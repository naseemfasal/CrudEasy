# CrudEasy for codigniter framework
CrudEasy is a model function for codeigniter to do CRUD operations easily. This module help you to avoid writing lot of models and help you to save time with some automated formatting and time insert features.


Usage : -

1. Upload Crd.php file to models folder in your codeigniter application.
2. Load the model "Crd" in your Controller or auto load the function in config > autoload.php.

Example : $this->load->model('Crd'); or in autoload $autoload['model'] = array('Crd');

3. Start working without writing models !! 

Examples :-

###### Select query 
```
            // join 1 
						$join[0] = array(
						'table_1' => 'joining_table',
						'column_1' => 'column_name',
						'as' => '',
						'table_2' => 'table2',
						'column_2' => 'column_name',
						'type' => 'LEFT'   
						);		
            // incase of second join
						$join[1] = array(
						'table_1' => 'joining_table',
						'column_1' => 'column_name',
						'as' => '',
						'table_2' => 'table2',
						'column_2' => 'column_name',
						'type' => 'LEFT'   
						);									
            $where=array();
            $result = $this->sql->select('*','table_name',$where,$join)->result_array();
						
            
   						
						$store_data =  array(
            'column_name1'=>'post:input_name1_from_form', // set the form input name here like the eg for each input
            'column_name2'=>'get:input_name2_from_form',  // set the form input name here like the eg for each input   
            'column_name3'=>'post:input_name3_from_form',  // set the form input name here like the eg for each input
            'date_of_posting'=>'mysqldate:input_date_name4_from_form',   
            'datetime_of_posting'=>'mysqldatetime:input_datetime_name4_from_form',
            'date'=>'date',  // auto insert current server date 
            'datetime'=>'datetime',  // auto insert current server date and time
            'column_status'=>'value:: hi this is custome message to insert'    // for setting custome value 
						);

						$response = $this->sql->insert('table_name',$data);	         
```
on success you will get response on $response variable as follows :-
```
      array(
      'insert_id'=>'1'  // insert id of the data 
      )
```      
    so that you will get response on the array  as $response['insert_id].
 
 ###### Update Query
   
 ```   
      $data = array(
        'column_1' => 'value,
				'column2'=>$'value2'
			);
			$where = array('id'=>'2234');
		  $this->sql->update('table_name',$data,$where);
   ```
   
   
  ###### Delete Query    
      
 		 $where = array('column1' => '1'); 
		 $flag = $this->sql->delete('table_name', $where);     
       
       
