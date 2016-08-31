<html>
<body bgcolor="gray">

<h1> Esempio di funzionamento polimorfico tra classi </h1>

Scopo &egrave; quello di implementare un array di animali in PHP confrontandolo poi con l'analogo codice in C++ per sottolineare le differenze.</br>
Anzitutto vediamo il codice PHP:</br>

<?php

class Cane{

		public function verso(){
		echo "bau bau!!!</br>";
		}
}

class Gatto{

		public function verso(){
				echo "miao miao!!!</br>";
		}
}

$animali=array();
$animali[]= new Cane();
$animali[]= new Gatto();

foreach ($animali as $animale){
		echo "Io sono ".get_class($animale)." e il mio verso e' ";
		$animale->verso();
}

?>

Volendo ora implementare modalit&agrave; differenti di visualizzazione da cambiare agilmente, vi sono due strade:</br>
strada 1, implementare la scelta della visualizzazione direttamente all'interno della classe:</br>

<?php
class Cane1{

		public function verso($scrivi){
		if($scrivi==0) {echo "bau bau!!!</br>";}
		else if ($scrivi==1) {echo "abbaio!!! </br>";}
		}
}

class Gatto1{

		public function verso($scrivi){
		if($scrivi==0) 		echo "miao miao!!!</br>";
		else if ($scrivi==1) {echo "miagolo!!! </br>";}
		}
}

$animali=array();
$animali[]= new Cane1();
$animali[]= new Gatto1();
$mostra=array("0","1");
foreach( $mostra as $mostro ){
		echo "Visualizzazione di tipo ".$mostro.": </br>";
		foreach ($animali as $animale){
		echo "&emsp; Io sono ".get_class($animale)." e il mio verso e' ";
		$animale->verso($mostro);
}
}

?>

strada 2, creare una classe che ha il compito di gestire la modalit&agrave; di visualizzazione, in modo da non dover toccare le classi cane e gatto:</br>
<?php
class Cane2{
		public function verso($visualizzatore){
			return $visualizzatore->verso($this);
		}
}

class Gatto2{
		public function verso($visualizzatore){
			return $visualizzatore->verso($this);
		}
}

class visualizzatore1{
		public function	verso($class){
				if (get_class($class) == "Cane2"){
					echo "Baaaaaau!</br>";
				}
				else if (get_class($class) == "Gatto2"){
					echo "Baaaaaau!</br>";
				}
	}
}
class visualizzatore2{
		public function	verso($class){
				if (get_class($class) == "Cane2"){
					echo "Abbaio al postino</br>";
				}
				else if (get_class($class) == "Gatto2"){
					echo "Miagolo alla padrona</br>";
				}
	}
}

$animali=array();
$animali[]= new Cane2();
$animali[]= new Gatto2();
$mostra=array("1","2");
foreach( $mostra as $mostro ){
		echo "Visualizzatore".$mostro.": </br>";
		foreach ($animali as $animale){
				echo "&emsp; Io sono ".get_class($animale)." e il mio verso e' ";
		$visualizzatore = "visualizzatore".$mostro;
		$visualizzatore = new $visualizzatore();
		$animale->verso($visualizzatore);
		}
}
?>

</br>
L'ultimo metodo da me proposto in php non risulta di particolare utilit&agrave; lo ammetto... volendo stare al mio lato pigro, lo preferirei solamente in quanto nonostante debba sbattermi nel creare una classe per ogni modalit&agrave; di visualizzazione, non dovro' andare a toccare le classi Cane e Gatto. Questo risulta comodo in quanto se avessi 100 classi di Animali, volendo aggiungere o modificare un Visualizzatore, dovrei entrare in ogni classe e modificare il singolo metodo (se per ogni classe esiste uno specifico file, allora dovrei editare 100 files!), ma mi basterebbe modificare o creare a cascata la modalita' di visualizzazione.</br>
Risulta invece Particolarmente utile in C++, qualora per ogni classe si usi la buona abitudine di dichiararla in un Header e implementarla in un corrispettivo file (in genere con estensione cpp o c), effettuando cosi' una compilazione separata di ciascuna classe ottenendo singoli file binari (che poi saranno collegati in fase di compilazione del programma mediante link). In questo caso se le classi fossero di grandi dimensioni, per arrivare al programma finale oltre ad editare ogni singola classe dovrei anche ricompilarla... invece con l'ausilio della classe Visualizzatore, l'unica ricompilazione avviene su questa stessa classe, lasciando tutte le classi di animali immutate... una cosa bella direi!


</body>
</html>

