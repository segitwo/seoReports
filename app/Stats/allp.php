<?php
/*include ("xmlrpc.inc");
require 'AllPositions.php';*/

use Stats;

$api = new xf3\AllPositions('de64f44f92a4053e6fff3fcc366987c4');

$report = $api->get_report(257712, null, null, null, null);


$idSe1 = $report["sengines"][0]["id_se"];
$nameSe1 = $report["sengines"][0]["name_se"];

$idSe2 = $report["sengines"][1]["id_se"];
$nameSe2 = $report["sengines"][1]["name_se"];


$finalArray = [];
foreach($report["queries"] as $query) {
    
    $position1 = $report["positions"][$idSe1 . "_" . $query["id_query"]];
    $position2 = $report["positions"][$idSe2 . "_" . $query["id_query"]];
    
    $finalArray[] = array(
        "query" => $query["query"],
        $nameSe1 => array(
            "position" => $position1["position"],
            "change_position" => $position1["change_position"]
        ),
        $nameSe2 => array(
            "position" => $position2["position"],
            "change_position" => $position2["change_position"]
        )
    );
}

header("Content-Type: text/html; charset=utf-8");
echo "<pre>";
echo print_r($finalArray, 1);

