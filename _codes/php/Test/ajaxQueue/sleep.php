<?php

// pagina php per sleep dinamico, al fine di testare chiamate ajax

$sleep = ($_GET['sleep']) ? $_GET['sleep'] : 5 ;

// current time
echo "Partenza: " . date('h:i:s') . "\n";

sleep($sleep);

// wake up !
echo "<br/>Fine: " . date('h:i:s') . "\n";

?>

<html>
	<body>
		<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
		<script>

		function ajaxQueue(){
			var __chain = (function(){
					var dfr = $.Deferred();
					dfr.resolve();
					var ajaxChain = dfr.promise();
					
					return	function (url){
				  		var dfr = $.Deferred();
			  			ajaxChain.always(function(){
			    			console.log(url);
			    			$.ajax({
			    				'url': url,
			    				'method': 'GET'})
			    			.done(function( data, textStatus, jqXHR ) {
			      				dfr.resolve(data, textStatus, jqXHR);})
			    			.fail(function( jqXHR, textStatus, errorThrown ) {
			      				dfr.reject(jqXHR, textStatus, errorThrown);});
			  			});
			  			ajaxChain = dfr.promise();
			  			return ajaxChain;
					};
			})();

			__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=1').always(function(){
			  console.log('1 ' + new Date());
			});
			__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=10').always(function(data, txt, jq){
			  // console.log(data);
			  // console.log(txt)
			  // console.log(jq)
			  console.log('10 ' + new Date());
			});
			__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=5').always(function(){
			  console.log('5 ' + new Date());
			});
			__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=2').always(function(){
			  console.log('2 ' + new Date());
			});
			__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=1').always(function(){
			  console.log('1 bis' + new Date());
			});
			setTimeout(function(){
				__chain('http://localhost/www/ArchivioIn/public/Test/sleep.php?sleep=3').always(function(){
				  console.log('3 timeout' + new Date());
				});
			}, 30000);
		}

		$(function(){
			ajaxQueue();
		});

		</script>
	</body>
</html>