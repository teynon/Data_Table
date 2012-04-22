<html>
<head>
<style>
table {
 border: 2px solid #888888;
 border-collapse: collapse;
}
th, td {
 border: 1px solid #999999;
}
</style>
</head>
<body>
<?php
require "Data_Table.php";
require "SQL_Object.php";

$id_link = mysql_connect("localhost", "root", "");
mysql_select_db("testing");

$table = new Data_Table();
$table->headerRepeat = 30;

$sql = "SELECT * FROM equipment";
$query = new SQL_Object($sql);

$keys = $query->data[0];
$columns = count($keys);

$header = array();
foreach ($keys as $key => $value) {
	$header[$key] = $key;
}

$table->addHeader($header);

foreach ($query as $key => $value) {
	$table->addRow($value);
}

//$table->sort("SN");

$table->table->setAttribute_r("width", "width", floor(100 / ($columns-1)) . "%", array("td", "th"));
$table->table->setAttribute_r("align", "center", floor(100 / ($columns-1)) . "%", array("td", "th"));

foreach ($table->tr as $row) {
	//$row->setAttribute_r("width", "width", floor(100 / $columns) . "%", array("td", "th"));
}

echo $table->generate();
?>
</body>
</html>
