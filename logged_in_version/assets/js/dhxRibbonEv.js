obj.ribb['ribbon'].attachEvent("onClick", function(itemId, bId){
	$('#extraData').empty();
	if(obj.wind !=null) obj.destroy_dhx_object('window'); // destroying all the windos if exist
	if(itemId == 'sendsms'){
		obj.showItem_toolBar('toolbar');
		obj.excludeNumber = [];
		obj.load_ext_file('#dhxDynFeild','push_c/pushView/sendsms');
	}
	else if(itemId == 'pullreport'){
		// pull detail report
		$('#from_calander,#till_calander').val('');// clearing input element value in tool bar
		obj.initCalanderInToolbar();
		var tree_items = ($.inArray('USER_MANAGE',obj.priv)!=-1 )?[{value: "user", text: "User" },{value: "shortcode", text: "Shortcode",selected: true},{value: "keys", text: "Keys"},] : [{value: "shortcode", text: "Shortcode",selected: true},{value: "keys", text: "Keys"}]
		obj.dhx_combo({
			parent: "sortList",
			width: 100,
			name: "sortList",
			items: tree_items
		});
		obj.showItem_toolBar('toolbar',['pullReportSort','pullCombo','till_calander','from_calander']);
		obj.load_ext_file('#dhxDynFeild','pull_c/pullView/pullreport');

	}else if(itemId == 'error_report'){
	// pull error detail report
		$('#from_calander,#till_calander').val('');// clearing input element value in tool bar
		obj.initCalanderInToolbar();
		obj.dhx_combo({
			parent: "errsortList",
			width: 100,
			name: "errsortList",
			items: [
			/*	{value: "none", text: "--Select--"},*/
				{value: "shortcode", text: "Shortcode"},
				{value: "keys", text: "Keys",selected: true},
			]
		});
		obj.showItem_toolBar('toolbar',['pullReportSort','errpullCombo','till_calander','from_calander']);
		obj.load_ext_file('#dhxDynFeild','pull_c/pullView/pullerror');

	}else if(itemId == 'feedback'){
	// feedback pull detail report
		$('#from_calander,#till_calander').val('');// clearing input element value in tool bar
		obj.initCalanderInToolbar();
		obj.showItem_toolBar('toolbar',['till_calander','from_calander']);
		obj.load_ext_file('#dhxDynFeild','pull_c/pullView/pullfeedback');
	}
	else if(itemId == 'rst_pass'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:'passwordreset',height:170,width:300,header:'Reset Password',file_l:'userManage_c/userView/passwordReset/'+selId});
	}
	else if(itemId == 'cl_sms_tran'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:'cl_sms_tran',height:600,width:700,header:'Cleint Transaction',file_l:'push_c/pushView/clientTransaction/'+selId});
	}
	else if(itemId == 'addBal'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:'addbalance',height:270,width:300,header:'Add Balance',file_l:'userManage_c/userView/addBalance/'+selId,clear_l:'userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend'), gridId:'dhxDynFeild_t'});
	}
	else if(itemId == 'assignshortcode'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:itemId,height:450,width:550,header:'Assign Shortcode',file_l:'pull_c/pullView/assignShortcode/'+selId});

	}
	else if(itemId == 'assigngateway'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:itemId,height:450,width:550,header:'Assign Shortcode',file_l:'push_c/pushView/assignGateway/'+selId});

	}
	else if(itemId == 'assignfeature'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:itemId,height:450,width:550,header:'Assign Feature',file_l:'common_c/load_View/assignFeature/'+selId,clear_l:'userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend'), gridId:'dhxDynFeild_t'});

	}
	else if(itemId == 'gen_exl_list'){
		var res = obj.dhx_ajax('common_c/rdyDownload');
		if(res=='fail' && res ==''){  obj.message_show('**Error Found in download' ,'error');}
		window.open('userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend')+'/download?id='+res+'&'+obj.searchQuery,'_blank')


	}

	else{
		obj.addRemoveObj(itemId);
	}

	if(itemId!='cl_sms_tran' && itemId!='addBal' && itemId!='rst_pass' && itemId!='assignshortcode' && itemId!='assigngateway' && itemId!='assignfeature' && itemId!='gen_exl_list'){
		obj.prev_id = itemId;
	}
});
obj.ribb['ribbon'].attachEvent('onSelectOption',function( id,ind,text){
	if(id == 'combo_st'){
		if(obj.prev_id!='userList'){obj.message_show('**:Invalid Operation');return;}
		if( ind == 0 ){
			obj.showItem_toolBar('toolbar',['newuser','userapprove','detail','search','pedit']);
			obj.grid['dhxDynFeild_t'].clearAndLoad('userManage_c/renderUser/suspend' );
		}
		else if( ind == 1 ){
			obj.showItem_toolBar('toolbar',['newuser','usersuspend','detail','search','pedit']);
			obj.grid['dhxDynFeild_t'].clearAndLoad('userManage_c/renderUser/approve' );
		}
	}
});
