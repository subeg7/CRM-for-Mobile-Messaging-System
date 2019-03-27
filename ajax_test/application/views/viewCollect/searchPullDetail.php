<div id="pullDetails">
	<form id="pullDetailForm">
    	<table>
        	<tr>
            	<td>Shortcode</td>
                <td>: <select name="shortcode">
                	<option value="none">--Select--</option>
                    <?php 
						if($shortcode!='none'){
							foreach($shortcode as $row){
								echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_name.'</option>';
							}
						}
					?>
                </select></td>
            </tr>
        	<tr>
            	<td>Count</td>
                <td>: <span class="red">[ > 1 ]</span> <input style="width:auto;" type="radio" name="overCount" value="over_single"> <span class="red">[ single ]</span> <input style="width:auto;" type="radio" name="overCount" value="single"></td>
            </tr>
        	<tr>
            	<td>From</td>
                <td><input id="from"type="text" name="from" readonly></td>
            </tr>
            <tr>
            	<td>Till</td>
                <td><input id="till"type="text" name="till" readonly></td>
            </tr>
         
            
        </table>
        <p><input class="button" type="reset" id="searchReset" value="Reset"/><input class="button" id="userSubmit" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>

<script type="text/javascript">
(function(){
obj.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				multi:'single',
				id:'fromDate',
				dateformat:"%Y-%m-%d",
				param: [ 'from','till']
			});
$('#pullDetails form').submit(function(e) {
    e.preventDefault();
	obj.grid['dhxDynFeild_t'].clearAndLoad( 'report_c/detailPull/search?object=grid&'+$(this).serialize(),function(e){
	//obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
	if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');
	} );
});

}());
</script>
<style>
#pullDetails{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#pullDetails,#pullDetails table tr td,#pullDetails input,#pullDetails select{
	font-size:12px; 
}
#pullDetails form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#pullDetails table{ margin:0 auto;}
#pullDetails table input,#pullDetails table  select{ width:150px; padding:3px 5px;}
#pullDetails table tr td{ padding:3px 8px;}
#pullDetails form > p { text-align:right;  margin-top:13px;}
#pullDetails form > p input:last-child{ margin-left:10px;}
</style>
