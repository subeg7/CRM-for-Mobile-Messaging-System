<form id="addCountry" action="#">
	<table>
    	<tr>
        	<td>Country Name :</td>
            <td><input autocomplete="off" name="name" type="text" /></td>
        </tr>
        <tr>
        	<td>Acronym :</td>
            <td><input autocomplete="off" name="acronym" type="text" /></td>
        </tr>
        <tr>
        	<td>Country Code :</td>
            <td><input autocomplete="off" name="code" type="text" /></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addCountry').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('vas/sms/sysManage_c/country/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Country has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/sysManage_c/renderCountry');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#addCountry { font-size:12px;}
#addCountry table{ margin:0 auto; padding-top:15px;}
#addCountry table tr td { padding:3px 5px;}
#addCountry table tr td input{ padding:4px 6px; font-size:12px; margin-left:5px;}
#addCountry p{ text-align:right; padding-right:24px; margin-top:10px;}
#addCountry p input{ margin-left:5px;}
</style>