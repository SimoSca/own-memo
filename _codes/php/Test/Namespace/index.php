<?php
	require_once "Tree.php";
	require_once "vendor/autoload.php";
?>

<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title>Namespace Test</title>
</head>
<style>
	body{
		background-color: rgba(77,177,177,0.5);
		max-width: 900px;
		margin: 0 auto;
		padding: 1em;
	}
	p{
		margin-top: 1em;
	}
	h1,h2{
		display:block;
		width: 100%;
		margin-bottom: 2px solid purple;
	}
</style>
<body>
	<h1>Namespace</h1>
	<p>
		In php l'uso dei **namespace** risulta importante per richiamare funzioni ed evitare problemi relativi a conflitti tra nomi di funzion/classi.<br>
		Relativamente ai namespace l'unica cosa da ricordare e' che possono essere utilizzati:
		<ul>
			<li>in modo `relativo`, per cui una classe a priori si pensa essere richiamata anteponendo automativamente l'attuale namespace</li>
			<li>in modo `Fully Qualified`, facendo precedere tutto da uno `\`, che sta ad indicare di omettere il namespace in cui si trova il codice chiamante la classe.</li>
		</ul>
		Oltre a questo, bisogna tenere presente che e' meglio avvalersi di mezzi per la gestione automatica dei namespace, perche' svolgerlo manualmente potrebbe portare a problemi se non si ha esperienza. A tal proposito `Composer` offre due tipologie per caricare in autoload:
		<ul>
			<li>`psr-0`</li>
			<li>`psr-4`</li>
		</ul>
	</p>
	
	<p>
		Per svolgere questo test presento la tree completa della directory:
		<pre>
		<?php 
			$tree = Tree::dirTreeToArray(__DIR__, ['/\.$/', '/\.\.$/','/vendor\/?/']);
			// var_dump($tree);
			Tree::plotTree($tree);
		?>
		</pre>
	</p>

	<pre>
		composer init
		aggiunto "autoload"
	</pre>

	<?php

		new Test(); //classmap
		// new Psr0\Test();
		new Psr4\Test();

	?>

</body>
</html>