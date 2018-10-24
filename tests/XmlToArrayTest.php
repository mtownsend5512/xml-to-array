<?php

use PHPUnit\Framework\TestCase;

class XmlToArray extends TestCase
{

    /** @test array */
    protected $testArray = [];

    /** @test string */
    protected $testXml;

    public function setUp()
    {
        $this->testArray = [
            'carrier' => 'fedex',
            'id' => 123,
            'tracking_number' => '9205590164917312751089',
        ];
        $this->testXml = '<?xml version="1.0"?><root><carrier>fedex</carrier><id>123</id><tracking_number>9205590164917312751089</tracking_number></root>';
    }

    /** @test */
    public function xml_can_convert_to_array()
    {
        $this->assertEquals(xml_to_array($this->testXml), $this->testArray);
    }
}
