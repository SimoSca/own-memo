<?php

function gettoken() {
    GLOBAL $script, $characters, $lasttoken;
    $c = 0;

    // delete whitespace
    while (($c = fgetc($script)) == ' ' || $c == "\t" || $c == "\n" || $c == "\r");

    // exit if EOF is reached
    if (feof($script)) exit;

    // match numbers
    if (is_numeric($c)) {
        $nextchar = fgetc($script);

        while(is_numeric($nextchar)) {
            $c .= $nextchar;
            $nextchar = fgetc($script);
        }

        // the last character read was not a number, put it back
        fseek($script, -1, SEEK_CUR);
        $lasttoken = $c;
        return FOO_NUMBER;
    }

    if ($c == "=") {
        return FOO_ASSIGNEQUALS;
    }

    if ($c == "+") {
        return FOO_PLUS;
    }

    if ($c == "*") {
        return FOO_MULTIPLY;
    }

    if ($c == ";") {
        return FOO_SEMICOLON;
    }

    if ($c == "\"") {
        $nextchar = fgetc($script);

        while($nextchar != "\"") {
            if ($nextchar == "\n") {
                die("Fatal error: Unterminated string\n");
            }
            $c .= $nextchar;
            $nextchar = fgetc($script);
        }

        // note, we don't put the last character back here as it is the closing double-quote
        // trim off the double quote at the beginning
        $lasttoken = trim($c, "\" \t\n\r");
        return FOO_STRING;
    }

    if (is_string($c)) {
        $nextchar = fgetc($script);
        while($nextchar != "\n" && in_array($nextchar, $characters)) {
            $c .= $nextchar;
            $nextchar = fgetc($script);
        }

        // last character was not a letter, put it back
        fseek($script, -1, SEEK_CUR);
        $lasttoken = trim($c);

        // is this a print statement? If so, it's special
        switch($lasttoken) {
            case "print":
                return FOO_PRINT;
                break;
            default:
                return FOO_VARIABLE;
        }
    }
}