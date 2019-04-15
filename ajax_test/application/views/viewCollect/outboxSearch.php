<div id="outboxsearch">
	<form>
    	<table>
        <?php
			// if(in_array('USER_MANAGE',$priv)){//buttonDebug
				echo '<tr>
            	<td>User ID</td>
                <td><input type="text" name="userid" id="userid"/></td>
            </tr>';
			// }//buttonDebug
		?>
        	<tr>
            	<td>Number</td>
                <td><input type="text" name="number" id="number"/></td>
            </tr>
            <tr>
            	<td>Sender ID</td>
                <td><input id="senderid" type="text" name="senderid" /></td>
            </tr>
            <tr>
            	<td>UserData</td>
                <td><input  type="text" name="userdata" /></td>
            </tr>
        	<tr>
            	<td>From</td>
                <td><input type="text" name="from" readonly id="fromCredit"/></td>
            </tr>
            <tr>
            	<td>Till</td>
                <td><input type="text" name="till" readonly id="tillCredit"/></td>
            </tr>
        </table>
        <p><input class="button" type="reset" value="Reset"/><input class="button" id="userSubmit" type="submit" name="submit" value="Search Outbox"/></p>
    </form>
</div>

<script type="text/javascript">
obj.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				multi:'single',
				id:'fromDate',
				dateformat:"%Y-%m-%d",
				param: [ 'fromCredit','tillCredit']
			});
$('#outboxsearch form').submit(function(e) {
	// console.log("searching the outbox");
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
	if( start =='' || till =='') {
		obj.message_show('** Error : One of the date Feild is empty','error');
		return;
	}
	if( (start !=='' && till !=='') && (till < start) ) {
		obj.message_show('** Error : Start Date is Greater than Till Date','error');
		return;
	}

	// store the start-till date range in the session
	console.log("Session Init with Message:start:",start," till:",till);
	window.sessionStorage.clear();
	sessionStorage.setItem("dateRangeStart",start);
	sessionStorage.setItem("dateRangeTill",till);
	
	// console.log("$(this).serialize()",$(this).serialize());
	// obj.grid['dhxDynFeild_t'].clearAndLoad( "report_c/renderOutbox/search?object=grid&"+$(this).serialize(),function(e){
	// 	console.log("ajax returned successfully with: ",e);
		
	// 	if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');
	// } );//default 
});
</script>
<style>
#outboxsearch{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;
box-sizing: border-box;}
#outboxsearch,#outboxsearch table tr td,#outboxsearch input,#outboxsearch select{
	font-size:12px;
}
#outboxsearch form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;
box-sizing: border-box;}
#outboxsearch table{ margin:0 auto;}
#outboxsearch table input{ width:160px; padding:3px 5px;}
#outboxsearch table  select{ padding:3px 5px;}
#outboxsearch table tr td{ padding:3px 8px;}
#outboxsearch form > p { text-align:right;  margin-top:13px;}
#outboxsearch form > p input:last-child{ margin-left:10px;}
</style>