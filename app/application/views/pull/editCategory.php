<form id="editCategory" action="#">
	<table>
    	<tr>
        	<td>Category :</td>
            <td><input autocomplete="off" disabled name="category" class="editDisabled" data-val="<?php echo ucwords($category[0]['category']); ?>" type="text" value="<?php echo ucwords($category[0]['category']); ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><textarea rows="5" cols="18" autocomplete="off" disabled name="description" class="editDisabled" data-val="<?php echo strtoupper($category[0]['description']); ?>" type="text" ><?php echo strtoupper($category[0]['description']); ?></textarea><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
/************ edit input selection function*****************/
$('.edit').on('click',function(e){
	if($(this).siblings().is('input')) $(this).siblings('input').removeAttr('disabled').focus();
	else if($(this).siblings().is('textarea')) $(this).siblings('textarea').removeAttr('disabled').focus();
	
});
$('.editDisabled').on('focusout',function(e){
	if( $(this).val().toString().toLowerCase() == $(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	} 
});
$('#editCategory').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	var res = obj.dhx_ajax('vas/sms/pull_c/category/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('category').close();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#editCategory, #editCategory textarea { font-size:12px;}
#editCategory table{ margin:0 auto; padding-top:15px;}
#editCategory table tr td { padding:3px 5px;}
#editCategory table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px; width:148px;}
#editCategory p{ text-align:right; padding-right:24px; margin-top:10px;}
#editCategory p input{ margin-left:5px;}
</style>