<?php
// no direct access
defined( '_JEXEC' ) or die;

class PlgEnomisEnomisplugin extends JPlugin {

    public function onEnomisTest($par){
        return json_encode($par) ." <br/>-- by EnomisPlugin3! --<br/>" ;
    }

    public function onEnomisTest2($par){
        return json_encode($par) ." <br/>-- by EnomisPlugin! --<br/>" ;
    }

}

