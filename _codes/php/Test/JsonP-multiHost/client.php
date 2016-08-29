<!DOCTYPE> 
<html> 
<head> 
<script> 
function jsonp_request(url, num) { 
 
    var head = document.getElementsByTagName("head")[0]; 
    var script = document.createElement("SCRIPT"); 
 
    script.type = "text/javascript"; 
    script.src = url + "?cb=update_table&host=host"+num; 
    head.appendChild(script); 
} 
 
function update_table(data) { 
 
    var container = document.getElementsByTagName("div")[1]; 
    var html = ""; 
 
    for(var i = 0; i<data.length; i++) { 
        html += "<span>"+data[i].id+"</span>";
        html += "<span>"+data[i].title+"</span>"
        html += "<span>"+data[i].author+"</span>"; 
    } 
    container.innerHTML += html; 
}
 
window.onload = function() { 
 
    for(var i = 1; i<5; i++) { 
     
        jsonp_request("http://host"+i+".localenomis/libri.php", i); 
    } 
}    
</script> 
 
<style> 
div { width: 300px; } 
div span { display:block; float: left; width: 100px; } 
div span.th { color: blue; } 
</style> 
</head> 
 
<body> 
<div> 
    <span class="th">Id</span>
    <span class="th">Libro</span>
    <span class="th">Autore</span> 
</div> 
 
<div>
</div>


<div>
Schema per la gestione di multipli host che puntano a questa directory:
<pre>
## added by Simone Scardoni in C:\Windows\System32\drivers\etc\hosts

127.0.0.1 host.localenomis
127.0.0.1 host1.localenomis
127.0.0.1 host2.localenomis
127.0.0.1 host3.localenomis
127.0.0.1 host4.localenomis
127.0.0.1 host5.localenomis


## in apache\conf\extra\httpd-vhosts.conf

NameVirtualHost *:80

<VirtualHost host.localenomis>
    ServerAdmin webmaster@dummy-host2.example.com
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
    ServerName host.localenomis
    # ServerAlias *.localenomis
    #UseCanonicalName Off
    ErrorLog "logs/host-error.log"
    CustomLog "logs/host-access.log" common
</VirtualHost>

<VirtualHost host1.localenomis>
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
</VirtualHost>
<VirtualHost host2.localenomis>
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
</VirtualHost>
<VirtualHost host3.localenomis>
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
</VirtualHost>
<VirtualHost host4.localenomis>
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
</VirtualHost>
<VirtualHost host5.localenomis>
    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/Test/JsonP-multiHost"
</VirtualHost>
</pre>
</div>