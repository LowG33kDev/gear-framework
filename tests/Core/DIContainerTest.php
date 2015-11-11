<?php

namespace Gear\Test\Core;

class DIContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testWithParameter()
    {
        $c = new \Gear\Core\DIContainer();
        $c['params1'] = 'value';
        $this->assertSame('value', $c['params1']);
    }

    public function testWithClosure()
    {
        $c = new \Gear\Core\DIContainer();
        $c['service1'] = function(){
            return new \Gear\Test\Fixtures\Service();
        };
        $this->assertInstanceOf('\Gear\Test\Fixtures\Service', $c['service1']);
    }
}