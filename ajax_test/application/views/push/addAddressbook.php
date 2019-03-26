<form id="addAddressbook" action="#">
	<table>
    	<tr>
        	<td>Addressbook Name :</td>
            <td><input autocomplete="off" name="name" type="text" /></td>
        </tr>
    	<tr>
        	<td>Description :</td>
            <td><input autocomplete="off" name="description" type="text" /></td>
        </tr>

    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addAddressbook').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('push_c/addressbook/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Addressbook has been added sucessfully');
		obj.tree['addressbookTree'].deleteChildItems('0');
		obj.tree['addressbookTree'].load('push_c/renderAddressbook?object=tree');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
		console.log(res);
	}

});

</script>
<style>
#addAddressbook,#addAddressbook table tr td select { font-size:12px;}
#addAddressbook table{ margin:0 auto; padding-top:15px;}
#addAddressbook table tr td { padding:3px 5px;}
#addAddressbook table tr td input,#addAddressbook table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#addAddressbook p{ text-align:right; padding-right:24px; margin-top:10px;}
#addAddressbook p input{ margin-left:5px;}
</style>
