<form id="editPrefix" action="#">
	<table>
    	<tr>
        	<td>Operator Prefix :</td>
            <td><input disabled class="editDisabled" autocomplete="off" name="prefix" type="text" data-val="<?php echo $prefix[0]['prefix']; ?>"  value="<?php echo $prefix[0]['prefix']; ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Operator :</td>
            <td><select name="operator" class="editDisabled" disabled data-val="<?php echo $prefix[0]['operator_id']; ?>"  >
            	<option value="">--Select--</option>
                <?php
					foreach($operator as $row){
						if($row->fld_int_id ==$prefix[0]['operator_id'] )
							echo '<option selected value="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</option>';
						else
							echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</option>';
					}
				?>
            </select><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#editPrefix').submit(function(e) {
    e.preventDefault();

	var id = obj.getSelected('dhxDynFeild');
	console.log(id);
	if(id==null) return;
	if($('select[name="operator"]').val() === ''){ obj.message_show('The Operator feild is required','error'); return}

	var res = obj.dhx_ajax('sysManage_c/prefix/edit/'+id,$(this).serialize() );
	if(res==='sucess'){

		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('prefix').close();
	}
	else{
		obj.message_show(res,'error');
	}

});
$('.edit').on('click',function(e){
	$(this).siblings('input').removeAttr('disabled').focus();
	$(this).siblings('select').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){

	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	}
});
</script>
<style>
#editPrefix, #editPrefix select { font-size:12px;}
#editPrefix table{ margin:0 auto; padding-top:15px;}
#editPrefix table tr td { padding:3px 5px;}
#editPrefix table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px;}
#editPrefix table tr td select{ padding: 4px 6px;width: 138px; margin-right:10px;}
#editPrefix p{ text-align:right; padding-right:24px; margin-top:10px;}
#editPrefix p input{ margin-left:5px;}
</style>
