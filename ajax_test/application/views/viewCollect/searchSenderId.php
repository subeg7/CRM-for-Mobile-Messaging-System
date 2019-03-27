<div id="searchSenderId">
    <form action="#">
    	<table>
        	<tr>
            	<td>Sender ID</td>
                <td><input type="text" name="senderId"/></td>
            </tr>
            <tr>
            	<td>State</td>
                <td><select name="state">
                	<option value="none">--Select--</option>
                	<option value="1"> Requested </option>
                    <option value="2"> Approved </option>
                    <option value="3"> Disapproved </option>
                </select></td>
            </tr>
            <tr>
            	<td>Request By</td>
                <td><input type="text" name="reqby"/></td>
            </tr>
        	<tr>
            	<td>Start Date <span style="color:red;"> *</span></td>
                <td><input readonly id="sDate" type="text" name="searchStart"/></td>
            </tr>
            <tr>
            	<td>Till Date<span style="color:red;"> *</span></td>
                <td><input readonly id="tDate" type="text" name="searchTill"/></td>
            </tr>
        </table>
        <p><input id="searchReset" class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>
<script type="text/javascript">
(function(e){
	obj.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				multi:'single',
				id:'fromDate',
				param: [ 'sDate','tDate']
			});
	$('#searchReset').click(function(e) {
        obj.grid['dhxDynFeild_t'].clearAndLoad( "push_c/renderSenderId?object=grid");
		obj.searchQuery = '';
    });
	$('#searchSenderId form').submit(function(e) {
		obj.searchQuery = '';
		e.preventDefault();
		if(obj.prev_id !== 'sender_id'){
			obj.message_show('** Invalid Scope for search','error');
			obj.wind.window('search').close();
			return;
		}
		var arr =[];
		var start = $('#sDate').val();
		if(start !==''){
			start = parseInt(new Date(start).getTime()/1000);
			arr.push('searchStart='+(start).toString());
		}
		var till = $('#tDate').val();
		if(till !==''){
			till = parseInt(new Date(till).getTime()/1000)+((60*60*24)-1);
			arr.push('searchTill='+(till).toString());
		}
		if( (start !=='' && till !=='') && (till < start) ) {
			obj.message_show('** Error : Start Date is Greater than Till Date','error');
			return;
		}
		if(till!=='' || start!==''){
			if(till==='' || start ===''){
				obj.message_show('** Error : Fill both date feild','error');
				return;
			}
		}
		if($('#searchSenderId input[name="senderId"]').val().replace(/ +/g, "")==='' && $('#searchSenderId input[name="reqby"]').val().replace(/ +/g, "")==='' && $('#searchSenderId select[name="state"]').val().replace(/ +/g, "")==='none' && till==='' && start === ''){
			obj.message_show('** Warning : Please fill Sender ID feild or Both date feild to search sernder ID','error');
				return;
		}
		arr.push('senderId='+($('#searchSenderId input[name="senderId"]').val().replace(/ +/g, "")));
		if($('#searchSenderId input[name="reqby"]').val()!==''){
			arr.push('reqby='+($('#searchSenderId input[name="reqby"]').val().replace(/ +/g, "")));
		}
		arr.push('state='+($('#searchSenderId select[name="state"]').val().replace(/ +/g, "")));
		arr.push('senderId='+($('#searchSenderId input[name="senderId"]').val().replace(/ +/g, "")));
     //  console.log(arr);
		obj.grid['dhxDynFeild_t'].clearAndLoad( "push_c/renderSenderId/search?object=grid&"+arr.join('&'),function(e){
			obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
			if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');
			//console.log(obj.searchQuery);
		} );
		
    });
}());
</script>
<style>
#searchSenderId { font-size:12px; padding:10px;}
#searchSenderId form table { margin:0 auto;}
#searchSenderId form table tr td{
	padding:3px 5px; 
}
#searchSenderId form table tr td input,#searchSenderId form table tr td select{ padding:3px 10px; font-size:12px;}
#searchSenderId form table tr td input{ width:149px;}
#searchSenderId form table tr td select{ width:170px;}
#searchSenderId form p { text-align:right; margin:10px 12px 0 0;}
#searchSenderId form p input{ margin-left:5px;}
</style>
