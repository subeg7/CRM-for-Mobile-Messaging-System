<form id="editCountry" action="#">
	<table>
    	<tr>
        	<td>Country Name :</td>
            <td><input autocomplete="off" disabled name="name" class="editDisabled" data-val="<?php echo ucwords($country[0]['fld_chr_name']); ?>" type="text" value="<?php echo ucwords($country[0]['fld_chr_name']); ?>" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Acronym :</td>
            <td><input autocomplete="off" disabled name="acronym" class="editDisabled" data-val="<?php echo strtoupper($country[0]['fld_chr_acro']); ?>" value="<?php echo strtoupper($country[0]['fld_chr_acro']); ?>" type="text" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Country Code :</td>
            <td><input autocomplete="off" disabled name="code" class="editDisabled" data-val="<?php echo ucwords($country[0]['fld_chr_code']); ?>" value="<?php echo $country[0]['fld_chr_code']; ?>" type="text" /><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
/************ edit input selection function*****************/
$('.edit').on('click',function(e){
	$(this).siblings('input').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){
	if( $(this).val().toLowerCase()==$(this).data('val').toLowerCase() ){
		$(this).attr('disabled','disabled');
	}
});
$('#editCountry').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	var res = obj.dhx_ajax('sysManage_c/country/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Update Sucessfully');
		obj.winTooble = true;
		obj.wind.window('country').close();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#editCountry { font-size:12px;}
#editCountry table{ margin:0 auto; padding-top:15px;}
#editCountry table tr td { padding:3px 5px;}
#editCountry table tr td input{ padding:4px 6px; font-size:12px; margin-right:10px;}
#editCountry p{ text-align:right; padding-right:24px; margin-top:10px;}
#editCountry p input{ margin-left:5px;}
</style>
