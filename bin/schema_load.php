<?php
require_once __DIR__ . '/../bootstrap.php';

use Core\App;

$db = App::get('database');
$schemaFile = __DIR__ . '/../database/schema.sql';
$sql = file_get_contents($schemaFile);

//throw new Exception('Testing exception handling!'); -> test

try {
    $parts = array_filter(explode(';', $sql));
    //var_dump($parts);die;
    foreach($parts as $sqlPart) {
        $db->query($sqlPart);
    }
    echo "Schema loaded Successfully! \n";
} catch (Exception $e) {
    echo "Error loading schema: " . $e->getMessage() . "\n";
}
