#!/bin/bash

#set -ex -> capire come fare perche' diff genera un exit 1 se i files differiscono (ed e' giusto),
#           ma di conseguenza causa l'interruzione dello script... quindi rivedere meglio l'uso dell'opzione -e in questo script

enomisLogPath="/data/log/cron | whatelse you wanna store your cron"
if [ ! -z "$enomisLogPath" ] && [ ! -d "$enomisLogPath" ] ; then mkdir $enomisLogPath ; fi

enomisAwsIpRangesUrl="https://ip-ranges.amazonaws.com/ip-ranges.json"
enomisApacheProxyListFile="<path to proxy list>"


writeLog(){
  local string="$1"
  local msg="[$(date  +"%Y-%m-%d_%H-%M-%S")] $string"
  echo "$msg"
  echo "$msg" >> "$enomisLogPath/httpd_cron_log"
}

updateApacheProxyList(){
  local awsIpRangesUrl="$1"
  local destFile="$2"
  # Multiline list
  #curl https://ip-ranges.amazonaws.com/ip-ranges.json | jq -r '.prefixes[] | select(.service=="CLOUDFRONT") | .ip_prefix'
  # Plain list
  #curl https://ip-ranges.amazonaws.com/ip-ranges.json | jq -r '.prefixes[] | select(.service=="CLOUDFRONT") | .ip_prefix'  | tr '\n' ' '

  { \
    echo "# Amazon CloudFront IP Ranges" ; \
    echo "# Generated at $(date) by $0" ; \
  }  >> $destFile
  curl -s $awsIpRangesUrl | jq -r '.prefixes[] | select(.service=="CLOUDFRONT") | .ip_prefix' >> $destFile
}

reloadApache(){
  # Test before reload
  httpd -t > /dev/null 2>&1
  if [ $? -ne 0 ] ; then
    writeLog "[ERROR] httpd -t returns error..."
    exit 1
  fi
  # reload apache
  systemctl reload httpd
}

endscript(){
  local tmpFile="$1"
  writeLog "All goes right!"

  # remove TempFile
  writeLog "remove tmp file generated"
  rm $tmpFile
}

main(){
  # NOTE: mktemp with template is compliant also with macos
  local tmpFile="$(mktemp tmp.XXXXXXXXXX -ut)"
  writeLog "Starting Apache update..."
  writeLog "Update ProxyList via remote amazon url"
  updateApacheProxyList "$enomisAwsIpRangesUrl" "$tmpFile"

  # Before update and reload, check if files are identical (but comments)... if so, then avoid extra tasks
  diff -qs  <( cat $enomisApacheProxyListFile | sed '/^#/ d' ) <( cat $tmpFile | sed '/^#/ d' ) > /dev/null 2>&1
  if [ $? -eq 0 ] ; then
    writeLog "IpList isn't changed. No more actions are required."
    endscript "$tmpFile"
    exit 0
  fi

  # Create temporary copy of original list
  local tmpListFile="$enomisApacheProxyListFile.tmp"
  writeLog "IpList is changed: generate temp file to backup and restore if something went wrong"
  echo "# Temp file generated on $(date) by enomis cron script" > $tmpListFile
  cat $enomisApacheProxyListFile >> $tmpListFile

  # Now override file
  cp $tmpFile $enomisApacheProxyListFile

  # Reload Apache
  writeLog "Try reload apache..."
  reloadApache

  # If reload not working, first try to revert the changes
  if [ $? -ne 0 ] ; then
    writeLog "an error occurred... try restore previous"
    cp $tmpListFile $enomisApacheProxyListFile
    reloadApache
    if [ $? -ne 0 ] ; then
      # TODO: add sendmail or something else
      writeLog "[ERROR] can't reload apache..."
      exit 1
    fi
  fi

  endscript "$tmpFile"
}

main

# Some Refs:
# - https://aws.amazon.com/blogs/security/how-to-automatically-update-your-security-groups-for-amazon-cloudfront-and-aws-waf-by-using-aws-lambda/ -> for aws security groups
# - https://www.andreapernici.com/shell-script-per-riavviare-apache-in-automatico-quando-down/
# - https://gist.github.com/janeczku/6f60b83378ad845f912c#file-cloudfront-ip-ranges-updater-sh

# Some Useful extra, if Apache doesn't restart:
# ipcs -s apache
# ipcs -s | grep apache | perl -e 'while () { @a=split(/\s+/);print `ipcrm sem $a[1]`}'
# cd /var/lock/subsys
# rm apache2
# /etc/init.d/apache2 restart