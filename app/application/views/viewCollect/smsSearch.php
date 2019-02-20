<div id="smssearch">
	<form>
    	<table>
        	<tr>
            	<td>From</td>
                <td><input type="text" name="from" readonly id="fromCredit"/></td>
            </tr>
            <tr>
            	<td>Till</td>
                <td><input type="text" name="till" readonly id="tillCredit"/></td>
            </tr>
        </table>
        <p><input class="button" type="reset" value="Reset"/><input class="button" id="userSubmit" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>

<script type="text/javascript">
obj.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				multi:'single',
				id:'fromDate',
				dateformat:"%Y-%m-%d",
				param: [ 'fromCredit','tillCredit']
			});
$('#smssearch form').submit(function(e) {
    e.preventDefault();
	var start = $('#fromCredit').val();
	var arr =[];
	if(start !==''){
		start = parseInt(new Date(start).getTime()/1000);
		arr.push('searchStart='+(start).toString());
	}
	var till = $('#tillCredit').val();
	if(till !==''){
		till = parseInt(new Date(till).getTime()/1000)+((60*60*24)-1);
		arr.push('searchTill='+(till).toString());
	}
	if( (till < start) ) {
		obj.message_show('** Error : Start Date is Greater than Till Date','error');
		return;
	}
	else if (start =='' || till =='') {
		obj.message_show('** Warning : One of the date field is empty','error');
		return;
	}
	
	obj.grid['dhxDynFeild_t'].clearAndLoad( "vas/sms/report_c/renderSmsTransaction/search?object=grid&"+$(this).serialize(),function(e){
	/*obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
		if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');*/
	} );
});

</script>
<style>
#smssearch{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#smssearch,#smssearch table tr td,#smssearch input,#smssearch select{
	font-size:12px; 
}
#smssearch form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#smssearch table{ margin:0 auto;}
#smssearch table input{ width:160px; padding:3px 5px;}
#smssearch table  select{ padding:3px 5px;}
#smssearch table tr td{ padding:3px 8px;}
#smssearch form > p { text-align:right;  margin-top:13px;}
#smssearch form > p input:last-child{ margin-left:10px;}
#senderid{ width:80px !important;}
</style>