<?php
/**
 * Created by PhpStorm.
 * User: apryakhin
 * Date: 26.01.16
 * Time: 16:33
 */

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.class.php';

    require_once $fileName;
}
spl_autoload_register('autoload');

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