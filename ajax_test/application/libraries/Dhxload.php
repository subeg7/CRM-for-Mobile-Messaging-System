<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dhxload
{
	public $data = NULL;
	public $rows  = NULL;
	public $xmlData =NULL;
	public function __get($var){
		return get_instance()->$var;
	}
	public function get_value($name){
		return (isset($this->data->$name))?$this->data->$name:FALSE;
	}
	public function set_value($name, $value){

		$this->data->$name = $value;

	}

	// public function 

		
	public function getXml(){
		$row = explode(',',$this->rows);
		$rowXml = '';
		for($i=0; $i < sizeof($row);$i++){
			if($i==0){
				$var = $row[$i];
				$rowXml .="<row id='".$this->data->$var."'>";
			}
			else{
				$var = $row[$i];
				$rowXml .="<cell>".$this->data->$var."</cell>";
			}
		}
		 $rowXml.='</row>';
		return $rowXml;
	}


	public function getCsv($csvRowId=0){
		
		$row = explode(',',$this->rows);
		$rowCsv = "".$csvRowId.",";
		for($i=0; $i < sizeof($row);$i++){
			if($i>0){
				$var = $row[$i];
				if($var=="fld_int_ondate"){
					//convert milliseconds to seconds then to date
					$this->data->$var=date('d F, Y h:i A',$this->data->$var);
				}
				// echo"     ".$this->data->$var;
				$rowCsv .= '"'.(string) strip_tags($this->data->$var).'"'.",";
			}
		}
		// exit();
		$ret = trim($rowCsv,',')."\r\n";
		return $ret;
	}
	/*This function loads the dhtmlx grid dynamically , by this function very heavy data loading in dhtmlx is possible
	*/
	public function dhxDynamicLoad($data,$display=true){
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
		

		if($display ){
			header("Content-type:text/xml");
			// echo htmlspecialchars($xmlData);
			
			echo $xmlData;
		}else{
			echo htmlspecialchars($xmlData);
			$this->xmlData = $xmlData;
		}
	}


	// public function getFileName($type){
	// 	$fileName = $id."_".$company."_".$type."_".$time;
	// 	return "awsome file name";
	// }


	public function getCsvData($data){
		$rowCount=0;
		// echo"query recieved is:<br>".$data['query'];
		$query = $this->db->query($data['query']);
		// echo"<br><br>";
		// print_r($query->result());
		// exit("terminated");
		$this->rows = $data['rows'];
		$csvData = $data['csvHeader'];
		$csvData .= $data['prinRowsName']."\r\n";
		foreach ($query->result() as $row){
			$rowCount++;
			// echo"<br><br>row:=>";
			// print_r($row);
			$this->data = $row;
			if(isset($data['callback'])){
				call_user_func(array($data['callback'][0],$data['callback'][1]),$this);
			}
			// echo"<br><br>".$this->getCsv();
			$csvData .= $this->getCsv($rowCount);
		}
		$csvData.=$data['csvFooter_1'].$rowCount.$data['csvFooter_2'];
		// echo $csvData;
		// exit();
		return $csvData;


	}
	/**end of class***/
}