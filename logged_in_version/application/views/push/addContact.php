<form id="addContact" action="#">
	<table>
    	<tr>
        	<td>S.N.</td>
        	<td>Name : </td>
            <td>Mobile No. : </td>
        </tr>
        <?php
			for($i=1; $i<11; $i++){
				echo '<tr><td>'.$i.'</td><td><input autocomplete="off" class="cname" value="Undefined" name="name'.$i.'" type="text" /></td><td><input autocomplete="off" maxlength="10" class="mname" name="mobile'.$i.'" type="text" /></td></tr>';
			}
		?>

    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function (){
$('input.mname').on('focusin',function(e){
	$(this).css('border','1px solid #c3bfbf');
});
$('input.cname').on('focusin',function(e){

	if($(this).val()=='Undefined'){
		$(this).val('');
	}
});
$('input.cname').on('focusout',function(e){
	var val = $(this).val().replace(/ /g,'');
	if(val==''){
		$(this).val('Undefined');
	}
});
$('#addContact').submit(function(e) {
    e.preventDefault();
	var bookid = obj.tree['addressbookTree'].getSelectedItemId();
	if( bookid=='' || bookid == 'books'){
		obj.message_show('** Warning : No address book selected','error');
		return;
	}
	var res = obj.dhx_ajax('push_c/addContact/'+bookid,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Contact has been added sucessfully');
		obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+bookid );

		$(this)[0].reset();
	}
	else{
		if(res==='fail') obj.message_show('** Error found','error');
		else if(res==='noentry') obj.message_show('** Error No item found for Entry','error');
		else{
			console.log(res);
			resPars = JSON.parse(res);
			console.log(resPars);
			if(resPars.fault!==undefined){
				obj.message_show('** Warning :'+ resPars.fault.length+' Number found fault','error');
				var faultLength  = resPars.fault.length;
				$('#addContact table tr td input').each(function(index, element) {
					if($(this).val() !=='Undefined'){
						if($.inArray( $(this).val(),resPars.fault)!=-1 ){
							if(faultLength !=0){
								$(this).css('border','1px solid red');
								faultLength--;
							}
						}
					}
                });
			}
			if(resPars.repeat!==undefined){
				obj.message_show('** Warning :'+ resPars.repeat.length+' Number found repeated','error');
				var repeatLength  = resPars.repeat.length;
				$('#addContact table tr td input').each(function(index, element) {
					if($(this).val() !=='Undefined'){
						if($(this).val() == resPars.repeat[0]){
							if(repeatLength !=0){
								$(this).css('border','1px solid blue');
								repeatLength--;
							}
						}
					}
                });
			}
			if(resPars.exist!==undefined){
				obj.message_show('** Warning :'+ resPars.exist.length+' Number Already Exist','error');
				var existLength  = resPars.exist.length;
				$('#addContact table tr td input').each(function(index, element) {
					if($(this).val() !=='Undefined'){
						if($.inArray( $(this).val(),resPars.exist)!=-1 ){
							if(existLength !=0){
								$(this).css('border','1px solid green');
								existLength--;
							}
						}
					}
                });
			}
		}

	}

});
})();
</script>
<style>
#addContact,#addContact table tr td select { font-size:12px;}
#addContact table{ margin:0 auto; padding-top:15px;}
#addContact table tr td { padding:3px 5px;}
#addContact table tr td input,#addContact table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#addContact p{ text-align:right; padding-right:24px; margin-top:10px;}
#addContact p input{ margin-left:5px;}
input.mname, input.cname{ border:1px solid #c3bfbf;}
</style>
