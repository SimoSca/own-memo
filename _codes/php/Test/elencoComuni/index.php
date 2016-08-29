<?php
$fileName = 'elenco-comuni-italiani.csv';
$csvData = file_get_contents($fileName);
$lines = explode(PHP_EOL, $csvData);

// la prima riga contiene le intestazioni, pertanto non la considero
// var_dump($lines[0]);
unset($lines[0]);



$regioni = array();
$province = array();
$sigle = array();
$path = array();

$ar = array();

// NB: il codice e' appesantito dal fatto che avendo lettere accentate devo creare una corrispondenza indice - valore come per le tabella mysql, creando cosi' relazioni 1 a molti

foreach ($lines as $line) {

	// svolgo l'encode dei caratteri
	// echo $line;
	$echo = false;
	// if(preg_match("@forl@i", $line)) $echo = true;
	// stringa decodificata
	$line =mb_convert_encoding($line, 'UTF-8',
          mb_detect_encoding($line, 'UTF-8, ISO-8859-1', true));;
    $row = str_getcsv($line, ';');
    // if($echo) var_dump($row);
	

    // raccolgo i dati
    $citta = $row[5];
    $regione = $row[9];
    // le province sono citta' metropolitane
    $provincia = ($row[11] == '-') ? $row[10] : $row[11] ;
    $sigla = $row[13];
	
	// raccolgo le regioni    
	// if(!in_array($regione, $regioni) && strlen($regione) > 4) $regioni[] = $regione;
	// $prov = "$provincia ($sigla)";
	// raccolgo le province
	// if(!in_array($prov, $province) && strlen($prov) > 4) $province[] = $prov;
	
	if(!in_array($provincia, $sigle)) $sigle[$provincia] = $sigla;

    // array associativo completo
	if(!is_array($path[$regione]) && strlen($regione) > 4) $path[$regione] = array();
	if(!is_array($path[$regione][$provincia]) && strlen($provincia) > 4) $path[$regione][$provincia] = array();
	if(strlen($citta) > 2) $path[$regione][$provincia][] = $citta;
	
	// raccolto per "righe"
	// $ar[] = compact('citta', 'regione', 'provincia', 'sigla');
	
}
echo '<pre>';
// print_r(count($ar));
// print_r($ar);
print_r($path);
// print_r(json_encode($path));
// print_r($regioni);
// print_r($province);
print_r($sigle);
echo '</pre>';
