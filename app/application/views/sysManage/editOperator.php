<form id="editOperator" action="#">
	<table>
    	<tr>
        	<td>Operator Acronym:</td>
            <td><input autocomplete="off" disabled name="acronym" class="editDisabled" data-val="<?php echo strtoupper($operator[0]['acronym']); ?>" type="text" value="<?php echo strtoupper($operator[0]['acronym']); ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><input autocomplete="off" disabled name="description" class="editDisabled" data-val="<?php echo ucwords($operator[0]['description']); ?>" value="<?php echo strtoupper($operator[0]['description']); ?>" type="text" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Country:</td>
            <td><select  class="editDisabled" disabled name="country" data-val="<?php echo $operator[0]['country_id']; ?>" >
            	<option value="">--Select--</option>
                <?php 
					foreach($country as $row){
						if($row->fld_int_id == $operator[0]['country_id'])
							echo '<option selected value="'.$row->fld_int_id.'">'.ucwords($row->fld_chr_name).'</option>';
						else
							echo '<option value="'.$row->fld_int_id.'">'.ucwords($row->fld_chr_name).'</option>';
					}
				?>
              
            </select><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
/************ edit input selection function*****************/
$('.edit').on('click',function(e){
	$(this).siblings('input').removeAttr('disabled').focus();
	$(this).siblings('select').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){
	
	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	} 
});
$('#editOperator').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	if($('select[name="country"]').val() === ''){ obj.message_show('The Country feild is required','error'); return}
	var res = obj.dhx_ajax('vas/sms/sysManage_c/operator/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('operator').close();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#editOperator , #editOperator select { font-size:12px;}
#editOperator table{ margin:0 auto; padding-top:15px;}
#editOperator table tr td { padding:3px 5px;}
#editOperator table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px;}
#editOperator table tr td select{ padding: 4px 6px;width: 138px;  margin-right:10px;}
#editOperator p{ text-align:right; padding-right:15px; margin-top:10px;}
#editOperator p input{ margin-left:5px;}
</style>