<?php
/*
	Delete Example
	Core Name : techproducts
	Available Fields : id, title
*/

require_once("../solr_php.php");

$solr_php = new Solr_php();
$solr_php->solr_host = 'http://localhost:8983/';

//$data = array(); //don't pass any $data parameter if you want to delete all records

//this will delete the record(s) with id = 1
$data = 
array(
		'id' => '1',
		'title' => 'test'
);

//execute the upsert command
$data = $solr_php->delete('techproducts', $data);

//always commit after do the delete function
$solr_php->commit('techproducts'); 

//let's check if the delete command already executed successfully
$data = $solr_php->select('techproducts');

echo $data;

?>