<?php
require "Data_Table.php";
require "SQL_Object.php";

$contents = file_get_contents("https://www.google.com/search?q=GOOG");

$dom = new DOMDocument();
$dom->loadHTML($contents);

$tables = $dom->getElementsByTagName("table");

$data_table = array();
$key = 0;

foreach ($tables as $table) {
    $data_table[$key] = new Data_Table();
    $data_table[$key]->importDOM($table);
    
    echo $data_table[$key]->generate();
}