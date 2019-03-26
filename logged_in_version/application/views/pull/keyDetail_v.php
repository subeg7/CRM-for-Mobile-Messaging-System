<div id="pulldetail">
	<div>
    	<table>
        	<tr>
            	<td>Main key</td>
                <td>: <?php echo $mainkey[0]->keys_name;?> </td>
            </tr>
            <tr>
            	<td>Disable Message</td>
                <td>: <?php echo  ($mainkey[0]->disable_message==='none')?'------':$mainkey[0]->disable_message;?></td>
            </tr>
            <tr>
            	<td>Error/Fail Message</td>
                <td>: <?php echo  ($mainkey[0]->fail_message==='none')?'------':$mainkey[0]->fail_message;?></td>
            </tr>
            <tr>
            	<td>Sucess Message</td>
                <td>: <?php echo  ($mainkey[0]->sucess_message==='none')?'------':$mainkey[0]->sucess_message;?></td>
            </tr>
        </table>
       
        <div>
            <h3>Sub Key List</h3>
            <div>
            <table>
            <?php 
				if($subkey !='none'){
					
					foreach($subkey as $row){
						if(strtolower($mainkey[0]->category)=='ptp'){
							echo '<tr><td>'.$row->keys_name.'</td><td>: '.$row->sucess_message.'</td><td><span>Template :</span> '.$row->tname.'</td><td><span>Address Book :</span> '.$row->aname.'</td></tr>';
						}else{
							echo '<tr><td>'.$row->keys_name.'</td><td>: '.$row->sucess_message.'</td></tr>';
						}
					}
				}
				
			?>
            </table>
            </div>
        </div>
   	</div>
</div>

<style>
#pulldetail{ height:100%; width:100%; padding:10px; font-size:12px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
}
#pulldetail > div ,#pulldetail > div > div div { border: 1px solid #a4bed4; padding:10px; width:100%; height:100%;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
}
#pulldetail >div > div div{position:relative; height: 300px; overflow:auto;}
#pulldetail >div > div h3{  color:blue;  margin:15px 0 10px 5px; }
#pulldetail > div  table tr td{ padding:3px 7px;}
#pulldetail > div  table tr td:first-child,#pulldetail > div  table tr td span{ color:#a21616;}


</style>