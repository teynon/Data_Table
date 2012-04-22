<?php
	require "Data_Table.php";

	$table = new Data_Table();
	$headers = array();
	$headers["Name"] = "Name";
	$headers["Phone"] = "Phone #";
	$headers["Email"] = "Email Address";
	$headers["Website"] = "Web Address";
	
	$table->addHeader($headers);
	
	$data = array();
	$data["Name"] = "Thomas";
	$data["Phone"] = "3304018393";
	$data["Email"] = "teynon1@gmail.com";
	$data["Website"] = "http://www.thomaseynon.com";
	
	$table->addRow($data);
	
	$data = array();
	$data["Name"] = "Andy";
	$data["Phone"] = "2223334444";
	$data["Email"] = "andy@blah.com";
	
	$table->addRow($data);
	
	$data = array();
	$data["Name"] = "George";
	$data["Phone"] = "2123334444";
	$data["Email"] = "ageorge@blah.com";
	
	$table->addRow($data);
	echo $table->generate();
	
	$table->sort("Name");
	//echo "<pre>" . print_r($table, true) . "</pre>";
	
	echo $table->generate();
	
	echo "<hr />";
	
	$table->rsort("Name");
	echo $table->generate();
	
	echo "<hr />";
	$table->sort("Name");
	$table->headerRepeat = 2;
	echo $table->generate();