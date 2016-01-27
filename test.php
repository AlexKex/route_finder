<?php
/**
 * Created by PhpStorm.
 * User: apryakhin
 * Date: 26.01.16
 * Time: 16:33
 */

$map2 = [
    ["A", "B", 3],
    ["A", "C", 4],
    ["B", "E", 6],
    ["B", "D", 5],
    ["C", "D", 2],
    ["C", "F", 10],
    ["D", "F", 1],
    ["D", "E", 1],
    ["E", "F", 7]
];

$finder = new \src\routeFinder();

$start = "A";
$finish = "F";
$info = $finder->findRoute($start, $finish, $map2);

echo "Route between ".$start." and ".$finish." \nRoute price : ".$info["price"]." \nTransit points : ".implode(" ", $info["route"])."\n";