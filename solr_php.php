<?php
/*
	Solr PHP Class 
	Tested in Solr 5.5
	
	Description : Solr PHP Class to do the basic function of Database
	Author : Yosia 
	Company : SOCIANOVATION - Be Social, Be Innovative
*/

header('Content-Type: application/json');

class Solr_php
{
	public $solr_host = "";
	
	/*
	* Parameter : 
	* - String, Desired Core Name
	* - Array, format : array('<column name>', '<value>'), example : array('id' => '123123') 
	* Return : Json
	*/
	public function select($core_name, $data = NULL)
	{
		$url = '';
		
		if($data == NULL)
		{
			//This will select all data without any condition
			$url = $this->solr_host.'solr/'.$core_name.'/select?q=*:*&wt=json&indent=true&rows=2147483647';
		}
		else
		{
			//support AND operation
			$conditions = '';
			foreach($data as $d => $v)
			{
				if($conditions == '')
				{
					$conditions .= $d.':'.$v;
				}
				else{
					$conditions .= ' AND '.$d.':'.$v;
				}
				
				$url = $this->solr_host.'solr/'.$core_name.'/select?q='.urlencode($conditions).'&wt=json&indent=true&rows=2147483647';
			}
			
		}
		
		$result = $this->curl_request($url);
		
		return $result;
	}
	
	/*
	* Update / Insert Function
	* Duplicate Indexed Data will be updated
	*
	* Parameter : 
	* - String, Desired Core Name
	* - Multiple Array, format : array(array('<column name1>', '<value1>'), array('<column name2>', '<value2>')), example : array(array('id' => '1231234') , array('id' => '1231235') ) 
	* Return : Json
	*/
	public function upsert($core_name, $data = NULL)
	{
		$url = $this->solr_host.'solr/'.$core_name.'/update/json';
		$data = json_encode($data);
		//$data = "[".$data."]";
		$result = $this->curl_request($url, $data);
		
		return $result;
	}
	
	/*
	* Delete Function
	*
	* Parameter : 
	* - String, Desired Core Name
	* - Array, format : array('<column name>', '<value>'), example : array('id' => '123123') 
	* Return : Json
	*/
	public function delete($core_name, $data = NULL)
	{
		$url = '';
		
		if($data == NULL)
		{
			//This will delete all data, make sure you set the $data parameter
			$url = $this->solr_host.'solr/'.$core_name.'/update?stream.body=<delete><query>*:*</query></delete>';
		}
		else
		{
			//support AND operation
			$conditions = '';
			foreach($data as $d => $v)
			{
				if($conditions == '')
				{
					$conditions .= $d.':'.$v;
				}
				else{
					$conditions .= ' AND '.$d.':'.$v;
				}
				
				$url = $this->solr_host.'solr/'.$core_name.'/update?stream.body=<delete><query>'.urlencode($conditions).'</query></delete>';
			}
		}
		
		
		
		$result = $this->curl_request($url);
		
		return $result;
	}
	
	/*
	* Commit Function
	* I set the Upsert and Delete request Commit to false, this will be the best practice to optimize the query performance
	* Make sure you run this function after do the 'upsert' or 'delete' function
	*
	* Parameter :
	* - String, Desired Core Name
	*/
	public function commit($core_name)
	{
		$url = $this->solr_host.'solr/'.$core_name.'/update?stream.body=<commit/>';
		
		$result = $this->curl_request($url);
	}
	
	/*
	* Some Helpers to do the CURL Request
	* This function already cover all requirements to do the Solr HTTP Request
	*/
	public function curl_request($url, $body = NULL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST,  1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/json')); 
		
		$output = curl_exec($ch);
		
		curl_close($ch);	
		
		return $output;
	}
}