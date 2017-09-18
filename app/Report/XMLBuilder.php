<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.09.2017
 * Time: 16:23
 */

namespace App\Report;


final class XMLBuilder
{

    static function sxml_append(\SimpleXMLElement $to, \SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }

    private function __construct()
    {
    }

    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

}