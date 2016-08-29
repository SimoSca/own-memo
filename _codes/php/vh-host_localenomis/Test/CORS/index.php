<?php

$nothing = false;
if(isset($_GET['action'])){
	$action = $_GET['action'];

	if($action == "nocors"){
		html("Pagina di test CORS, con azione impostata <strong>nocors</strong>.<br/> Non modifico alcun header, ed infatti in questo modo la cors non e' impostata.");
	}
	elseif ($action == "cors") {
		$header = 'N.P.';
		// per multi domini
		$http_origin = $_SERVER['HTTP_ORIGIN'];
		$http_allowed = ["http://www.domain1.com", "http://localhost", "https://localhost", "http://host.localenomis", "https://host.localenomis"];
		if (in_array($http_origin, $http_allowed)){  
			$header = "Access-Control-Allow-Origin: $http_origin";
		    header($header);
		}
		html("Pagina di test CORS, con azione impostata <strong>nocors</strong>.<br/> Per effettuare la CORS ho impostato nello header <strong> $header </strong>.");
	}
	else{
		$nothing=true;
	}

}else{
	$nothing = true;
}

if($nothing) html("Nessuna richiesta action...");

function html($text){
	?>
	<!DOCTYPE>
	<!DOCTYPE html>
	<html>
	<head>
		<title>CORS Test</title>
	</head>
	<body>
		<?php
		echo $text;
		?>
	</body>
	</html>
	<?php
}

