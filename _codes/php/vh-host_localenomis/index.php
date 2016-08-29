<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title>Host LocalEnomis</title>
</head>
<style>
	body{
		background-color: rgba(89, 230, 210,0.5);
		max-width: 900px;
		margin: 0 auto;
	}
	h3,h4{
		display:block;
		width: 100%;
		border-bottom: 1px solid purple;
	}
	h4{
		display: inline;
	}
	emph{
		background-color: rgba(100,100,100,0.3);
		padding: 0 2px ;
	}
	xmp{
		background-color: rgba(118,187,205,0.5);
	}
</style>
<body>


	Virtual host locale implementato per test su CORS, JSONP e altro ancora!

	<p>Per la gestione degli host virtuali vedi i seguenti punti:
	<ol>
		<li><a href="#windows_vhost">Host in Windows</a></li>
		<li><a href="#http_vhost">HTTP VHost</a></li>
		<li><a href="#https_vhost">HTTP[S(sl)] VHost</a></li>
		<li><a href="#trust">Abilitare Certificato</a></li>
	</ol>
	</p>

	<div id="windows_vhost">
		<h3>Host in Windows</h3>
		Schema per la gestione di multipli host che puntano a questa directory: per fare in modo che il browser reindirizzi particolari domini alla macchina stessa, devo impostare tali domini a dns locale:
		<xmp>
		## added by Simone Scardoni in C:\Windows\System32\drivers\etc\hosts
	
		127.0.0.1 host.localenomis
		127.0.0.1 host1.localenomis
		127.0.0.1 host2.localenomis
		127.0.0.1 host3.localenomis
		127.0.0.1 host4.localenomis
		127.0.0.1 host5.localenomis
		</xmp>
	</div>

	<div id="http_vhost">
		<h3>HTTP VHost</h3>
		Poi devo dire ad APACHE come reindirizzare questi dns: se gli host sono HTTP tipicamente viaggiano sulla porta 80.
		<xmp>
		## in apache\conf\extra\httpd-vhosts.conf
	
		NameVirtualHost *:80
	
		<VirtualHost host.localenomis>
		    ServerAdmin webmaster@dummy-host2.example.com
		    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/vh-host_localenomis/"
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
		</xmp>
	</div>

	<div id="https_vhost">
		<h3>HTTP[S(sl)] VHost</h3>
		<p>Ora e' necessario svolgere ulteriori passaggi, in quanto la crittografia SSL e' una crittografia asimmetrica e richiede una chiave pubblica, una privata e un certificato. </p>
		<p>Questi tre files sono gia' presenti in XAMPP ma il certificato risulta collegato solamente a LOCALHOST, il che implica l'impossibilita' di usare tale certificato per uno dei miei DNS-redirect creati al primo punto.</p>

		<h4>Generazione Files</h4>
		<p>Per ovviare a questo problema devo generare opportuni files per abilitare la crittografia con un dns, diciamo <b>host.localenomis</b>. Per fare questo mi sposto col terminale in <emph>C:\xampp\apache\bin</emph> e svolgo le seguenti operazioni:
		
		<xmp>
		set OPENSSL_CONF=C:\xampp\apache\conf\openssl.cnf
		set RANDFILE=C:\Temp\.rnd
		openssl genrsa -out vhost-server.key 1024
		openssl req -nodes -new -key vhost-server.key -out vhost-server.csr
			dopo questo comando trovo il testo:
				If you enter '.', the field will be left blank.
				-----
			mi limito a inserire sempre il punto, tranne:
				Country Name (2 letter code) [AU]:IT
				Organization Name [...]:host.localenomis
				Common Name [...]:host.localenomis

				A challenge password []:99vhost

		openssl x509 -req -days 365 -in vhost-server.csr -signkey vhost-server.key -out vhost-server.crt
		mv vhost-server.crt ..\conf\ssl.crt\
		mv vhost-server.csr ..\conf\ssl.csr\
		mv vhost-server.key ..\conf\ssl.key\
		</xmp>

		<h4>Definizione VHOST</h4>
		<p>
			Per fortuna XAMPP di default ha abilitato SSL, ovvero ha gia' decommentato gli opportuni moduli e inserito le opportune librerie ".dll" da <emph>php.ini</emph> e <emph>httpd.conf</emph>, pertanto non mi rimane che impostare i virtual host anche per https, ricordando che tipicamente il protocollo https lavora sulla porta 443. Di seguito le configurazioni in <emph>httpd-ssl.conf</emph>:
		</p>

		<xmp>
		## in apache\conf\extra\httpd-ssl.conf

		Listen 443

		<VirtualHost host.localenomis:443>
		    DocumentRoot "C:/xampp/htdocs/www/ArchivioIn/public/vh-host_localenomis/"
		     ServerName host.localenomis
		 
		    SSLEngine on
		    SSLCertificateFile "conf/ssl.crt/vhost-server.crt"
		    SSLCertificateKeyFile "conf/ssl.key/vhost-server.key"
		        <FilesMatch "\.(cgi|shtml|phtml|php)$">
		        SSLOptions +StdEnvVars
		    </FilesMatch>
		    <Directory "C:/xampp/apache/cgi-bin">
		        SSLOptions +StdEnvVars
		    </Directory>
		    BrowserMatch "MSIE [2-5]" \
		             nokeepalive ssl-unclean-shutdown \
		             downgrade-1.0 force-response-1.0
		    CustomLog "C:/xampp/apache/logs/ssl_request.log" \
		          "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
		</VirtualHost>
		</xmp>

		<p>Come si puo' vedere ho impostato <b>host.localenomis</b> sia via http in <emph>httpd-vhost.conf</emph> sia sia via https in <emph>httpd-ssl.conf</emph> duplicando del codice, pratica che ammetto non amare molto... per ovviare a questo potrei usare, per ciascun file.con, un costrutto della forma:
		<xmp>
		<VirtualHost *:80>
		        include /etc/apache2/vhost.conf.d/site1
		</VirtualHost>

		<VirtualHost *:443>
		        include /etc/apache2/vhost.conf.d/site1
		        include /etc/apache2/vhost.conf.d/site1-ssl
		</VirtualHost>
		</xmp>
	</div>

	<div id="trust">
		<h3>Abilitare Certificato</h3>
		<p>A questo punto la parte noiosa e' fatta, ma provando il sito in https dovrei avere ancora problemi, in quanto il cerficato risulta insicuro, essendo stato creato in locale. Per questo motivo l'unica cosa che mi rimane da fare col browser e' importare manualmente il cerficato comandando cosi' al browser di considerarlo sicuro. L'operazione varia a seconda del browser, ma tipicamente lo dovrei trovare in <emph>impostazioni</emph> , <emph>avanzate</emph>.</p>
	</div>

	<script type="text/javascript">
		var qs = document.querySelectorAll('xmp');
		for(var i in qs){
			var el = qs[i];
			// console.log(qs[i].innerText)
			if(typeof el.innerText == "undefined") continue;
			var text =  el.innerText.replace(/()?\n(\r)?(    |\t)/gi, '\n');
			el.innerText = text;
		}
	</script>

</body>
</html>