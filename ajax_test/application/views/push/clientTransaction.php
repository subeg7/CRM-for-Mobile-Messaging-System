<div id="clientTransaction">
	<div id="transactionTool"></div>
    <div id="transactionGrid" style="margin-top:10px;"></div>
    <div id="extraInfo"></div>
</div>

<script type="text/javascript">
(function(){
	var grid = null;
	var myForm = null;
	var keys = Object.keys(obj.popu);
	if( obj.form['search']!= undefined) {
		obj.form['search'].unload();
		delete obj.form['search'];
	}

	if( obj.popu['search']!= undefined) {
		obj.popu['search'].unload();
		delete obj.popu['search'];
	}

	if(obj.indi_toolbar['transactionTool'] ) obj.indi_toolbar['transactionTool'].unload();
	obj.indi_toolbar['transactionTool'] =  new dhtmlXToolbarObject({
												parent: 'transactionTool',
												icons_path : 'images/load',
												xml: '<?xml version="1.0"?><toolbar><item id="selectTran" type="buttonSelect" text="Select Report" img="/edit.png"><item type="button"	id="smsreport"  text="SMS Transaction" img="/transaction.png"/><item type="button"	id="creditlog" text="Credit Log"    img="/creditreport.png"  /></item><item id="todayReport" type="button" text="Today Report" img="/detail.png" /><item id="search" type="button" text="Search" img="/search.png" /></toolbar>'
										});
	var smsfrom = [
				{type: "settings", position: "label-left", labelWidth: 110, inputWidth: 130},
				{type: "calendar", label: "From", name: "from", dateFormat:"%Y-%m-%d"},
				{type: "calendar", label: "Till", name: "till", dateFormat:"%Y-%m-%d",time:'show'},
				{type: "button", value: "Submit", offsetLeft: 149}
			];
	var creditform = [
				{type: "settings", position: "label-left", labelWidth: 110, inputWidth: 130},
				{type: "combo", label: "Type", name: "type",options: [{text: "--Select--", value:"none", selected: true},{text: "Deduce", value:"2", selected: true},{text:"Alloted", value:"1"}]},
				{type: "calendar", label: "From", name: "from", dateFormat:"%Y-%m-%d"},
				{type: "calendar", label: "Till", name: "till", dateFormat:"%Y-%m-%d"},
				{type: "button", value: "Submit", offsetLeft: 149}
			];
	obj.popu['search'] = new dhtmlXPopup({ toolbar: obj.indi_toolbar['transactionTool'], id: "search" });
	obj.popu['search'].attachEvent("onShow", function(){

		if (obj.form['search'] != undefined) obj.form['search'].unload();

		obj.form['search'] = obj.popu['search'].attachForm(smsfrom);
		obj.form['search'].attachEvent("onButtonClick", function(name){

			obj.popu['search'].hide();
			var formdata = [];

			var start = obj.form['search'].getFormData().from;
			if(start !==''){
				start = parseInt(new Date(start).getTime()/1000);
				console.log(start);
			}
			var till = obj.form['search'].getFormData().till;
			if(till !==''){
				till = parseInt(new Date(till).getTime()/1000)+((60*60*24)-1);
			}
			if( (start !=='' && till !=='') && (till < start) ) {
				obj.message_show('** Error : Start Date is Greater than Till Date','error');
			}
			else{
				start = obj.form['search'].getFormData().from.toISOString().substring(0, 10);
				till = obj.form['search'].getFormData().till.toISOString().substring(0, 10);

				formdata.push('from='+ start);
				formdata.push('till='+ till);

				if(grid == 'smsreport'){

					obj.grid['transactionGrid_t'].clearAndLoad( "report_c/renderSmsTransaction/search/"+obj.grid['dhxDynFeild_t'].getSelectedRowId()+"?object=grid&"+formdata.join('&'));
				}else{
						obj.grid['transactionGrid_t'].clearAndLoad( "report_c/renderCredit/search/"+obj.grid['dhxDynFeild_t'].getSelectedRowId()+"?object=grid&"+formdata.join('&'),function(e){

							start = parseInt(new Date(start).getTime()/1000);
							till = parseInt(new Date(till).getTime()/1000)
							$('#extraInfo').empty()
							var res = obj.dhx_ajax('report_c/sumCreditlog/'+start+'/'+till+'/'+obj.grid['dhxDynFeild_t'].getSelectedRowId());

							if(res!=='none'){

								res = JSON.parse(res);
								var detail=[];
								var keys = Object.keys(res);
								for( i=0;i<keys.length; i++){
									if(keys[i]==1)	detail.push('<span style="color:blue;">Total Alloted :</span> '+res[keys[i]]);
									else if(keys[i]==2)	detail.push('<span style="color:blue;">Total Deduced :</span> '+res[keys[i]]);
								}
								$('#extraInfo').empty().append('<p>'+detail.join(' <span style="color:red;">||</span> ')+'</p>');

							}
						});
				}
			}
		});
	});


	obj.indi_toolbar['transactionTool'].attachEvent('onClick',function(id){

		if(id =='smsreport'){
			$('#extraInfo').empty()
			obj.dynamicGrid({ id:'transactionGrid',header:"Operator, Total Units,Date",setInitWidths:'144,150,370',multiple:'multiple',attachHeader:false,pagesInGrp:6,pageSize:8});
			grid = 'smsreport';
		}
		else if(id =='creditlog'){
			$('#extraInfo').empty()
			obj.dynamicGrid({ id:'transactionGrid',header:"Operator, Transaction Units,Type/Description,Remaining Balance,Date",setInitWidths:'90,120,180,124,150',multiple:'multiple',attachHeader:false,pagesInGrp:6,pageSize:8});
			grid = 'creditlog';
		}
		else if(id =='todayReport'){
			obj.grid['transactionGrid_t'].clearAndLoad('report_c/renderTodayReport/'+grid+'/'+obj.grid['dhxDynFeild_t'].getSelectedRowId(),function(e){
				if(grid==='creditlog'){
					var res = obj.dhx_ajax('report_c/sumCreditlog/today/null/'+obj.grid['dhxDynFeild_t'].getSelectedRowId());
					if(res!=='none'){
						res = JSON.parse(res);
						var detail=[];
						var keys = Object.keys(res);
						for( i=0;i<keys.length; i++){
							if(keys[i]==1)	detail.push('<span style="color:blue;">Total Alloted :</span> '+res[keys[i]]);
							else if(keys[i]==2)	detail.push('<span style="color:blue;">Total Deduced :</span> '+res[keys[i]]);
						}
						$('#extraInfo').empty().append('<p>'+detail.join(' <span style="color:red;">||</span> ')+'</p>');

					}
				}
			});

		}

	});


}());
</script>
<style>
#clientTransaction{ width:100%; height:100%; padding:10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;

}
#extraInfo{ font-size:12px;}
</style>
