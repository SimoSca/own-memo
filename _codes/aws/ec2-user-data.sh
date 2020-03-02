#!/bin/bash

yum install httpd -y
chkconfig httpd on

echo "$HOSTNAME" > /var/www/html/index.html
chown apache /var/www/html/index.html
systemctl start httpd
