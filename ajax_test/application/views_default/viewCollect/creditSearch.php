<div id="creditsearch">
	<form>
    	<table>
        	<tr>
            	<td>Type</td>
                <td>
                	<select id="transType" name="type">
                		<option value="none">--Select--</option>
                        <option value="1">Alloted</option>
                        <option value="2">Deduced</option>
                	</select>
                </td>
            </tr>
        	<tr>
            	<td>From</td>
                <td><input type="text" name="from" readonly id="fromCredit"></td>
            </tr>
            <tr>
            	<td>Till</td>
                <td><input type="text" name="till" readonly id="tillCredit"></td>
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
$('#creditsearch form').submit(function(e) {
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
	if( (start !=='' && till !=='') && (till < start) ) {
		obj.message_show('** Error : Start Date is Greater than Till Date','error');
		return;
	}

	obj.grid['dhxDynFeild_t'].clearAndLoad( "report_c/renderCredit/search?object=grid&"+$(this).serialize(),function(e){
		var res = obj.dhx_ajax('report_c/sumCreditlog/'+start+'/'+till);
		if(res!=='none'){

			res = JSON.parse(res);
			var detail=[];
			var keys = Object.keys(res);
			for( i=0;i<keys.length; i++){
				if(keys[i]==1)	detail.push('<span style="color:blue;">Total Alloted :</span> '+res[keys[i]]);
				else if(keys[i]==2)	detail.push('<span style="color:blue;">Total Deduced :</span> '+res[keys[i]]);
			}
			$('#extraData').empty().append('<p>'+detail.join(' <span style="color:red;">||</span> ')+'</p>');

		}

	/*obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
		if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');*/
	} );
});

</script>
<style>
#creditsearch{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;
box-sizing: border-box;}
#creditsearch,#creditsearch table tr td,#creditsearch input,#creditsearch select{
	font-size:12px;
}
#creditsearch form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;
box-sizing: border-box;}
#creditsearch table{ margin:0 auto;}
#creditsearch table input{ width:180px; padding:3px 5px;}
#creditsearch table  select{width:190px; padding:3px 5px;}
#creditsearch table tr td{ padding:3px 8px;}
#creditsearch form > p { text-align:right;  margin-top:13px;}
#creditsearch form > p input:last-child{ margin-left:10px;}
</style>
