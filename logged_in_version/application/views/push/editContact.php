<form id="editContact" action="#">
	<table>
    	<tr>
        	<td>S.N.</td>
        	<td>Name : </td>
            <td>Mobile No. : </td>
        </tr>
        <?php
			$i=1;
			foreach($detail as $row){
				echo '<tr  data-val="'.$row->fld_int_id.'" ><td>'.$i.'</td><td><input disabled autocomplete="off" class="cname editDisabled" value="Undefined" name="name'.$i.'" type="text" data-val="'.ucwords($row->fld_chr_name).'" value="'.ucwords($row->fld_chr_name).'"  /><span class="edit">Edit..</span></td><td><input autocomplete="off" maxlength="10" disabled class="mname editDisabled" name="mobile'.$i.'" type="text" data-val="'.$row->fld_chr_phone.'" value="'.$row->fld_chr_phone.'" /><span class="edit">Edit..</span></td></tr>';
				$i++;
			}
		?>

    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function (){
$('.edit').on('click',function(e){
	if($(this).siblings().is('input'))
		$(this).siblings('input').removeAttr('disabled').focus();
	else if($(this).siblings().is('select'))
		$(this).siblings('select').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){

	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		console.log($(this).parent('tr').data('val'));
	}
});

$('#editContact').submit(function(e) {
	e.preventDefault();
	var bookid = obj.tree['addressbookTree'].getSelectedItemId();
	if( bookid=='' || bookid == 'books'){
		obj.message_show('** Warning : No address book selected','error');
		return;
	}
	var arr = [];
	$('#editContact table tr td input').each(function(index, element) {

        if($(this).attr('name') != 'reset' && $(this).attr('name') != 'submit' ){
			if(!$(this).is(':disabled')){
				arr.push($(this).parents('tr').data('val')+'_'+$(this).attr('name')+'='+$(this).val() );
			}
		}
    });
	var res = obj.dhx_ajax('push_c/editContact/'+bookid,arr.join('&') );
	if(res === 'sucess'){
		obj.message_show('Contact Updated Sucessfully');
		obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+bookid );
		obj.wind.window('contact_edit').close();
	}
	else obj.message_show(res,'error');

});
})();
</script>
<style>
#editContact,#editContact table tr td select { font-size:12px;}
#editContact table{ margin:0 auto; padding-top:15px;}
#editContact table tr td { padding:3px 5px;}
#editContact table tr td input,#editContact table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#editContact p{ text-align:right; padding-right:24px; margin-top:10px;}
#editContact p input{ margin-left:5px;}
input.mname, input.cname{ border:1px solid #c3bfbf;}
span.edit{ margin-left:5px;}
</style>
