<form id="addOperator" action="#">
	<table>
    	<tr>
        	<td>Operator Acronym :</td>
            <td><input autocomplete="off" name="acronym" type="text" /></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><input autocomplete="off" name="description" type="text" /></td>
        </tr>
        <tr>
        	<td>Country:</td>
            <td><select autocomplete="off" name="country" >
            	<option value="">--Select--</option>
                <?php //var_dump($country );
					foreach($country as $row){
						echo '<option value="'.$row->fld_int_id.'">'.ucwords($row->fld_chr_name).'</option>';
					}
				?>
              
            </select></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addOperator').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('vas/sms/sysManage_c/operator/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Operator has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/sysManage_c/renderOperator');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#addOperator, #addOperator select { font-size:12px;}
#addOperator table{ margin:0 auto; padding-top:15px;}
#addOperator table tr td { padding:3px 5px;}
#addOperator table tr td input{ padding:4px 6px; font-size:12px;}
#addOperator table tr td select{ padding: 4px 6px;width: 138px;}
#addOperator p{ text-align:right; padding-right:15px; margin-top:10px;}
#addOperator p input{ margin-left:5px;}
</style>