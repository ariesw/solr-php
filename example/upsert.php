<?php
/*
	Upsert Example
	Core Name : techproducts
	Available Fields : id, title
*/

require_once("../solr_php.php");

$solr_php = new Solr_php();
$solr_php->solr_host = 'http://localhost:8983/';

$array_post = array();

$data = 
array(
	array(
		'id' => '1',
		'title' => 'John Doe'
	),
	array(
		'id' => '2',
		'title' => 'Yosia Doe'
	),
	array(
		'id' => '3',
		'title' => 'Cindy Doe'
	)
);

//execute the upsert command
$data = $solr_php->upsert('techproducts', $data);

//always commit after do the upsert function
$solr_php->commit('techproducts'); 

//let's check if the upsert command already executed successfully
$data = $solr_php->select('techproducts');

echo $data;

?>