<form id="editFeature" action="#">
	<table>
    	<tr>
        	<td>Feature :</td>
            <td><input autocomplete="off" disabled name="feature" class="editDisabled" data-val="<?php echo $feature[0]['fld_chr_feature']; ?>" type="text" value="<?php echo $feature[0]['fld_chr_feature']; ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><input autocomplete="off" disabled name="description" class="editDisabled" data-val="<?php echo $feature[0]['fld_chr_desc']; ?>" value="<?php echo $feature[0]['fld_chr_desc']; ?>" type="text" /><span class="edit">Edit..</span></td>
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
$('#editFeature').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	var res = obj.dhx_ajax('vas/sms/sysManage_c/feature/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('feature').close();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#editFeature , #editFeature select { font-size:12px;}
#editFeature table{ margin:0 auto; padding-top:15px;}
#editFeature table tr td { padding:3px 5px;}
#editFeature table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px;}
#editFeature table tr td select{ padding: 4px 6px;width: 138px;  margin-right:10px;}
#editFeature p{ text-align:right; padding-right:15px; margin-top:10px;}
#editFeature p input{ margin-left:5px;}
</style>