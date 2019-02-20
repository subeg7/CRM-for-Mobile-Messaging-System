<form id="editAddressbook" action="#">
	<table>
    	<tr>
        	<td>Addressbook Name :</td>
            <td><input autocomplete="off" disabled name="name" type="text" class="editDisabled" data-val="<?php echo ucwords($addressbook[0]->fld_chr_name); ?>" value="<?php echo ucwords($addressbook[0]->fld_chr_name); ?>" /><span class="edit">Edit..</span></td>
        </tr>
    	<tr>
        	<td>Description :</td>
            <td><input autocomplete="off" disabled name="description" type="text" class="editDisabled" data-val="<?php echo ucwords($addressbook[0]->fld_chr_desc); ?>" value="<?php echo ucwords($addressbook[0]->fld_chr_desc); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){
$('.edit').on('click',function(e){
	if($(this).siblings().is('input'))
		$(this).siblings('input').removeAttr('disabled').focus();
	else if($(this).siblings().is('select'))
		$(this).siblings('select').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){
	
	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	} 
});
$('#editAddressbook').submit(function(e) {
    e.preventDefault();
	var bookid = obj.tree['addressbookTree'].getSelectedItemId();
	if( bookid==''){
		obj.message_show('** Warning : No address book selected','error');
		return;
	}
	var res = obj.dhx_ajax('vas/sms/push_c/addressbook/edit/'+bookid,$(this).serialize() );

	if(res==='sucess'){
		obj.message_show('Addressbook Edited Sucessfully');
		obj.tree['addressbookTree'].deleteChildItems('0');
		obj.tree['addressbookTree'].load('vas/sms/push_c/renderAddressbook?object=tree');
		obj.wind.window('address_edit').close();
	}
	else{
		obj.message_show(res,'error');
	}
	
});
}());
</script>
<style>
.edit{ margin-left:10px;}
#editAddressbook,#editAddressbook table tr td select { font-size:12px;}
#editAddressbook table{ margin:0 auto; padding-top:15px;}
#editAddressbook table tr td { padding:3px 5px;}
#editAddressbook table tr td input,#editAddressbook table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#editAddressbook p{ text-align:right; padding-right:24px; margin-top:10px;}
#editAddressbook p input{ margin-left:5px;}
</style>