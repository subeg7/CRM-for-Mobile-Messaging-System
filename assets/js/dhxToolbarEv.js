/*
this file contains the toolbar event  oriented javscript
*/

// @pram :  id , gives the id of the tool bar 
obj.tool['toolbar'].attachEvent('onClick',function(id){// start of toolbar event
	
	if(id=='newcountry'){
		obj.dhx_win({ id:id,height:200,width:300,header:'Add Country',file_l:'vas/sms/sysManage_c/sysView/addCountry'});
	}
	else if(id=='newoperator'){
		obj.dhx_win({ id:id,height:200,width:300,header:'Add Operator',file_l:'vas/sms/sysManage_c/sysView/addOperator'});
	}
	else if(id=='newblocknumber'){
		obj.dhx_win({ id:id,height:320,width:450,header:'Add Cell No.',file_l:'vas/sms/sysManage_c/sysView/blockNumber'});
	}
	else if(id=='newmsgtemplate'){
		obj.dhx_win({ id:id,height:370,width:450,header:'Add Template',file_l:'vas/sms/push_c/pushView/addMsgTemplate'});
	}
	else if(id=='newfeature'){
		obj.dhx_win({ id:id,height:200,width:310,header:'Add Feature',file_l:'vas/sms/sysManage_c/sysView/addFeature'});
	}
	else if(id=='newprefix'){
		obj.dhx_win({ id:id,height:170,width:300,header:'Add Operator',file_l:'vas/sms/sysManage_c/sysView/addPrefix'});
	}
	else if(id=='newgroup'){
		obj.dhx_win({ id:id,height:550,width:600,header:'Add Group',file_l:'vas/sms/sysManage_c/sysView/addGroup'});
	}
	else if(id=='newuser'){
		obj.dhx_win({ id:id,height:610,width:710,header:'Add User',file_l:'vas/sms/userManage_c/userView/addUsers',clear_l:'vas/sms/userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend'), gridId:'dhxDynFeild_t'});
	}
	else if(id=='newshortcode'){
		obj.dhx_win({ id:id,height:250,width:300,header:'Add Shortcode',file_l:'vas/sms/pull_c/pullView/addShortcode'});
	}
	else if(id=='newcategory'){
		obj.dhx_win({ id:id,height:270,width:300,header:'Add Shortcode',file_l:'vas/sms/pull_c/pullView/addCategory'});
	}
	else if(id=='newgateway'){
		obj.dhx_win({ id:id,height:400,width:330,header:'Add Gateway',file_l:'vas/sms/push_c/pushView/addGateway'});
	}
	else if(id=='newaddressbook'){
		obj.dhx_win({ id:id,height:177,width:330,header:'Add Addressbook',file_l:'vas/sms/push_c/pushView/addAddressbook'});
	}
	else if(id=='newcontact'){
		var bookid = obj.tree['addressbookTree'].getSelectedItemId();
		if( bookid=='' || bookid == 'books'){
			obj.message_show('** Warning : No address book selected','error');
			return;
		}
		obj.dhx_win({ id:id,height:425,width:430,header:'Add Contact',file_l:'vas/sms/push_c/pushView/addContact'});
	}
	else if(id=='newscheduler'){
		obj.dhx_win({ id:id,height:425,width:870,header:'Add Scheduler Job',file_l:'vas/sms/push_c/pushView/addScheduler'});
	}
	else if(id=='newtemplate'){
		obj.dhx_win({ id:id,height:425,width:450,header:'Add Message',file_l:'vas/sms/push_c/pushView/addTemplate'});
	}
	else if(id=='newsenderid'){
		obj.dhx_win({ id:id,height:325,width:400,header:'Request Sender ID',file_l:'vas/sms/push_c/pushView/addSenderId'});
	}
	else if(id=='newkey'){
		obj.dhx_win({ id:id,height:395,width:870,header:'Add Keys',file_l:'vas/sms/pull_c/pullView/addKey'});
	}
	else if(id=='newscheme'){
		obj.dhx_win({ id:id,height:500,width:700,header:'Add Scheme',file_l:'vas/sms/pull_c/pullView/addScheme'});
	}
	else if(id=='assignDetail'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:id,height:500,width:600,header:'Shortcode Assign Details',file_l:'vas/sms/pull_c/pullView/assignShortcodeDetail/'+selId});
	}
	
	else if(id=='edit'){
		// start of edit
		if(obj.prev_id == 'country'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:200,width:350,header:'Edit Country',file_l:'vas/sms/sysManage_c/sysView/editCountry/'+selId,clear_l:'vas/sms/sysManage_c/renderCountry', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'operator'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:200,width:350,header:'Edit Operator',file_l:'vas/sms/sysManage_c/sysView/editOperator/'+selId,clear_l:'vas/sms/sysManage_c/renderOperator', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'feature'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:200,width:350,header:'Edit Feature',file_l:'vas/sms/sysManage_c/sysView/editFeature/'+selId,clear_l:'vas/sms/sysManage_c/renderFeature', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'prefix'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:200,width:350,header:'Edit Prefix',file_l:'vas/sms/sysManage_c/sysView/editPrefix/'+selId,clear_l:'vas/sms/sysManage_c/renderPrefix', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'group'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:550,width:600,header:'Edit Group',file_l:'vas/sms/sysManage_c/sysView/editGroup/'+selId,clear_l:'vas/sms/sysManage_c/renderGroup', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'shortcode'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:250,width:300,header:'Edit Shortcode',file_l:'vas/sms/pull_c/pullView/editShortcode/'+selId,clear_l:'vas/sms/pull_c/renderShortcode', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'category'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id ,height:250,width:300,header:'Edit Category',file_l:'vas/sms/pull_c/pullView/editCategory/'+selId,clear_l:'vas/sms/pull_c/renderCategory', gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'gateway'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id,height:400,width:330,header:'Add Gateway',file_l:'vas/sms/push_c/pushView/editGateway/'+selId,clear_l:'vas/sms/push_c/renderGateway',gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'template'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id,height:425,width:500,header:'Edit Template',file_l:'vas/sms/push_c/pushView/editTemplate/'+selId,clear_l:'vas/sms/push_c/renderTemplate',gridId:'dhxDynFeild_t'});
		}
		else if(obj.prev_id == 'scheduler'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			obj.dhx_win({ id:obj.prev_id,height:380,width:500,header:'Edit Scheduler',file_l:'vas/sms/push_c/pushView/editScheduler/'+selId,clear_l:'vas/sms/push_c/renderSchduler',gridId:'dhxDynFeild_t'});
		}
		// end of edit
	}
	else if( id == 'address_edit'){
		if(obj.prev_id == 'addressbook'){
			var bookid = obj.tree['addressbookTree'].getSelectedItemId();
			if( bookid=='' || bookid == 'books'){
				obj.message_show('** Warning : No address book selected','error');
				return;
			}
			obj.dhx_win({ id:id,height:177,width:370,header:'Edit Addressbook',file_l:'vas/sms/push_c/pushView/editAddressbook/'+bookid});
		}
	}
	else if( id == 'contact_edit'){
		if(obj.prev_id == 'addressbook'){
			var selId = obj.getSelected('addressGrid','multiple');
			if(selId == null) return;
			obj.dhx_win({ id:id,height:425,width:480,header:'Edit Contact',file_l:'vas/sms/push_c/pushView/editContact/'+(selId.split(',')).join('_') });
		}
	}
	else if( id == 'empty'){
		if(obj.prev_id == 'addressbook'){
			var bookid = obj.tree['addressbookTree'].getSelectedItemId();
			if( bookid=='' || bookid == 'books'){
				obj.message_show('** Warning : No address book selected','error');
				return;
			}
		}
		obj.message_show('Do you want to Truncate ?','confirm',obj.truncate);	
	}
	else if(id=='userapprove' || id=='usersuspend'){
		obj.message_show('Do you want to Continue ?','confirm',obj.userManage);	
	}	
	else if(id=='detail'){
		if(obj.prev_id == 'addressbook'){
			var bookid = obj.tree['addressbookTree'].getSelectedItemId();
			if( bookid=='' || bookid == 'books'){
				obj.message_show('** Warning : No address book selected','error');
				return;
			}
			obj.dhx_win({ id:id,height:300,width:450,header:'Addressbook Detail',file_l:'vas/sms/push_c/pushView/addressDetail/'+bookid});
		}
		else if(obj.prev_id == 'keys'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			
			obj.dhx_win({ id:id,height:500,width:600,header:'Key Detail',file_l:'vas/sms/pull_c/pullView/keydetail/'+selId});
			
		}
		
		else{
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			if(obj.prev_id == 'userList'){
				obj.dhx_win({ id:id,height:500,width:770,header:'User Detail',file_l:'vas/sms/userManage_c/userView/detail/'+selId});
			}
			else if(obj.prev_id == 'sentbox'){
				obj.dhx_win({ id:id,height:500,width:620,header:'SentBox Detail',file_l:'vas/sms/report_c/reportView/sentbox/'+selId});
				
			}
		}
		
	}
	else if(id=='pedit'){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		obj.dhx_win({ id:id,height:450,width:600,header:'Edit User Privileges',file_l:'vas/sms/userManage_c/userView/privEdit/'+selId});
	}
	else if(id=='delete' || id == 'address_delete' || id == 'contact_delete'){
		obj.message_show('Do you want to delete ?','confirm',obj.deleteFun);	
	}
	else if(id=='disselect'){
		obj.grid['dhxDynFeild_t'].clearSelection();
	}
	else if(id=='select'){
		obj.grid['dhxDynFeild_t'].selectAll();
	}
	else if(id=='upload'){
		if(obj.prev_id == 'addressbook'){
			var bookid = obj.tree['addressbookTree'].getSelectedItemId();
			if( bookid=='' || bookid == 'books'){
				obj.message_show('** Warning : No address book selected','error');
				return;
			}
			obj.dhx_win({ id:id,height:250,width:550,header:'Upload Contact',file_l:'vas/sms/push_c/pushView/uploadAddress'});
		}
		else if(obj.prev_id == 'sender_id'){
			obj.dhx_win({ id:id,height:270,width:400,header:'Upload Sender ID',file_l:'vas/sms/push_c/pushView/uploadSenderId'});
		}
		else if(obj.prev_id == 'upload'){
			//console.log(obj.tree['uploadkeyTree'].getSelectedItemId());
			if(obj.tree['uploadkeyTree'].getSelectedItemId()=='' || obj.tree['uploadkeyTree'].getSelectedItemId()=='keys'){
				obj.message_show('** Warning : Select Keys first','error');
				return;
			}
			obj.dhx_win({ id:id,height:395,width:510,header:'Upload Data',file_l:'vas/sms/pull_c/pullView/uploadData'});
		}
		
		
	}
	else if( id =='search'){
		if(obj.prev_id == 'sender_id'){
			obj.dhx_win({ id:id,height:270,width:350,modal:false,header:'Search Sender ID',file_l:'vas/sms/common_c/load_View/searchSenderId'});
		}
		else if(obj.prev_id == 'keys'){
			obj.dhx_win({ id:id,height:270,width:350,modal:false,header:'Search Sender ID',file_l:'vas/sms/common_c/load_View/searchkeys'});
		}
		else if(obj.prev_id == 'userList'){
			obj.dhx_win({ id:id,height:320,width:350,modal:false,header:'Search User',file_l:'vas/sms/common_c/load_View/userSearch'});
		}
		else if(obj.prev_id == 'creditlog'){
			obj.dhx_win({ id:id,height:200,width:300,modal:false,header:'Search Credit History',file_l:'vas/sms/common_c/load_View/creditlog'});
		}
		else if(obj.prev_id == 'sentbox'){
			obj.dhx_win({ id:id,height:290,width:300,modal:false,header:'Search Sent History',file_l:'vas/sms/common_c/load_View/sentbox'});
		}
		else if(obj.prev_id == 'smsreport'){
			obj.dhx_win({ id:id,height:170,width:320,modal:false,header:'Search SMS Transaction',file_l:'vas/sms/common_c/load_View/smsreport'});
		}
		else if(obj.prev_id == 'detailsPull'){
			obj.dhx_win({ id:id,height:240,width:298,modal:false,header:'Search Pull Details',file_l:'vas/sms/common_c/load_View/detailpull'});
		}
		else if(obj.prev_id == 'upload'){
			if(obj.tree['uploadkeyTree'].getSelectedItemId()==''){
				obj.message_show('** Select IS keys List first','error');
				return;
			}
			obj.dhx_win({ id:id,height:240,width:328,modal:false,header:'Search Uploaded Data',file_l:'vas/sms/common_c/load_View/uploadData'});
		}
		
	}
	else if( id =='todayReport'){
		if(obj.prev_id =='detailsPull'){
			obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/report_c/detailPull?today=today');
		}else{
			obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/report_c/renderTodayReport/'+obj.prev_id,function(e){
				if(obj.prev_id==='creditlog'){
					var res = obj.dhx_ajax('vas/sms/report_c/sumCreditlog');
					if(res!=='none'){
						res = JSON.parse(res);
						var detail=[];
						var keys = Object.keys(res);
						for( i=0;i<keys.length; i++){
							if(keys[i]==1)	detail.push('<span style="color:blue;">Total Alloted :</span> '+res[keys[i]]);
							else if(keys[i]==2)	detail.push('<span style="color:blue;">Total Deduced :</span> '+res[keys[i]]);
						}
						$('#extraData').empty().append('<p>'+detail.join(' || ')+'</p>');
					
					}
				}
			});
			
		}
		
		
	}
	
	else  if( id =='excel'){
		var res = obj.dhx_ajax('vas/sms/common_c/rdyDownload');
		if(res=='fail' && res ==''){  obj.message_show('**Error Found in download' ,'error');} 
		if(obj.prev_id == 'sender_id'){
			
			window.open('vas/sms/push_c/renderSenderId/download?id='+res+'&'+obj.searchQuery,'_blank');
		}
		else if(obj.prev_id == 'creditlog'){
			if(obj.toolbar_id == 'todayReport'){
				window.open('vas/sms/report_c/renderCredit/download?object=grid&type=today','_blank');
			}else{
				window.open('vas/sms/report_c/renderCredit/download?object=grid&'+obj.searchQuery,'_blank');
			}
			
		}
		
	}
	else if( id =='approve' || id == 'disapprove'){
		if(obj.prev_id == 'sender_id'){
			var selId = obj.getSelected('dhxDynFeild','multiple');
			if(selId == null) return;
			var res = obj.dhx_ajax('vas/sms/push_c/senderIdOperate/'+id,'id='+selId);
			if(res == 'sucess'){ 
				obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderSenderId/search?object=load&'+obj.searchQuery);
				obj.message_show('Operation Sucess');
			}
			else obj.message_show(res ,'error');
		}
	}
	else if( id =='enable' || id == 'disable'){
		if(obj.prev_id == 'keys'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			var res = obj.dhx_ajax('vas/sms/pull_c/operateKeys/'+id+'/'+selId);
			if(res == 'sucess'){ 
				obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/pull_c/renderKeys/operate?object=load&'+obj.searchQuery);
				obj.message_show('Key has been '+id+'d sucessfully');
			}
			else obj.message_show(res ,'error');
		}
		else if(obj.prev_id == 'scheduler' || obj.prev_id == 'cron'){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			var res = obj.dhx_ajax('vas/sms/push_c/queJobOperation/'+id+'/'+selId);
			if(res == 'sucess'){ 
				if(obj.prev_id == 'scheduler') obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderSchduler?object=load');
				else if(obj.prev_id == 'cron') obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderQueue?object=load');
				obj.message_show(id.toLowerCase()+'d Sucessfully');
			}
			else obj.message_show(res ,'error');
		}
		/*else if(){
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			var res = obj.dhx_ajax('vas/sms/push_c/queJobOperation/'+id+'/'+selId);
			if(res == 'sucess'){ 
				obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderSchduler?object=load');
				obj.message_show(id.toLowerCase()+'d Sucessfully');
			}
			else obj.message_show(res ,'error');
		}*/
	}
	if(id !='excel')obj.toolbar_id = id;
// end of the toolbar event
});