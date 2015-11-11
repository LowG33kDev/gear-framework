<?php

namespace Gear\Test\Util;

use \Gear\Util\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testWithDefaultValues()
    {
        $collection = new Collection(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertArrayHasKey('key1', $collection->getData());
        $this->assertEquals('value2', $collection->key2);
        $this->assertEquals('value2', $collection['key2']);
        $this->assertNull($collection['key3']);
        $this->assertNull($collection->key4);
    }

    public function testValues()
    {
        $collection = new Collection();
        $collection->setData(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertArrayHasKey('key1', $collection->getData());
        $this->assertEquals('value2', $collection->key2);
        $this->assertEquals('value2', $collection['key2']);
        $this->assertEquals(2, count($collection));

        $collection->clear();
        $this->assertNull($collection['key1']);
        $this->assertNull($collection->key2);
        $this->assertEquals(0, count($collection));
    }

    public function testUnkownValues()
    {
        $collection = new Collection();

        $this->assertNull($collection['key3']);
        $this->assertNull($collection->key4);
    }
}
