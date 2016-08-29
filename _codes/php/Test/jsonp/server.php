 <?php

    if(array_key_exists("callback", $_GET)){
    
    	$callback=$_GET["callback"];
    	echo $callback;
	

    	if($callback=='getCompany')
    	$response="({\"company\":\"Google\",\"image\":\"xyz.jpg\"})";

    	else
    	$response="({\"position\":\"Development Intern\"})";
    	echo $response;
    }

	if(array_key_exists("cumpa", $_GET)){
    	//file_put_contents(rand().".txt", $_GET["cumpa"]);
    	$response="{\"company\":\"".$_GET["cumpa"]."\"}";
    	echo $response;
	}    


    ?>    