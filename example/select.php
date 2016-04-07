<?php
/*
	Select Example
	Core Name : techproducts
	Available Fields : id, title
*/

require_once("../solr_php.php");

$solr_php = new Solr_php();
$solr_php->solr_host = 'http://localhost:8983/';

//$data = array(); //don't pass any $data parameter if you want to select all records

//this will select the record(s) with id = 1
$data = 
array(
		'id' => '1',
		'title' => 'test'
);

//let's check if the delete command already executed successfully
$data = $solr_php->select('techproducts', $data);

echo $data;

?>