<?php

//use PHPUnit\Framework\TestCase;

class ETestTest extends \PHPUnit_Framework_TestCase{

    public function testName()
    {
        $t = new ETest();
        $this->assertEquals('ETest', $t->name);
//        $this->assertEquals('etest', $t->name);

    }

}