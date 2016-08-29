<?php

function execute(&$stack) {
    GLOBAL $variables;
    $operator = array_pop($stack);

    if ($stack[count($stack) - 1]->type == IS_OPERATOR) {
        $right = execute($stack);
    } else {
        $right = array_pop($stack);
    }

    if (count($stack)) {
        if ($stack[count($stack) - 1]->type == IS_OPERATOR) {
            $left = execute($stack);
        } else {
            $left = array_pop($stack);
        }
    }

    switch($operator->token) {
        case FOO_ASSIGNEQUALS:
            $variables[$left->val] = getval($right);
            break;
        case FOO_PLUS:
            return new token(IS_OPERAND, FOO_NUMBER, getval($left) + getval($right));
        case FOO_MULTIPLY:
            return new token(IS_OPERAND, FOO_NUMBER, getval($left) * getval($right));
        case FOO_PRINT:
            print getval($right);
            print "\n";
    }
}