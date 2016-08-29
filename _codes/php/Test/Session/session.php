<?php

include '../config.php';

session_start();

define('IK_DURATION', 20);

class manage{
	
	public function __construct(){
		echo 'prima di maneggiare:';
		var_dump($_SESSION);
		var_dump($_GET);
		var_dump($_COOKIE);
		self::check();
	}

	public static function check(){

		if( isset($_GET['in'])  ){
			self::enter();
		}
		elseif(isset($_GET['out'])){
			self::out();
		}
		elseif(isset($_GET['cookie']) ){
			self::createCookie();
		}

		echo 'ora controllo:<br/>';
		self::controll();
	}

	//questa funzione la uso per il login, quindi in teoria dopo aver compilato un form
	public static function	enter(){
		$_SESSION['in'] = 'us';
	}

	// anche questa in teoria la utilizzo solo dopo aver compilato un form e inserito l'opzione
	// resta connesso
	public static function createCookie(){
	     //var_dump('create cookie');
	     //$this->time = time();
	    $time = time() + IK_DURATION;
	     $nick = 'cookie'; 
	     //var_dump($cookie_string);
	     setcookie('test_cookie_data' ,$nick, $time);  

	     $_SESSION['in'] = $nick;
	     //$class=__CLASS__;
	     //$class::startSession();
	 } 

	public static function out(){
		session_destroy();
		if(! empty($_SESSION)) $_SESSION= null;
		if (isset($_COOKIE) && array_key_exists('test_cookie_data', $_COOKIE)) {
		    unset($_COOKIE['test_cookie_data']);
		    setcookie("test_cookie_data" ,null); 
		 //echo 'lavoro lol sui cookie';
		}

	}

	public static function controll(){
		$s = 'non sei loggato';
		// prima uso checkCookie perche' in questo modo, ogni volta mi svolge
		// un update del time in cui il cookie verra' distrutto, 
		// altrimenti sarebbe piu' giusto controllare soltanto la sessione...
		// dunque volendo potrei anche invertirli.
		if(self::checkCookie()){
			$s = $_SESSION['in'].' sei loggato da cookie';
		}
		elseif(isset($_SESSION['in'])){
			$s = $_SESSION['in'].' sei loggato in sessione';
		}

		echo $s;
	}


	public static function checkCookie(){
	    $time = time(); 
	    if (isset($_COOKIE) && array_key_exists('test_cookie_data', $_COOKIE) ) {
	        $cookie_info = explode("&" , $_COOKIE['test_cookie_data']); 
	        var_dump($cookie_info);
	     //   echo 'size of : '.sizeof($cookie_info);
	     //   if(sizeof($cookie_info)<2){ return null;}
	     //   else {
	        $nick = $cookie_info[0]; 
	        setcookie("test_cookie_data" ,$nick, time()+IK_DURATION); 
	        //echo '** assetto cookie ***';
	        var_dump('managio cookie <br/>');
	       // var_dump($_COOKIE);
	       // se ci sono i cookie, li imposto come variabile di sessione
	       $_SESSION['in']=$nick;
	        return true;
	       // }
	    }
	    //var_dump('il cookie non esiste');
	    return false;
	}
}

// nb: session_destroy() e setcookie("...", null) distruggono la sessione e i cookie, ma i loro effetti
// 		non si applicano alle rispettive variabili $_SESSION e $_COOKIE, che risultano ben definite e invariate
// 		all'interno del medesimo script e fino ad un reload!!!
// 		proprio per questo in out devo usare unset. Inoltre in create cookie, visto che il setcookie non mette immediatamente a disposizione il particolare indice di cookie che sto' creando, inizio gia' a impostare la sessione.
// 		
// ERA PROPRIO NEI DETTAGLI QUI SOPRA CHE AVEVO L'INGHIPPO SULLE SESSIONI!!!

?>

<body>
<?php $m = new manage(); ?>

<div><a href="?out=ok"> out </a></div>
<div><a href="?in=ok"> in </a></div>
<div><a href="?cookie=ok"> cookie </a></div>
dopo aver maneggiato:<br/>
<?php
var_dump($_SESSION);
var_dump($_GET);
var_dump($_COOKIE);
?>
</body>

<?php
// for per creare le pagine: ovviamente le creero' alla fine di tutti i settaggi
for( $i =0; $i<3; $i++){

	$file = 'session-test_'.$i.'.php';
	$data = "<h1>Test sessione $i </h1>\r\n";
	$data .= '<?php include "'.basename(__FILE__).'";';
	//if(file_exists($file))	file_put_contents($file,$data);
}