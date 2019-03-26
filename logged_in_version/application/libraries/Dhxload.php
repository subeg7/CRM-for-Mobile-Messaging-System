<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dhxload
{
	public $data = NULL;
	public $rows  = NULL;

	public function __get($var){
		return get_instance()->$var;
	}

	public function get_value($name){
		return (isset($this->data->$name))?$this->data->$name:FALSE;
	}
	public function set_value($name, $value){
		$this->data->$name = $value;
	}
	public function getXml(){
		$row = explode(',',$this->rows);
		$rowXml = '';
		for($i=0; $i < sizeof($row);$i++){
			if($i==0){
				$rowXml .="<row id='".(string) htmlspecialchars($this->data->$row[$i])."'>";
			}
			else{
				$rowXml .="<cell>".(string) htmlspecialchars($this->data->$row[$i])."</cell>";
			}
		}
		return $rowXml.'</row>';
	}
	public function getCsv(){

		$row = explode(',',$this->rows);
		$rowXml = '';
		for($i=0; $i < sizeof($row);$i++){
			if($i>0){
				$rowXml .=(string) strip_tags($this->data->$row[$i]).",";
			}
		}
		return trim($rowXml,',')."\r\n";
	}
	/*This function loads the dhtmlx grid dynamically , by this function very heavy data loading in dhtmlx is possible
	*/
	public function dhxDynamicLoad($data){

		$xmlData = "<?xml version='1.0' encoding='utf-8' ?>";
		//define variables from incoming values
		$posStart = $data['posStart'];
		$count 	  = $data['count'];
		//if this is the first query - get total number of records in the query result

		$arr = explode(" ",$data['query']);
		$frm_tab = FALSE;

		for($i=0; $i < sizeof($arr); $i++){
			if($arr[$i]=='SELECT') $init_query = $arr[$i].' count(*) as cnt ';
			else if($arr[$i]=='FROM' || $frm_tab == TRUE){
				$init_query = $init_query.' '.$arr[$i];
				$frm_tab = TRUE;
			}
		}

		//die($init_query);
		$query = $this->db->query($init_query );
		$row = $query->row();
		//$totalCount = $row->cnt;
		$totalCount = (in_array('GROUP',$arr) || in_array('DISTINCT',$arr)  )?$query->num_rows():$row->cnt;
		$xmlData = $xmlData."<rows total_count='".$totalCount."' pos='".$posStart."'>";
		$query->free_result();
		//add limits to query to get only rows necessary for the output
		if($count > 0) $data['query'].= " LIMIT ".$posStart.",".$count;

		//query database to retrieve necessary block of data
		$query = $this->db->query($data['query']);

		$this->rows = $data['rows'];
		if(isset($data['userdata'])){
			$xmlData .= '<userdata name="query">'.$data['userdata'].'</userdata>';
		}
		foreach ($query->result() as $row){
			$this->data = $row;
			if(isset($data['callback'])){
				call_user_func(array($data['callback'][0],$data['callback'][1]),$this);
			}
			$xmlData .= $this->getXml();
		}
		$xmlData .= "</rows>";

		header("Content-type:text/xml");
		echo $xmlData;
	}

	public function getCsvData($data){
		$query = $this->db->query($data['query']);
		$this->rows = $data['rows'];
		$csvData = $data['prinRowsName']."\r\n";
		foreach ($query->result() as $row){
			$this->data = $row;
			if(isset($data['callback'])){
				call_user_func(array($data['callback'][0],$data['callback'][1]),$this);
			}
			$csvData .= $this->getCsv();
		}
		return $csvData;
	}

	/**end of class***/
}
