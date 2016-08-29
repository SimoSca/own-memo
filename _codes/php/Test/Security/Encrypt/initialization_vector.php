<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>IV encryption</title>
</head>
<body>

<style>
	body{
		background-color: rgba(70,100,30,0.5);
		margin: 1em auto 2em;
		max-width: 900px;
	}
	xmp{
		background-color: rgba(100,200,50,0.5);
		padding: 0 1em;
	}
</style>
<h1>Initialization Vector</h1>

esempio preso da <a href="http://stackoverflow.com/questions/11821195/use-of-initialization-vector-in-openssl-encrypt" target="_blank">http://stackoverflow.com/questions/11821195/use-of-initialization-vector-in-openssl-encrypt</a>

	<div>
		An IV is generally a random number that guarantees the encrypted text is unique.<br/>
	
		To explain why it's needed, let's pretend we have a database of people's names encrypted with 	the key 'secret' and no IV.
	</div>
	<xmp>
		<?php
		$ar = ["john", "alex", "john"];
		// la mia secretHash
		$secret = "zioPino";
		echo "\n\r";
		echo "Chiave segreta: $secret, Funzione: hash_hmac, Algoritmo: md5 ";
		echo "\n\rDatabase: \n\r";
		$i = 0;
		foreach ($ar as $value) { 
			$i++;
			echo "id: $i, Valore: $value, Secret: " . hash_hmac('md5', $value, $secret)."\n\r";
		}
		?>
	</xmp>
	<div>
		If John 1 knows his cipher text and has access to the other cipher texts, he can easily find other people named John.
		<br/>
		Now in actuality, an encryption mode that requires an IV will always use one. If you don't specify an IV, it's automatically set to a bunch of null bytes e cosi' le secret risultano ancora uguali, pur utilizzando openssl_encrypt.
	</div>
	<xmp>
		<?php
		$ar = ["john", "alex", "john"];
		$secret = "zioPino";
		echo "\n\r";
		echo "Chiave segreta: $secret, create_iv(iv_size, 'lol'), Funzioni: mcrypt*_get_iv_size*_create_iv* e openssl_encrypt";
		echo "\n\rDatabase: \n\r";
		$i = 0;
		foreach ($ar as $value) { 
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, 'lol');
			echo "iv_size: $iv_size \n\r";
			$encryptedMessage = openssl_encrypt($value, 'AES-256-CBC' , $secret /*,0, $iv*/);
			$i++;
			echo "id: $i, Valore: $value, Secret: $encryptedMessage \n\r";
		}
		?>
	</xmp>
	<div>
		As you can see, the two 'John' cipher texts are now different. Each IV is unique and has influenced the encryption process making the end result unique as well. John 1 now has no idea what user 3's name is.
		<br/>
		Decryption requires the use of the same IV the text was encrypted with of course, which is why it must be stored in the database. The IV is of no use without the key so transmitting or storing it with the encrypted text is of no concern.
		<br/>
		This is an overly simplistic example, but the truth is, not using IV's has serious security ramification
	</div>


	<hr>


	<div>
		You also may want to utilize some of PHP's IV generation functions. I think this should work for you:
	</div>
	<xmp>
		<?php
		$ar = ["john", "alex", "john"];
		$data = []; // extra per passaggio successivo
		$secret = "25c6c7ff35b9979b151f2136cd13b0ff";
		echo "\n\r";
		echo "Chiave segreta: $secret, create_iv(iv_size, MCRYPT_RAND), Funzioni: mcrypt*_get_iv_size*_create_iv* e openssl_encrypt";
		echo "\n\rDatabase: \n\r";
		$i = 0;
		foreach ($ar as $value) { 
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			echo "iv_size: $iv_size \n\r";
			$encryptedMessage = openssl_encrypt($value, 'AES-256-CBC' , $secret , 0, $iv);
			$i++;
			echo "id: $i, Valore: $value, Secret: $encryptedMessage \n\r";

			// utile per il passaggio successivo
			$data[] = $iv.$encryptedMessage;
		}
		?>
	</xmp>
	<div>
		In questo modo tutto risulta differente. <br/>
		For storage/transmission, you can simply concatenate the IV and cipher text like so:
	</div>
	<xmp>
	Codice:
		dentro al loop aggiungo array: $data[] = $iv.$encryptedMessage;
	Risultato:
		<?php foreach($data as $v) echo "\n\r\$data[] = " .$v; ?>
	</xmp>
	<div>
		Then on retrieval, pull the IV out for decryption:
	</div>
	<xmp>
		<?php
		$ar = ["john", "alex", "john"];
		$data = []; // extra per passaggio successivo
		$secret = "25c6c7ff35b9979b151f2136cd13b0ff";
		$i = 0;
		// encrypt
		echo "\r\nEncrypt:\n\r";
		foreach ($ar as $value) { 
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$encryptedMessage = openssl_encrypt($value, 'AES-256-CBC' , $secret , 0, $iv);
			// extra aggiunto per rendere piu leggibile il codice, 
			// e per fare in modo che a occhio umano le stringhe che si concatenano in $data[] appaiano come una sola
			// ma e' opzionale, e devo pensarci bene: se un malintenzionato capisce che uso un base64 allora e' come non aver cambiato nulla
			$data[] = base64_encode($iv.$encryptedMessage);
			echo "id: $i, Valore: $value, \$data[]: $data[$i] \n\r";
			$i++;
		}
		// decrypt
		$i = 0;
		echo "Decrypt:\n\r";
		foreach ($ar as $value) { 
			$dt = base64_decode($data[$i]);
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = substr($dt, 0, $iv_size);
			echo "iv_size: $iv_size \n\r";
			$decryptedMessage = openssl_decrypt(substr($dt, $iv_size), 'AES-256-CBC' , $secret , 0, $iv);
			echo "id: $i, Valore teorico: $value, Secret: $decryptedMessage \n\r";
			$i++;
		}
		?>
	</xmp>
	<div>
		E con questo si chiude il giro. <br/>
		Per cultura e' possibile vedere il link <a href="https://www.leaseweb.com/labs/2014/02/aes-php-mcrypt-key-padding/" target="_blank">https://www.leaseweb.com/labs/2014/02/aes-php-mcrypt-key-padding/</a>.
	</div>
</body>
</html>