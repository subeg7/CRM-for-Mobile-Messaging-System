<div id="pullreport_view">
</div>
<script type="text/javascript">
(function(){

	var left = 0;
	var right = 0;
	var chart_data_obj = [];
	var descript  = {mo:{},mt:{}};
	var acronym_data_obj =[];
	var acronym = {};
	var legends = [];


	$('#pullreport_view').empty().append('<div id ="load" style="position:absolute; top:0; z-index:2; left:0; height:100%; width:100%;  background-color:rgba(31,31,31,0.1); "><p style="text-align:center; margin:20% auto; padding:3px 40px 3px 0; background:url('+obj.l_imgP+') no-repeat center center; background-size:20px 20px; background-position:80px 0; width:70px;">Loading...</p></div><div id="addressTree" class="floatLeft"><p><input id="searchadd" placeholder="search" style="width:130px; padding:3px; font-size:11px; margin-right:10px;"/><a href="#" onClick="return obj.treeSearch('+"'addressbookTree'"+');"><img src="images/load/searchadd.png" style="height:20px; vertical-align:bottom;"/></a></p><div id="addressbookTree" style="margin-top:10px;"></div></div><div id="pullreport" class="floatRight"><div id="chartreport"></div><div id="detailrep"><div id="detailNav"></div><div id="detailData"></div></div></div><div class="clearBoth" style="height:0;"></div>');

	obj.dhx_tree({parent:'addressbookTree'});
	/// tree click event
	obj.tree['addressbookTree'].attachEvent('onClick',function( id ){
		if( id== 'list' ) return;
		if(obj.fromDate==null || obj.tillDate == null) {
			obj.message_show('** Warning : Invalid Date Range','error');
			return;
		}
		var parentNode = null;
		if(obj.comb['sortList'].getSelectedValue()=='user'){
			if(this.getParentId(id)=='list') return;
			parentNode = this.getParentId(id);

		}

		var searchFrom = parseInt(new Date(obj.fromDate).getTime()/1000);
		var searchTill = parseInt(new Date(obj.tillDate).getTime()/1000);
		var nowTIme =   parseInt(new Date().getTime()/1000);
		if( ( searchFrom > searchTill ) || (nowTIme < searchFrom) ) {
			obj.message_show('** Warning : Invalid Date Range','error');
			return;
		}


		$('#chartreport').empty();
		$('#detailrep #detailData,#detailrep #detailNav').empty();
		$('#load').show();

		var res = obj.dhx_ajax('pull_c/getPullReport/'+obj.comb['sortList'].getSelectedValue()+'/'+id+'/'+searchFrom+'/'+searchTill+'/'+parentNode);
		setTimeout(function(){  $('#load').hide(); }, 500);
		var total_mo =0;
		var total_mt =0;
		var randcolor = [];
		var chart_obj = {mo:{type:'M.O.'},mt:{type:'M.T.'}};
		var indi_descrp_data = {};// for vp keys data
		descript = {mo:{},mt:{}};
		chart_data_obj =[];
		legends = [];
		acronym = {};
		acronym_data_obj = [];
		left = 0; right= 0;


		if(res!=='fail'){

			var data = JSON.parse(res);
			var j=1;
			for(i =0; i < data.length; i ++){

				if(data[i].mo_mt ==1){
					chart_obj['mo'][data[i].acronym] = data[i].count;
					total_mo = total_mo+ data[i].count;
					if(descript['mo'][data[i].acronym]==undefined)descript['mo'][data[i].acronym] = data[i].count;
					else  descript['mo'][data[i].acronym] = descript['mo'][data[i].acronym] + data[i].count;

					acronym_data_obj.push(data[i].acronym);
				}else{
					chart_obj['mt'][data[i].acronym] = data[i].count;
					total_mt = total_mt + data[i].count;
					if(descript['mt'][data[i].acronym]==undefined)descript['mt'][data[i].acronym] = data[i].count;
					else  descript['mt'][data[i].acronym] = descript['mt'][data[i].acronym] + data[i].count;
				}
			}

			var keys_detail = acronym_data_obj;
			acronym_data_obj = acronym_data_obj.chunk(10);
			for(i=0; i <acronym_data_obj.length;i++){
				var temp_obj = {mo:{type:'M.O.'},mt:{type:'M.T.'}};
				for(j=0;j <acronym_data_obj[i].length; j++){
					temp_obj['mo'][acronym_data_obj[i][j]] = chart_obj['mo'][acronym_data_obj[i][j]];
					temp_obj['mt'][acronym_data_obj[i][j]] = chart_obj['mt'][acronym_data_obj[i][j]];

					indi_descrp_data[acronym_data_obj[i][j]] = {'mo':chart_obj['mo'][acronym_data_obj[i][j]],'mt':chart_obj['mt'][acronym_data_obj[i][j]]};
					if(j==acronym_data_obj[i].length-1) chart_data_obj.push(temp_obj);
				}
			}

			var treKeysType = (obj.tree['addressbookTree'].getItemText(obj.tree['addressbookTree'].getSelectedItemId())).split(' ');

			var des_keys = Object.keys(descript);

			/****** showing pull report numeric report *****/
			for(n=0; n <des_keys.length ; n++){

				var ele= '<div class="pullReportTotal"><h3>Total '+des_keys[n].toUpperCase()+'</h3>';

				var operator = Object.keys(descript[des_keys[n]]);
				if($.inArray('VP',treKeysType)==-1 && $.inArray('PTP',treKeysType)==-1){ // key isnot vp
					for(z=0; z <operator.length ; z++){
						ele = ele + '<p>'+operator[z].toUpperCase()+' : '+descript[des_keys[n]][operator[z]]+'</p>';
					}
				}else{
					var totals = 0;
					for(z=0; z <operator.length ; z++){
						totals = totals + parseInt(descript[des_keys[n]][operator[z]]);
					}
					ele = ele + '<p>'+totals+'</p>';
				}
				ele = ele+ '</div>';
				$('#detailrep #detailData').append(ele);
			}
			$('#detailrep').append('<div style="height:7px; width:100%; clear:both;"></div>');
			if($.inArray('VP',treKeysType)!=-1 || $.inArray('PTP',treKeysType)!=-1){ // key is vp
				var vpLength = Math.ceil(keys_detail.length/2);
				var eleVp = '';
				for(m=0; m < keys_detail.length; m++){
					if(m== 0 || m == vpLength){ eleVp = eleVp + '<div class="pullReportVpCover">'; }
					eleVp = eleVp + '<div class="pullReportVp"><h3> <span style="color:rgb(194, 42, 42);">[ '+(m+1)+' ] </span> '+keys_detail[m]+'</h3><p> M.O. : '+indi_descrp_data[keys_detail[m]].mo+'</p><p> M.T. : '+indi_descrp_data[keys_detail[m]].mt+'</p></div>';
					if(m== (vpLength-1) || m==(keys_detail.length-1) ){ eleVp = eleVp + '</div>'; }
				}
				$('#detailrep #detailData').append(eleVp);
				$('#detailrep').append('<div style="height:7px; width:100%; clear:both;"></div>');
			}

			var keys_ope = acronym_data_obj[0];

			for(i=0; i<keys_ope.length; i++){
				acronym[keys_ope[i]] = obj.color[i];
				legends.push({text:keys_ope[i],color:obj.color[i]});
			}
			var bar_obj ={
				view:"bar",
				container:"chartreport",
				gradient:"rising",
				value:"#"+keys_ope[0]+"#",
				color: acronym[keys_ope[0]],
				radius:0,
				tooltip:{
					template:"#"+keys_ope[0]+"#"
				},
				xAxis:{
					template:"'#type#"
				},
				yAxis:{
					start:0,
				},
				legend:{
					values:legends,
					valign:"middle",
					align:"left",
					width:90,
					layout:"y"
				}
			};
			//console.log(bar_obj);
			obj.dhx_chart(bar_obj);
			for(i=1; i<keys_ope.length; i++){
				obj.chrt['chartreport'].addSeries({
					value:"#"+ keys_ope[i] + "#",
					color:acronym[keys_ope[i]],
					tooltip:{
						template:"#"+keys_ope[i]+"#"
					}
				});
			}
			var chartdata = [chart_data_obj[0].mo,chart_data_obj[0].mt];
			console.log(chartdata);
			obj.chrt['chartreport'].parse(chartdata,"json");
			if(acronym_data_obj.length > 1){
				$('#detailrep #detailNav').append('<p id="reportNav"><input id="leftChart" type="button" value="<"/><span>Records from top 1 to '+(acronym_data_obj[0].length).toString()+'</span><input id="rightChart" type="button" value=">"/></p>');
			}

		}/// end of res
		/*}
		else if(obj.comb['sortList'].getSelectedValue()=='keys'){
		}*/
	});
	obj.tree['addressbookTree'].load('pull_c/renderTreeList/'+obj.comb['sortList'].getSelectedValue()+'?object=tree',function(){
		setTimeout(function(){  $('#load').hide(); }, 500);
		if(obj.tree['addressbookTree'].getUserData( 'session','session')!== undefined ){
			obj.session_expire(ths.tree['addressbookTree'].getUserData( 'session','session'));
		}
	});
	obj.comb['sortList'].attachEvent("onChange",function(value,text){
		$('#chartreport').empty();
		$('#detailData').empty();
		$('#load').show();
		obj.tree['addressbookTree'].deleteItem('list',false);
		obj.tree['addressbookTree'].load('pull_c/renderTreeList/'+obj.comb['sortList'].getSelectedValue()+'?object=tree',function(){
			setTimeout(function(){  $('#load').hide(); }, 500);
			if(obj.tree['addressbookTree'].getUserData( 'session','session')!== undefined ){
					obj.session_expire(obj.tree['addressbookTree'].getUserData( 'session','session'));
				}
		});

	});

	$('#detailrep').on('click','#reportNav input',function(e){

		if($(this).attr('id')=='leftChart'){
			if(right > 0 ) --right;
		}
		else if($(this).attr('id')=='rightChart'){
			++right;
			if(right > 0 ) $('#leftChart').removeAttr('disabled');
		}
		if( acronym_data_obj[right]== undefined ){
			return;
		}
		legends = []; acronym ={};
		$('#chartreport').empty();

		var keys_ope = acronym_data_obj[right];
		for(i=0; i<keys_ope.length; i++){
			acronym[keys_ope[i]] = obj.color[i];
			legends.push({text:keys_ope[i],color:obj.color[i]});
		}
		var bar_obj ={
		view:"bar",
		container:"chartreport",
		gradient:"rising",
		value:"#"+keys_ope[0]+"#",
		color: acronym[keys_ope[0]],
		radius:0,
		tooltip:{
			template:"#"+keys_ope[0]+"#"
		},
		xAxis:{
			template:"'#type#"
		},
		yAxis:{
			start:0,
		},
		legend:{
			values:legends,
			valign:"middle",
			align:"left",
			width:90,
			layout:"y"
		}
	};
	obj.dhx_chart(bar_obj);
	for(i=1; i<keys_ope.length; i++){
		obj.chrt['chartreport'].addSeries({
			value:"#"+ keys_ope[i] + "#",
			color:acronym[keys_ope[i]],
			tooltip:{
				template:"#"+keys_ope[i]+"#"
			}
		});
	}
	var chartdata = [chart_data_obj[right].mo,chart_data_obj[right].mt];

	obj.chrt['chartreport'].parse(chartdata,"json");
	if(acronym_data_obj.length > 1){
		var start = ((right*10)+1);
		var end =  ( parseInt((acronym_data_obj[right].length).toString()) + (10*right)) ;
		$('#reportNav span').empty().append('Records from top '+start+' to '+ end);
	}


	});
}());
</script>
<style>
#reportNav{ text-align:center; margin-bottom:15px;}
#reportNav span{ font-size:12px; margin: 0 15px;
}
#reportNav input{ cursor:pointer; font-size:12px; font-family:tahoma;}
#detailrep{ font-size:12px;}
.pullReportTotal { float: left; position:relative;
width: 300px;
border: 1px solid #adadad;
margin-left: 10px;
padding: 8px 10px;
}

.pullReportTotal h3{ color:rgb(194, 16, 16); position:absolute; top:-9px; left:10px; padding:2px 10px; background-color:white;}
.pullReportTotal p{ margin-top:3px;}
.pullReportVpCover{
	float: left; position:relative;
width: 322px;
margin-left: 10px;
}
.pullReportVp{ border: 1px solid #adadad; width:100%; padding:10px;
-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box;
position:relative;
margin-top:10px;
display:table;
}
.pullReportVp h3{ color:#2323b9; position:absolute; top:-9px; left:10px; padding:2px 10px; background-color:white;}
.pullReportVp  p{ float:left; margin-right:10px;}
.pullReportVp  p:last-child{ margin-right:0; border-left:2px solid green; padding-left:10px; }
</style>
