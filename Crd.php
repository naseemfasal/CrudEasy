<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class crd extends CI_Model
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*
    -----------JOIN ARRAY FORMAT-------------
	$join[0] = array(
				'table_1' => 'tb_category',
				'column_1' => 'category_id',
				'as' => '',
				'table_2' => 'tb_listing',
				'column_2' => 'listing_category_id',
					'type' => 'INNER'
		);
				
				$join[1] = array(
				'table_1' => 'tb_users',
				'column_1' => 'user_id',
				'as' => '',
				'table_2' => 'tb_listing',
				'column_2' => 'fk_user_id',
					'type' => 'INNER'
		);

	-----------ORDER BY ARRAY FORMAT-----------


	 $order_by[0] = array(
		'table' =>'tb_1',
		'column' => 'column'
	);

	
    */
		public function meta_inc($key,$inc_value='1'){
				if($key!=''){
					$this->db->where('meta_key',$key);
					$this->db->set('meta_value', 'meta_value+ 1', FALSE);
					$this->db->update('tb_settings');				
				}
		}
		public function add_serial_number()
    {
        $this->db->query('INSERT INTO tb_customer_serial (ser_num,serial_number) VALUES (1000,1) ON DUPLICATE KEY UPDATE ser_num=ser_num+1');
				return array(
        		'status'=>TRUE,
        		'insert_id' => $this->db->insert_id()
        	);
    }
	
		public function get_meta_value_by_key($key)
    {
				$where=array('meta_key'=>$key);
   			$data=$this->sql->select('*','tb_settings',$where);
				if($data){
					$result=$data->result_array();
					return $result[0]['meta_value'];
				}
				else{
					
					return 'INVALID_KEY';
				}
				
				
    }	
	
    public function select($columns = '*', $from_table = '', $where = array(), $join_array = array(), $per_page = '0', $page_num = '0', $order_by = array())
    {
        $this->db->select($columns);
        
        if ($from_table == '') {
            return array(
                'status' => 'FROM CANT BE NULL'
            );
        } else {
            $this->db->from($from_table);
        }

        if (!empty($where)) {
            $this->db->where($where);
            
        }
        
        if (!empty($join_array)) {
            foreach ($join_array as $data) {
                if ($data['as'] == '') {
                    $this->db->join($data['table_1'], $data['table_1'] . '.' . $data['column_1'] . '=' . $data['table_2'] . '.' . $data['column_2'], $data['type']);
                } else {
                    $this->db->join($data['table_1'] . ' as ' . $data['as'], $data['table_1'] . '.' . $data['column_1'] . '=' . $data['table_2'] . '.' . $data['column_2'], $data['type']);
                }
                
            }
        }
        
        
        if ($page_num != '0' || $per_page != '0') {
            $this->db->limit($per_page, $page_num);
        }
        
        if (!empty($order_by)) {
            
            foreach ($order_by as $data) {
                $this->db->order_by($data['table'] . '.' . $data['column'], $data['direction']);
            }
        }
        $result         = $this->db->get();
        $result->status = 'EXECUTION COMPLETED';
        return $result;
    }


    public function insert($table,$data,$default_inserting=NULL)
    {
			
				$data_formatted=$this->array_gen($data,$default_inserting);
        if($this->db->insert($table, $data_formatted)){
        	return array(
        		'status'=>TRUE,
        		'insert_id' => $this->db->insert_id()
        	);
        }
        else{
        	return array(
        		'status'=>FALSE
        	);
        }
        
    }
    
    public function update($table,$data,$where,$default_inserting=NULL)
    {
			
			$data_formatted=$this->array_gen($data,$default_inserting);
    	if(!empty($where)){
        	$this->db->where($where);  		
    	}
    	else{
    		return array(
        		'status'=>FALSE
        	);
    	}

        if ($this->db->update($table, $data_formatted)) {
            return array(
        		'status'=>TRUE
        	);
        } else {
            return array(
        		'status'=>FALSE
        	);
        }
    }

    public function delete($table,$where)
    {
    	if(!empty($where)){
    		$this->db->where($where);
    	}
    	else{
    		return FALSE;
    	}

    	if($this->db->delete($table)){
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	}
    }
	
	
	/* 
	Usage 1:
	
	Single dimension array : here array_key and value will be same.
	array('key_value1','key_value2)
	
	
	
	Usage 2:	
	Two dimension array :  for seperate array_key and value
		array(
		 'array_key1'=>'post:input_name',
		 'array_key1'=>'get:input_name',
		'array_key1'=>'input_name' ,  // array key = inut post name				
		'array_key2'=>'date',  // return date as value
		'array_key3'=>'datetime',  // return datetime as value 
		'array_key4'=>'value:: hi this is message'  // this method can be used to pass custome text value to the key
		'array_key5'=> 'mysqldate::dateofbirth'  // this is will convert posted dateofbirth to mysql date
		'array_key5'=> 'mysqldatetime:: dateofbirth time'  // this is will convert posted dateofbirth time to mysql date time
		)


	*/
	
		function array_gen($array_items=array(),$default=NULL){
			
			$output=array();
			// if input and array items are empty
			if(empty($array_items)){
				
				
				$output=$this->input->post();

			}
			else{
							if(array_key_exists('0',$array_items)){
									foreach($array_items as $item){
											$output[$item]=($this->input->post($item)!='')? $this->input->post($item) : $default;
									}																	
							}
							else{
									foreach($array_items as $key => $value){
										
									if($value=='datetime'){
											$output[$key]=date("Y-m-d H:i:s");
									}
									elseif($value=='date'){
											$output[$key]=date("Y-m-d");
									}
									else{

											if(strpos($value,':') !== false) {
												$splitted=explode(":",$value);  
												
														if($splitted[0]=='value'){
															$output[$key]=$splitted[1];
														}
														elseif($splitted[0]=='mysqldate'){
															$date = date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post($splitted[1]))));

														}
														elseif($splitted[0]=='mysqldatetime'){
															$date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $this->input->post($splitted[1]))));
														}											
														elseif($splitted[0]=='post'){
															$date = $output[$key]=$this->input->post($splitted[1]);
														}		
														elseif($splitted[0]=='get'){
															$date = $output[$key]=$this->input->get($splitted[1]);
														}		
																								
											}
											
											else{
												$output[$key]=($value!='')? $value : $default;

											}


									}



									}																	

						

				}

			}

			return $output;
		}
    
}

/* End of file Sql.php */
/* Location: ./application/models/Sql.php */
