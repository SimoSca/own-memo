<?php
   // this function converts variables to values as appropriate
function getval($token) {
    GLOBAL $variables;

    if ($token->token == FOO_VARIABLE) {
        return $variables[$token->val];
    } else {
        return $token->val;
    }
}