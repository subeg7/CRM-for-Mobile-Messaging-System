<form id="addShortcode" action="#">
	<table>
    	<tr>
        	<td>Shortcode :</td>
            <td><input autocomplete="off" name="shortcode" type="text" /></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><textarea cols="18" rows="5" autocomplete="off" name="description"></textarea></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addShortcode').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('vas/sms/pull_c/shortcode/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Shortcode has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/pull_c/renderShortcode');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#addShortcode, #addShortcode textarea { font-size:12px;}
#addShortcode table{ margin:0 auto; padding-top:15px;}
#addShortcode table tr td { padding:3px 5px;}
#addShortcode table tr td input{ padding:4px 6px; font-size:12px; width:148px;}
#addShortcode p{ text-align:right; padding-right:24px; margin-top:10px;}
#addShortcode p input{ margin-left:5px;}
</style>