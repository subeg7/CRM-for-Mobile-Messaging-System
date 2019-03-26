<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Curd_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		//include_once ("assets/connector/grid_connector.php");
	}
	public function get_search($sql, $type='array'){
		$query = $this->db->query($sql);
		$res = NULL;
		if($query->num_rows() > 0){
			if($type=='array'){   $res = $query->result_array();}
			elseif($type=='object'){ $res = $query->result();	}
		}
		$query->free_result();
		return $res;
	}
	public function get_insert($table,$data, $type='normal'){
		if($type=='normal'){
			$this->db->insert($table, $data);
			return ($this->db->affected_rows() >0)? $this->db->insert_id(): FALSE;
		}
		elseif($type=='batch'){
			$this->db->insert_batch($table, $data);
			return $this->db->affected_rows() == sizeof($data);
		}

	}
	// always first element in array taken as where clause
	public function get_update($table,$data, $type='normal'){
		if($type=='normal'){
			$keys = array_keys($data);
			$this->db->where($keys[0], $data[$keys[0]]);
			$this->db->update($table, $data);
			return $this->db->affected_rows() >0;
		}
		elseif($type=='batch'){
			$keys = array_keys($data[0]);
			$this->db->update_batch($table, $data, $keys[0]);
			return $this->db->affected_rows() > 0;
		}

	}
	/* this function take first argument as table name and secound associative array where its value is form of array
		@return true if sucess and false if error
		@pram: $table takes table name to delete data
		@pram: $data take array i.e array('fld_int_id'=>array(id1,id2))
	*/
	public function get_delete($table,$data){
		$keys = array_keys($data);
		$arr = array();
		if(sizeof($data[$keys[0]]) > 1){
			$arr = array_chunk($data[$keys[0]],10);
			foreach($arr as $val){
				$query = $this->db->query('DELETE FROM '.$table.' WHERE '.$keys[0].' IN ('.implode(",",$val).')');
				if($this->db->affected_rows() != sizeof($val)) return '** Error in deletion';
			}
			return TRUE;
		}
		else{
			$this->db->where($keys[0],$data[$keys[0]][0]);
			$this->db->delete($table);
			return $this->db->affected_rows() >0;
		}
	}
	// check individual recurrance and multi condition query
	public function checkRecur($table,$data,$type="individual"){
		if($type == "individual"){
			$arr = array();
			foreach($data as $key=>$val){
				$key = explode('__',$key);
				$res = $this->get_search('SELECT '.strtolower($key[0]).' FROM '.$table.' WHERE '.strtolower($key[0]).' = "'.strtolower($val).'"');
				if($res !== NULL) $arr[] = '<p>'. $key[1] .' already Exist</p>';
			}
			return (sizeof($arr) == 0) ? TRUE : implode(' ',$arr) ;
		}
		elseif($type=="multiple"){
			$query = 'SELECT * FROM '.$table.' WHERE ';
			foreach($data as $key=>$val){
				$query.=strtolower($key).' = "'.strtolower($val).'" AND ';
			}
			$query = $this->get_search( trim($query,'AND '));
			return ($query !== NULL)? 'already Exist': TRUE;

		}
	}
	public function getData($from,$id=NULL,$type='array'){
		if($id==NULL){
			$res = $this->get_search('SELECT * FROM '.$from,$type);
		}
		elseif(is_array($id)){
			$query = 'SELECT * FROM '.$from.' WHERE ';
			foreach($id as $key=>$val){ $query .= $key.' = '.$val.' AND '; }
			$res = $this->get_search( trim($query,'AND '),$type);
		}
		else{
			if($from=='groups') $id_field = 'id';
			else $id_field = 'fld_int_id';
			$res = $this->get_search('SELECT * FROM '.$from.' WHERE '.$id_field.'='.$id,$type);
		}
		return ($res!=NULL)?$res: FALSE;
	}
	function undoEntry($dataArr){
		if(!is_array($dataArr)) return FALSE;
		foreach($dataArr as $key=>$val){
			$val = explode(' ', $val);
			$this->get_delete($key,array($val[0]=>array($val[1]) ));
		}
		return TRUE;
	}
// model ends
}
