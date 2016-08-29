<?php
    define("FOO_NUMBER", 0);
    define("FOO_VARIABLE", 1);
    define("FOO_ASSIGNEQUALS", 2);
    define("FOO_PRINT", 3);
    define("FOO_STRING", 4);
    define("FOO_SEMICOLON", 5);
    define("FOO_PLUS", 6);
    define("FOO_MULTIPLY", 7);
    define("IS_OPERATOR", 0);
    define("IS_OPERAND", 1);


    // you can change the values in $precedence, as long you keep the order the same
    $precedence = array (FOO_PRINT => 0, FOO_ASSIGNEQUALS => 1, FOO_PLUS => 2, FOO_MULTIPLY => 3);

    // includes
    $files = [
        "token.php",
        "getval.php",
        "execute.php",
        "main.php",
        "gettoken.php"
    ];

    foreach($files as $f)  require($f); 


    $script = fopen("script.foo", "r");
    $characters = array_merge(range('a', 'z'), range('A', 'z'));
    $characters[] = "_";
    $variables = array();

    function cleanup() {
        GLOBAL $script;
        fclose($script);
    }

    register_shutdown_function("cleanup");
    main();
?>