<?php

if (!function_exists('xml_to_array')) {
    /**
     * Convert valid XML to an array.
     *
     * @param string $xml
     * @param bool $outputRoot
     * @return array
     */
    function xml_to_array($xml, $outputRoot = false)
    {
    	return \Mtownsend\XmlToArray\XmlToArray::convert($xml, $outputRoot);
    }
}
