<?php

namespace Mtownsend\XmlToArray;

use DOMDocument;

/**
 * @author Adrien aka Gaarf & contributors
 * @author Mark Townsend
 */
class XmlToArray
{
    /**
     * Convert valid XML to an array.
     *
     * @param string $xml
     * @param bool $outputRoot
     * @return array
     */
    public static function convert($xml, $outputRoot = false)
    {
        $array = self::xmlStringToArray($xml);
        if (!$outputRoot && array_key_exists('@root', $array)) {
            unset($array['@root']);
        }
        return $array;
    }

    protected static function xmlStringToArray($xmlstr)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        $root = $doc->documentElement;
        $output = self::domNodeToArray($root);
        $output['@root'] = $root->tagName;
        return $output;
    }

    protected static function domNodeToArray($node)
    {
        $output = [];
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::domNodeToArray($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t])) {
                            $output[$t] = [];
                        }
                        $output[$t][] = $v;
                    } elseif ($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if ($node->attributes->length && !is_array($output)) { // Has attributes but isn't an array
                    $output = ['@content' => $output]; // Change output into an array.
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = [];
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
}
