<?php

/**
 * esempio di struttura base di un token:
 *
 * non utilizzata in alcun file
 */

class token {
    public $type;
    public $token;
    public $val;

    public function __construct($type, $token, $val) {
        $this->type = $type;
        $this->token = $token;
        $this->val = $val;
    }
}