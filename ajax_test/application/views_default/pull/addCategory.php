<form id="addCategory" action="#">
	<table>
    	<tr>
        	<td>Category :</td>
            <td><input autocomplete="off" name="category" type="text" /></td>
        </tr>
        <tr>
        	<td>Uploadable :</td>
            <td><input autocomplete="off" style="width:auto;"name="upload" type="checkbox" value="upload" /></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><textarea cols="18" rows="5" autocomplete="off" name="description"></textarea></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addCategory').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('pull_c/category/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Shortcode has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('pull_c/renderCategory');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#addCategory,#addCategory table tr td textarea { font-size:12px;}
#addCategory table{ margin:0 auto; padding-top:15px;}
#addCategory table tr td { padding:3px 5px;}
#addCategory table tr td input{ padding:4px 6px; font-size:12px; width:148px;}
#addCategory p{ text-align:right; padding-right:24px; margin-top:10px;}
#addCategory p input{ margin-left:5px;}
</style>
