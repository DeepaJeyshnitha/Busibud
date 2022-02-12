<?php

	require_once('../search_library/search.php');

	require_once('../pagination1.0/prepared_query.php');


	$application_obj = new ManageApp();
	@@ -11,7 +11,7 @@

	$application_obj->Myconnection ($connection_mock_chat,"localhost","root","Mock_test_db");
	$table_heading_name=array('Name','Email','Phone Number','Gender');
	$table_column_name=array('Name','Email','Phone','Gender');
	$where=1;
	if($_POST['request'] == 'data')
	{  
	@@ -23,7 +23,7 @@
	    $max_page=0;
	    $where = 1;
	    $response_data=array();
	    $total_data=$application_obj->total_data($connection_mock_chat,$buffer_range,$data_per_page,$where,$params=null);
	    $response_data['total_length']=$total_data['total_length'];
	    $response_data['total_data']=$total_data['total_data'];
	    $response_data['max_page']=$total_data['max_page'];
	@@ -44,8 +44,9 @@
	    $response_data=array();
	    $obj=new searching($input,$connection_mock_chat);
	    $keys=array('type','table_name','search_col_name','get_colms','get_id');
	    //$value=array(array('string','login_db.mock_test_tbl','name','null as name,id,null as email,null as phone,null as gender','id'));
	    $value=array(array('email','Mock_test_db.mock_test_tbl','Email','Name as name,Id,Email as email,Phone as phone,Gender as gender','id'));
		$query_data=array();

	    foreach ($value as $key => $value1) 
	    {
	@@ -58,16 +59,19 @@

	    if($get_query_and_data['query']!='')
	    {  
	        //$result=mysqli_prepared_query($connection_mock_chat,$get_query_and_data['query'],"",$params);
		    $params = array("%".$input."%","%".$input."%");
	        $result = mysqli_prepared_query($connection_mock_chat,$get_query_and_data['query'],"ss",$params); 
	    }

	    $get_ids=$obj->get_ids($result,$get_query_and_data['string'],$get_query_and_data['get_ids']);

	    $where_data=$obj->searching_data($get_ids);

	    $table_from=array("table_name_id","table_name_email");
	    //$table1_to=array("login_db.mock_test_tbl","login_db.mock_test_tbl");
	    $table1_to=array("Mock_test_db.mock_test_tbl","Mock_test_db.mock_test_tbl");
		$tble1=str_replace($table_from, $table1_to, $where_data);

	    if($tble1=='')
	    {
	@@ -76,9 +80,9 @@
	    }
	    else
	    {
	        $where='Email LIKE ? OR Name LIKE ?';

	        $total_data=$application_obj->total_data($connection_mock_chat,$buffer_range,$data_per_page,$where,$params);
	        $response_data['total_length']=$total_data['total_length'];
	        $response_data['total_data']=$total_data['total_data'];
	        $response_data['max_page']=$total_data['max_page'];
	@@ -97,15 +101,15 @@ function MyConnection (&$connection,$host,$user,$db)

	        $user='root';

			$connection= mysqli_connect ($host, $user, "" , $db); 
			if (!$connection) 
			{
				die ( "no connection found" . mysqli_error($connection));
			}

		}

		function total_data($connection_mock_chat,$buffer_range,$data_per_page,$where,$params)
		{
		    $response_array=array();
		    $max_page=1;
	@@ -119,7 +123,7 @@ function total_data($connection_mock_chat,$buffer_range,$data_per_page,$where)
		        $data_from=$page_from*$data_per_page-$data_per_page;
		        $data_to=$page_to-$page_from;
		        $data_to=($data_to*$data_per_page)+$data_per_page;
		        $data=$this->get_data($connection_mock_chat,$data_from,$data_to,$data_per_page,$where,$params);
		        if(!empty($data))
		        {
		            $max_page=$data[0]['max_page'];
	@@ -171,41 +175,53 @@ function total_data($connection_mock_chat,$buffer_range,$data_per_page,$where)
		}  


		function get_data($connection_mock_chat,$data_from,$data_to,$data_per_page,$where,$params)
		{   
		    $response=array();
		    if(empty($params)){
		    	$params= array();
		    	$params2 = array($data_from,$data_to);
		    	$types1 = "";
		    	$types2 = "ii";
		    }else{
		    	$params= $params;
		    	$params2 = array($params[0],$params[1],$data_from,$data_to);
		    	$types1 = "ss";
		    	$types2 = "ssii";

		    }
		    $max_page='';
		   $query="SELECT COUNT(*) as total_row FROM mock_test_tbl WHERE ".$where;
	        $total_row= mysqli_prepared_query($connection_mock_chat,$query,$types1,$params);
	        $total_length=$total_row[0]['total_row'];
	        $max_page=ceil($total_length/$data_per_page);

		    $query="SELECT * FROM mock_test_tbl WHERE ".$where."  LIMIT ?,?";

		    $params = array($data_from,$data_to);

        	$extra_slots_entry= mysqli_prepared_query($connection_mock_chat,$query,$types2,$params2);
		        if($extra_slots_entry && !empty($extra_slots_entry))
		        {
		            foreach ($extra_slots_entry as $val)
		            {
		                $res_here=$val;
		                $res_here['max_page']=$max_page;
		                $res_here['total_length'] =$total_length;
		                $Name=$val['Name'];
		                $Email=$val['Email'];
		                $phoneNum=$val['Phone'];
		                $Gender=$val['Gender'];

		                $res_here['Name']=$Name;
		                $res_here['Email']=$Email;
		                $res_here['Phone']=$phoneNum;
		                $res_here['Gender']=$Gender;
		                $response[]=$res_here;   
		            }
		        } 
		        return $response;
		}

	}
?>
