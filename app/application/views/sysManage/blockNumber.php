<form id="addCellNumbers" action="#">
	<table>
    	<tr>
        	<td>Country</td>
            <td><select name="countrySelected">
            	<option value="">--Select--</option>
                <?php if($country != NULL){
						foreach($country as $row){
							echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->fld_chr_acro).'</option>';
						}
					}
				?>
            </select></td>
        	<td><input autocomplete="off" type="number" id="blockNumber" /></td>
            <td><button type="button" id="addNumbers" class="button">Add</button></td>
        </tr>
    </table>
    <div id="blockedNumbersFeild">
    	<h4>Cell Numbers </h4>
    	<ul></ul>
    </div>
    <p><input class="button" id="numberReset" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){
	
	var numbers = [];
	$('#addNumbers').click(function(e) {
		var number = $('#blockNumber').val().replace(/\s/g, '');
		if($('#blockedNumbersFeild ul li').length < 10 ){
			if(number.length != 10 ){
				obj.message_show('Invalid Number Length','error');
			}else{
				if($.inArray(number,numbers) == -1){
					$('#blockedNumbersFeild ul').append('<li><span>'+$('#blockNumber').val()+'</span><i>x</i></li>');
					$('#blockNumber').val('');
					numbers.push(number);
				}else{
					obj.message_show('Number Already Exst','error');
				}
			}
		}
		else{
			obj.message_show('Maximum number of crossed','error');
		}
		
	});
	$('#blockedNumbersFeild ul').on('click','li i',function(e){
		numbers.splice(numbers.indexOf($(this).siblings('span').text()), 1);
		$(this).parent('li').remove();		
	});
	$('#addCellNumbers').submit(function(e) {
		e.preventDefault();
		if(numbers.length > 0 ){
			var res = obj.dhx_ajax('vas/sms/sysManage_c/blockedNumber',$(this).serialize()+'&numbers='+numbers.join('_') );
			if(res==='sucess'){
				obj.message_show('New Blocked Numbers has been added sucessfully');
				//obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/sysManage_c/renderOperator');
				$(this)[0].reset();
			}
			else{
				obj.message_show(res,'error');
			}
			/*$('#blockedNumbersFeild ul li').each(function(index, element) {
				numbers.push($(this).children('span').text().replace(/\s/g, ''));
				
			});*/
		}else{
			obj.message_show('Cell Numbers not found','error');
		}
		
		
	});
	$('#numberReset').click(function(e) {
		$('#blockedNumbersFeild ul').empty(); numbers = [];
	});
}());
</script>
<style>
#addCellNumbers{ padding:10px;}
#addCellNumbers, #addCellNumbers select { font-size:12px;}
#addCellNumbers table{ margin:0 auto;}
#addCellNumbers table tr td { padding:3px 5px;}
#addCellNumbers table tr td input{ padding:4px 6px; font-size:12px;}
#addCellNumbers table tr td select{ padding: 4px 6px;width: 138px;}
#addCellNumbers p{ text-align:right; margin-top:10px;}
#addCellNumbers p input{ margin-left:5px;}
#blockedNumbersFeild{
	border:1px solid #a4bed4;
	padding:10px; margin-top:16px; position:relative; height:150px;
}
#blockedNumbersFeild h4{ position:absolute; top:-8px; left:10px; padding:1px 10px; color:blue; backface-visibility:hidden;
background-color:white;}
#blockedNumbersFeild ul li{ border:1px solid #d1d1d1; padding:5px 15px 5px 10px; color:#971f1f; position:relative;margin:0 5px 5px 0;
float:left; width:150px;}
#blockedNumbersFeild ul li i{ position:absolute; right:5px; top:3px;padding: 0 4px 3px 4px; background-color:#847e7e;
color:white; cursor:pointer;  }
#blockedNumbersFeild ul li i:hover{ background-color:#ca1515;}
</style>


















