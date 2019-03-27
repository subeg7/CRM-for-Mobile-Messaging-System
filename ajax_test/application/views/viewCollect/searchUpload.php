<div id="uploadSearch">
	<form id="uploadForm">
    	<table>
        	<tr>
            	<td>Unique ID</td>
                <td><input id="uniqueid" type="text" name="unique" ></td>
            </tr>
        	<tr>
            	<td>Count</td>
                <td>: <span class="red">[ > 1 ]</span> <input style="width:auto;" type="radio" name="overCount" value="over_single"> <span class="red">[ single ]</span> <input style="width:auto;" type="radio" name="overCount" value="single"></td>
            </tr>
        </table>
        <p><input class="button" type="reset" id="searchReset" value="Reset"/><input class="button" id="userSubmit" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>

<script type="text/javascript">
(function(){
$('#uploadSearch form').submit(function(e) {
    e.preventDefault();
	var counts = null
	var uniqueid = $('#uniqueid').val();
	$('#uploadForm input[name="overCount"]').each(function(index, element) {
        if($(this).is(':checked')) counts = $(this).val();
    });
	if(uniqueid=='' && counts==null ){
		obj.message_show('** One of the field must be filled or selected','error');
		return;
	}
	if(uniqueid=='') uniqueid='none';
	
	obj.grid['uploadGrid_t'].clearAndLoad( 'pull_c/renderUpload/'+obj.tree['uploadkeyTree'].getSelectedItemId()+'/'+uniqueid+'/'+counts+'?object=grid',function(e){
	//obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
	/*if(obj.grid['uploadGrid_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');*/
	} );
});

}());
</script>
<style>
#uploadSearch{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#uploadSearch,#uploadSearch table tr td,#uploadSearch input,#uploadSearch select{
	font-size:12px; 
}
#uploadSearch form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#uploadSearch table{ margin:0 auto;}
#uploadSearch table input,#uploadSearch table  select{ width:150px; padding:3px 5px;}
#uploadSearch table tr td{ padding:3px 8px;}
#uploadSearch form > p { text-align:right;  margin-top:13px;}
#uploadSearch form > p input:last-child{ margin-left:10px;}
</style>
