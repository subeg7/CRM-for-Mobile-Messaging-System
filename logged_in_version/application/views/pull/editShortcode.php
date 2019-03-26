<form id="editShortcode" action="#">
	<table>
    	<tr>
        	<td>Shortcode :</td>
            <td><input autocomplete="off" disabled name="shortcode" class="editDisabled" data-val="<?php echo ucwords($shortcode[0]['fld_chr_name']); ?>" type="text" value="<?php echo ucwords($shortcode[0]['fld_chr_name']); ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><textarea rows="5" cols="18" autocomplete="off" disabled name="description" class="editDisabled" data-val="<?php echo strtoupper($shortcode[0]['fld_chr_description']); ?>" type="text" ><?php echo strtoupper($shortcode[0]['fld_chr_description']); ?></textarea><span class="edit">Edit..</span></td>
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
	console.log($(this).data('val').toString().toLowerCase());
	if( $(this).val().toString().toLowerCase() == $(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	}
});
$('#editShortcode').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	var res = obj.dhx_ajax('pull_c/shortcode/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('shortcode').close();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#editShortcode, #editShortcode textarea { font-size:12px;}
#editShortcode table{ margin:0 auto; padding-top:15px;}
#editShortcode table tr td { padding:3px 5px;}
#editShortcode table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px; width:148px;}
#editShortcode p{ text-align:right; padding-right:24px; margin-top:10px;}
#editShortcode p input{ margin-left:5px;}
</style>
