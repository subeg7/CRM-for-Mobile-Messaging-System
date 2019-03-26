<form id="addPrefix" action="#">
	<table>
    	<tr>
        	<td>Operator Prefix :</td>
            <td><input autocomplete="off" name="prefix" type="text" /></td>
        </tr>
        <tr>
        	<td>Operator :</td>
            <td><select autocomplete="off" name="operator" >
            	<option value="">--Select--</option>
                <?php
					foreach($operator as $row){
						echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</option>';
					}
				?>
            </select></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addPrefix').submit(function(e) {
    e.preventDefault();

	var res = obj.dhx_ajax('sysManage_c/prefix/new',$(this).serialize() );
	if(res==='sucess'){

		obj.message_show('New Operator Prefix has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('sysManage_c/renderPrefix');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#addPrefix ,#addPrefix select{ font-size:12px;}
#addPrefix table{ margin:0 auto; padding-top:15px;}
#addPrefix table tr td { padding:3px 5px;}
#addPrefix table tr td input{ padding:4px 6px; font-size:12px;}
#addPrefix table tr td select{ padding: 4px 6px;width: 138px;}
#addPrefix p{ text-align:right; padding-right:24px; margin-top:10px;}
#addPrefix p input{ margin-left:5px;}
</style>
