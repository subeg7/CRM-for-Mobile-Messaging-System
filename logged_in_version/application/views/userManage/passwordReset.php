<form id="passwordreset">
	<table>
    	<tr>
        	<td>New Password :</td>
            <td><input autocomplete="off" name="password" type="password" /></td>
        </tr>
        <tr>
        	<td>Conform Password :</td>
            <td><input autocomplete="off" name="conpassword" type="password" /></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>

</form>
<script type="text/javascript" language="javascript">

$('#passwordreset').submit(function(e) {
    e.preventDefault();
	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;
	var res = obj.dhx_ajax('userManage_c/resetPassword',$(this).serialize()+'&userid='+selId );
	if(res==='sucess'){
		obj.message_show('New Password has been changed sucessfully');
		obj.wind.window('passwordreset').close();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#passwordreset { font-size:12px;}
#passwordreset table{ margin:0 auto; padding-top:15px;}
#passwordreset table tr td { padding:3px 5px;}
#passwordreset table tr td input{ padding:4px 6px; font-size:12px; margin-left:5px;}
#passwordreset p{ text-align:right; padding-right:11px; margin-top:10px;}
#passwordreset p input{ margin-left:5px; font-size:12px;}
</style>
