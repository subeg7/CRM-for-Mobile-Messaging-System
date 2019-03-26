<div id="pullFeedback"></div>
<script type="text/javascript">
	obj.initCalanderInToolbar();
	obj.showItem_toolBar('toolbar',['till_calander','from_calander']);
	$('#pullFeedback').empty().append('<div id ="load" style="position:absolute; top:0; z-index:2; left:0; height:100%; width:100%;  background-color:rgba(31,31,31,0.1); "><p style="text-align:center; margin:20% auto; padding:3px 40px 3px 0; background:url('+obj.l_imgP+') no-repeat center center; background-size:20px 20px; background-position:80px 0; width:70px;">Loading...</p></div><div id="addressTree" class="floatLeft"><p><input id="searchadd" placeholder="search" style="width:130px; padding:3px; font-size:11px; margin-right:10px;"/><a href="#" onClick="return obj.treeSearch('+"'addressbookTree'"+');"><img src="images/load/searchadd.png" style="height:20px; vertical-align:bottom;"/></a></p><div id="addressbookTree" style="margin-top:10px;"></div></div><div id="feedback" class="floatRight"></div><div class="clearBoth" style="height:0;"></div>');

	obj.dhx_tree({parent:'addressbookTree'});
	obj.create_dhx_grid({
		p_id				: "feedback",
		setHeader			: "Sender,Operator,Text",
		//attachHeader		: "#text_filter,#text_filter,#text_filter",
		setInitWidths		: "140,100,440",
		setColAlign			: "left,left,left",
		setColTypes			: 'txt,txt,txt',
		setColumnIds		: '"sender,text,ope"',
		enableEditEvents	: 'true,false,true',
		multi_select		: true,

	});
	obj.tree['addressbookTree'].attachEvent('onClick',function( id ){
		if( id== 'list' ) return;
		if(obj.fromDate==null || obj.tillDate == null) {
			obj.message_show('** Warning : Invalid Date Range','error');
			return;
		}

		var searchFrom = parseInt(new Date(obj.fromDate).getTime()/1000);
		var searchTill = parseInt(new Date(obj.tillDate).getTime()/1000);
		if( searchFrom > searchTill) {
			obj.message_show('** Warning : Invalid Date Range','error');
			return;
		}

		$('#load').show();
		obj.grid['feedback_t'].clearAndLoad( "pull_c/renderFeedback/"+id+'/'+searchFrom+'/'+searchTill+'?object=grid' );
		setTimeout(function(){  $('#load').hide(); }, 500);
	});
	obj.tree['addressbookTree'].load('pull_c/getFeedbackList?object=tree',function(){
		setTimeout(function(){  $('#load').hide(); }, 500);
		if(obj.tree['addressbookTree'].getUserData( 'session','session')!== undefined ){
			obj.session_expire(obj.tree['addressbookTree'].getUserData( 'session','session'));
		}
	});




</script>
