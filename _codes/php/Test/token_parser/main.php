<?php

function main() {
    GLOBAL $lasttoken, $precedence;

    while(1) {
        $stack = array();
        $operators = array();

        do {
            $token = gettoken();

            switch($token) {
                case FOO_NUMBER:
                    case FOO_VARIABLE:
                    case FOO_STRING:
                        $stack[] = new token(IS_OPERAND, $token, $lasttoken);
                        break;
                    default:
                        if ($token != FOO_SEMICOLON) {
                            // this removes higher-precedence operators in places of a new, lower-precedence one

                            while (count($operators) && $precedence[$operators[count($operators) - 1]->token] > $precedence[$token]) {
                                $higher_op = array_pop($operators);
                                array_push($stack, $higher_op);
                            }

                            $operators[] = new token(IS_OPERATOR, $token, NULL);
                        }
            }
        } while ($token != FOO_SEMICOLON);

        while (count($operators)) array_push($stack, array_pop($operators));

        execute($stack);
    }
}