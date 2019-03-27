<!-- <img src="vas/images/load/searchadd.png" style="height:20px; vertical-align:bottom;"/> -->

<?php

// exit;
$this->load->view('layout/header.php');
// exit;
	// echo"dashboard view";
?>
<section id="mainSection">
    <div id="mainWrapper">
        <div id="ribbon"></div>
        <div id="toolbar" style="margin-top:5px;"></div>
        <div id="main_feild" style="width:930px;  margin:10px auto;">
        	<div id="ribbonState" class="ribbonUp"></div>
            <div id="dhxDynFeild">

            </div>
            <div id="extraData">
            </div>
        </div>
    </div>
</section>
<script language="javascript" type="text/javascript">
// console.log("awsome point");
	Object.defineProperty(Array.prototype, 'chunk', {
		value: function(chunkSize) {
			var R = [];
			for (var i=0; i<this.length; i+=chunkSize)
				R.push(this.slice(i,i+chunkSize));
			return R;
		}
	});
	var InitObj = function(xmlData){
		this.fromDate      	= null;
		this.tillDate	  	= null;
		this.prev_id 		= null;
		this.toolbar_id 	= null;
		this.excludeNumber  = [];
		this.indi_toolbar   = [];
		this.verifiedNumber = null;
		this.searchQuery    = '';
		this.priv 			= ("<?php echo implode(',',$priv); ?>").split(',');
		this.ribbonJson 	= <?php echo $ribbon;?>;
		this.toolbarXml 	= '<?php echo $toolbar;?>';
		this.color 			= ["#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00","#00ffff","#f0ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b","#8b008b","#556b2f","#8b0000","#e9967a","#9400d3","#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff","#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000","#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000","#c0c0c0","#ffffff","#ffff00"];
		var ths = this;
		$.each($(this.toolbarXml).children('toolbar item'),function(i,v){
			ths.toolbarIdList.push($(this).attr('id'));
		});
		ths.toolbarIdList.push('pullCombo');
		ths.toolbarIdList.push('errpullCombo');
		ths.toolbarIdList.push('from_calander');
		ths.toolbarIdList.push('till_calander');

	}
	InitObj.prototype= new Ucr_dhx();
	/****** address book initilization function *****************/
	InitObj.prototype.randomColor = function (numOfSteps, step) {
		// This function generates vibrant, "evenly spaced" colours (i.e. no clustering). This is ideal for creating easily distinguishable vibrant markers in Google Maps and other apps.
		// Adam Cole, 2011-Sept-14
		// HSV to RBG adapted from: http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
		var r, g, b;
		var h = step / numOfSteps;
		var i = ~~(h * 6);
		var f = h * 6 - i;
		var q = 1 - f;
		switch(i % 6){
			case 0: r = 1; g = f; b = 0; break;
			case 1: r = q; g = 1; b = 0; break;
			case 2: r = 0; g = 1; b = f; break;
			case 3: r = 0; g = q; b = 1; break;
			case 4: r = f; g = 0; b = 1; break;
			case 5: r = 1; g = 0; b = q; break;
		}
		var c = "#" + ("00" + (~ ~(r * 255)).toString(16)).slice(-2) + ("00" + (~ ~(g * 255)).toString(16)).slice(-2) + ("00" + (~ ~(b * 255)).toString(16)).slice(-2);
		return (c);
	}
	InitObj.prototype.treeSearch = function(id){

		if(this.tree[id].findItem($('#searchadd').val(),0,1)==null ){
			this.message_show('** : No. Result found in list');
		}
		return false;
	}
	InitObj.prototype.count_msg = function( message, ms_type){
		var size = 0;
		var len = message.length;
		var msg_len = 0;
		if     ( (ms_type == 2 || ms_type == 4) && len <= 70 ) size = 70;
		else if( (ms_type == 2 || ms_type == 4) && len > 70  ) size = 67;
		else if( (ms_type == 1 || ms_type == 3) && len <= 160) size = 160;
		else if( (ms_type == 1 || ms_type == 3) && len > 160 ) size = 153;

		var char_len= ( len-(parseInt(len/size)*size));

		if(char_len!=0){
			msg_len = parseInt((len+size)/size);
		}
		else{
			msg_len = parseInt((len)/size);
		}
		var detail ={ messageLength: msg_len};
		if(char_len!=0){ detail.charLength =size- char_len;}
		else{ detail.charLength = char_len; }
		return 	detail;

	};
	InitObj.prototype.addressBook = function (id){
		var ths=this;
		$('#'+id).empty().append('<div id="addressTree" class="floatLeft"><p><input id="searchadd" placeholder="search addressbook" style="width:130px; padding:3px; font-size:11px; margin-right:10px;"/><a href="#" onClick="return obj.treeSearch('+"'addressbookTree'"+');"><img src="assets/images/searchadd.png" style="height:20px; vertical-align:bottom;"/></a></p><div id="addressbookTree" style="margin-top:10px;"></div></div><div id="addressGrid" class="floatRight"></div><div class="clearBoth" style="height:0;"></div>');

		ths.dhx_tree({parent:'addressbookTree'});
		ths.tree['addressbookTree'].attachEvent('onClick',function( id ){
			if( id== 'books' ) return;

			ths.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+id );
		});
		ths.create_dhx_grid({
			p_id				: "addressGrid",
			setHeader			: "Name ,Mobile Number,Operator",
			attachHeader		: "#text_filter,#text_filter,#text_filter",
			setInitWidths		: "328,202,150",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"name,cel,ope"',
			enableEditEvents	: 'true,false,true',
			pageSize			: 11,
			multi_select		: true,

		});
		ths.tree['addressbookTree'].load('push_c/renderAddressbook?object=tree',function(){
			if(ths.tree['addressbookTree'].getUserData( 'session','session')!== undefined ){
				ths.session_expire(ths.tree['addressbookTree'].getUserData( 'session','session'));
			}
		});
		ths.showItem_toolBar('toolbar',['newaddressbook','newcontact','upload','detail','aedit','adelete','empty','excecl']);

	}
	InitObj.prototype.uploadDb = function (id){
		var ths=this;
		$('#'+id).empty().append('<div id="addressTree" class="floatLeft"><p><input id="searchadd" placeholder="search keys" style="width:130px; padding:3px; font-size:11px; margin-right:10px;"/><a href="#" onClick="return obj.treeSearch('+"'uploadkeyTree'"+');"><img src="assets/images/searchadd.png" style="height:20px; vertical-align:bottom;"/></a></p><div id="uploadkeyTree" style="margin-top:10px;"></div></div><div id="uploadGrid" class="floatRight"></div><div class="clearBoth" style="height:0;"></div>');

		ths.dhx_tree({parent:'uploadkeyTree'});
		ths.create_dhx_grid({
			p_id				: "uploadGrid",
			setHeader			: "Unique ID ,Message,Count/Type",
			//attachHeader		: "#text_filter,#text_filter,#text_filter",
			setInitWidths		: "120,400,160",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"id,msg,count"',
			enableEditEvents	: 'true,false,true',
			pageSize			: 6,
			multi_select		: true,
		});
		ths.tree['uploadkeyTree'].load('pull_c/renderKeyTree?object=tree',function(){
			if(ths.tree['uploadkeyTree'].getUserData( 'session','session')!== undefined ){
				ths.session_expire(ths.tree['uploadkeyTree'].getUserData( 'session','session'));
			}

		});
		ths.showItem_toolBar('toolbar',['upload','empty','search']);
		ths.tree['uploadkeyTree'].attachEvent('onClick',function( id ){
			if( id== 'keys') return;
			ths.grid['uploadGrid_t'].clearAndLoad( "pull_c/renderUpload/"+id);
		});
	}
	InitObj.prototype.scheduler = function (id){
		this.showItem_toolBar('toolbar',['newscheduler','enable','disable','edit','delete']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Event Name ,Schedule Date,Message,Cell Numbers,Sender ID,Created On,State",
			attachHeader		: "#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter",
			setInitWidths		: "115,130,225,120,110,130,100",
			setColAlign			: "left,left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"name,date,msg,msgc,num,rec,cre,state"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderSchduler'
		 });
	}
	InitObj.prototype.MsgTemplate = function (id){
		this.showItem_toolBar('toolbar',['newtemplate','edit','delete']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Name ,Message,Message Count/Type,Created On",
			attachHeader		: "#text_filter,#text_filter,#text_filter,#text_filter",
			setInitWidths		: "240,370,140,180",
			setColAlign			: "left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt',
			setColumnIds		: '"name,msg,msgc,cre"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderTemplate'
		 });
	}
	InitObj.prototype.userGrid = function (id,user){
		if(this.ribb['ribbon'].getValue('combo_st')==1){
			this.showItem_toolBar('toolbar',['newuser','usersuspend','detail','search','pedit']);
		}
		else{
			this.showItem_toolBar('toolbar',['newuser','userapprove','detail','search','pedit']);
		}
		<?php if($isAdmin =='admin'){?>
			var header = "User ID,Type/Feature,Name,User Reseller,Contact Person,Contact No.,Balance,Country,Status";
			var width = "60,100,148,148,150,90,115,65,54";
		<?php }else{ ?>
			var header = "User ID,Type/Feature,Name,Contact Person,Contact No.,Balance,Country,Status";
			var width = "60,100,246,170,100,120,70,64";
		<?php }?>
		this.searchQuery = '';
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"id,name,rese,person,ph,type,bal,coun,stat"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'userManage_c/renderUser/'+( (this.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend')
		 });

	}
	InitObj.prototype.queJob = function (id,user){
		<?php if($isAdmin =='admin'){?>
			var header = "Sent By,Reseller Name,Sender ID,Message,Type/Count,Cell Numbers,Que Date,State";
			var width = "110,110,90,190,80,100,130,120";
			this.showItem_toolBar('toolbar',['enable','disable','delete','disselect','search']);
		<?php }elseif(in_array('USER_MANAGE', $priv)){ ?>
			var header = "Sent By,Sender ID,Message,Type/Count,Cell Numbers,Que Date,State";
			var width = "160,100,210,100,100,140,120";
			this.showItem_toolBar('toolbar',['enable','disable','delete','disselect','search']);
		<?php }elseif(in_array('PUSH', $priv)){?>
			var header = "Sender ID,Message,Type/Count,Cell Numbers,Que Date,State";
			var width = "120,270,100,140,150,150";
			this.showItem_toolBar('toolbar',['enable','disable','delete','disselect']);
		<?php }?>

		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"ruse,use,sender,mes,typ,numb,sch,state"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderQueue'
		 });
	}
	InitObj.prototype.gateway = function (id){
		<?php if($isAdmin =='admin'){?>
		var header = "Name,User Name,Operator,Host Name,SMSC ID,Port,Priority";
		var width = "150,150,100,240,100,90,100";
		this.showItem_toolBar('toolbar',['newgateway','edit','delete']);
		<?php }else{?>
		var header = "Name,Operator";
		var width = "400,530";
		this.showItem_toolBar('toolbar');
		<?php }?>
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"name,usr,oper,hname,smsc,port,prio"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderGateway'
		 });
	}
	InitObj.prototype.senderid = function (id){
		<?php if($isAdmin =='admin'){?>
			var header = "Request By/Reseller,Sender ID,Description,Operator/Gateway,State,Date,Priority";
			var width = "200,100,200,140,95,130,65";
			this.showItem_toolBar('toolbar',['newsenderid','approve','disapprove','select','disselect','search','excel','delete']);
		<?php }elseif(in_array('USER_MANAGE', $priv)){?>
			var header = "Request By,Sender ID,Operator/Gateway,State,Date";
			var width = "300,130,200,150,150";
			this.showItem_toolBar('toolbar',['disapprove','disselect','search','excel']);
		<?php }elseif(in_array('PUSH', $priv) ){ ?>
			var header = "Sender ID,Gateway/Operator,State,Description,Date";
			var width = "140,200,160,260,170";
			this.showItem_toolBar('toolbar',['newsenderid','upload','select','disselect','search','excel','delete']);
		<?php }?>
		this.searchQuery = '';
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"reqby,resel,send,oper,stat,date,prio"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderSenderId'
		 });

	}
	InitObj.prototype.package = function (id){
		this.showItem_toolBar('toolbar',['newpackage','edit','delete']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Name,Amount,Description,Package Type",
			setInitWidths		: "250,150,400,200",
			setColAlign			: "left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt',
			setColumnIds		: '"name,amt,des,pack"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
		 });
	}
	InitObj.prototype.shortcode = function (id){
		<?php if($isAdmin =='admin'){?>
		var header = "Shortcode,Description,Assign To";
		var width = "250,300,380";
		this.showItem_toolBar('toolbar',['newshortcode','edit','delete','assignDetail']);
		<?php }else{?>
		var header = "Shortcode,Description";
		var width = "250,680";
			<?php if(in_array('USER_MANAGE', $priv)){?>
			this.showItem_toolBar('toolbar',['assignDetail']);
			<?php } else{?>
			this.showItem_toolBar('toolbar');
			<?php } ?>
		<?php }?>

		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"stc,des,name"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'pull_c/renderShortcode'
		 });
	}
	InitObj.prototype.category = function (id){
		this.showItem_toolBar('toolbar',['newcategory','edit','delete']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Name,Description,Data Upload",
			setInitWidths		: "250,530,150",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"name,des,upload"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'pull_c/renderCategory'
		 });
	}
	InitObj.prototype.smsMsgTemplate = function (id){
		this.showItem_toolBar('toolbar',['newmsgtemplate','delete']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Sender ID,Operator,Template,Data",
			setInitWidths		: "150,130,500,150",
			setColAlign			: "left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt',
			setColumnIds		: '"name,des,upload,date"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'push_c/renderMsgTemplate'
		 });
	}
	InitObj.prototype.key = function (id){
		<?php if($isAdmin =='admin'){?>
			var header = "Key,User,User Reseller,Shortcode,Category,Created on,Status";
			var width = "100,195,195,80,80,140,140";
			this.showItem_toolBar('toolbar',['enable','disable','detail','delete','search']);

		<?php }elseif( in_array('USER_MANAGE', $priv) ){?>
			var header = "Key,User,Shortcode,Category,Created on,Status";
			var width = "150,220,180,100,140,140,";
			this.showItem_toolBar('toolbar',['enable','disable','detail','search']);
		<?php }elseif(in_array('PULL', $priv) ){ ?>
			var header = "Key,Shortcode,Category,Created on,Status";
			var width = "200,175,175,240,140";

			this.showItem_toolBar('toolbar',['newkey','enable','disable','detail','search','delete']);

		<?php }?>

		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"key,usr,usrre,stc,cat,creon,stat"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'pull_c/renderKeys'
		 });

	}
	InitObj.prototype.group = function (id){
		this.showItem_toolBar('toolbar',['newgroup','edit','delete','disselect']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Name,Description,Assign Privileges",
			setInitWidths		: "130,250,550",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"name,des,assign"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'sysManage_c/renderGroup'
		 });
	}
	InitObj.prototype.prefix = function (id){
		this.showItem_toolBar('toolbar',['newprefix','edit','delete','disselect']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "cell Prefix,Operator,Description",
			setInitWidths		: "150,300,480",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"name,pre,des"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'sysManage_c/renderPrefix'
		 });
	}
	InitObj.prototype.operator = function (id){
		this.showItem_toolBar('toolbar',['newoperator','edit','delete','disselect']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Operator Acronym,Description,Country",
			setInitWidths		: "200,430,300",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"acr,des,cou"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'sysManage_c/renderOperator'
		 });
	}
	InitObj.prototype.feature = function (id){
		this.showItem_toolBar('toolbar',['newfeature','edit','delete','disselect']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Feature,Description",
			setInitWidths		: "300,630",
			setColAlign			: "left,left",
			setColTypes			: 'txt,txt,',
			setColumnIds		: '"nam,des"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'sysManage_c/renderFeature'
		 });
	}
	InitObj.prototype.country = function (id){
		this.showItem_toolBar('toolbar',['newcountry','edit','delete','disselect']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Country Name, Acronym,Country Code",
			setInitWidths		: "530,200,200",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"name,acr,cod"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			: 'sysManage_c/renderCountry'
		 });
	}
	InitObj.prototype.transaction = function (id){
		this.showItem_toolBar('toolbar',['todayReport','search']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Operator, Total Units,Date",
			setInitWidths		: "250,190,490",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"oper,unit,date"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			//enable_Paging		:false,
		 });
	}
	InitObj.prototype.creditLog = function (id){
		this.showItem_toolBar('toolbar',['todayReport','search','excel']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Operator, Transaction Unit,Type/Description,Remaining Balance,Date",
			setInitWidths		: "100,115,450,125,140",
			setColAlign			: "left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt',
			setColumnIds		: '"ope,unit,type,bal,date"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			//xml_link			: 'report_c/renderCredit'
		 });
	}
	InitObj.prototype.sendBox = function (id,priv){
		this.showItem_toolBar('toolbar',['todayReport','detail','search','excel_new']);
		<?php if($isAdmin =='admin' ){?>
			var header = "Send By/Reseller,Sender ID,Message,Cell No.,Date,User Data";
			var width = "200,110,245,115,135,125";
		<?php }elseif(in_array('USER_MANAGE', $priv) ){?>
			var header = "Send By, Sender ID,Message,Cell No.,Date,User Data";
			var width = "200,115,250,114,135,115";
		<?php }else{?>
			var header = "Sender ID,Message,Type/Count,Cell No.,Date,User Data";
			var width = "120,330,100,115,135,130";
		<?php }?>
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: header,
			setInitWidths		: width,
			setColAlign			: "left,left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"send,senderid,msg,type,cell,date,userd"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			pageSize			: 10,
		 });
	}
	InitObj.prototype.dailyReport = function (id,priv){
		this.showItem_toolBar('toolbar');
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "User, Transaction Detail",
			setInitWidths		: "300,630",
			setColAlign			: "left,left",
			setColTypes			: 'txt,txt',
			setColumnIds		: '"user,detail"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			xml_link			:'report_c/renderTodayReport/dailyreport'
		 });
	}
	InitObj.prototype.detailLog = function (id){
		this.showItem_toolBar('toolbar',['todayReport','search']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Operator,Cell Number, Shortcode,Text,Type/Count,Date",
			setInitWidths		: "100,120,120,310,120,160",
			setColAlign			: "left,left,left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt,txt',
			setColumnIds		: '"cell,send,shr,txt,typ,date"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			//xml_link			:'report_c/detailPull'
		 });
	}
	InitObj.prototype.blockedNumber = function (id){
		this.showItem_toolBar('toolbar',['newblocknumber','delete','search']);
		this.create_dhx_grid({
			p_id				: id,
			setHeader			: "Cell Numbers, Operator, Date",
			setInitWidths		: "550,170,210",
			setColAlign			: "left,left,left",
			setColTypes			: 'txt,txt,txt',
			setColumnIds		: '"cell,send,shr"',
			enableEditEvents	: 'true,false,true',
			multi_select		: true,
			//xml_link			:'report_c/detailPull'
		 });
	}
	InitObj.prototype.scheme = function (id,priv){
		this.showItem_toolBar('toolbar',['newscheme','delete']);
		var ele = '<div id="scheme" class="floatLeft"></div><div id="SchemeDetail" class="floatRight"><h3>Scheme Detail</h3><div></div></div class="clearBoth"></div>';
		$('#'+id).empty().append(ele);

		this.create_dhx_grid({
			p_id				: 'scheme',
			setHeader			: "Scheme Name, Created On,scheme,detail",
			attachHeader		: "#text_filter,#text_filter,#text_filter,#text_filter",
			setInitWidths		: "405,160,100,100",
			setColAlign			: "left,left,left,left",
			setColTypes			: 'txt,txt,txt,txt,txt',
			setColumnIds		: '"creby,name,creon,sche,detail"',
			enableEditEvents	: 'false,false,false',
			xml_link			: 'pull_c/renderScheme'
		 });
		 this.grid['scheme_t'].setColumnHidden(2,true);
		 this.grid['scheme_t'].setColumnHidden(3,true);
		 //////////// dhtmlx shcmeme grid event
		var ths = this;
		ths.grid['scheme_t'].attachEvent("onRowSelect", function(id,ind){
			var shcmes = (ths.grid['scheme_t'].cells(id,2).getValue()).toString();
			var detail = (ths.grid['scheme_t'].cells(id,3).getValue()).toString();
			shcmes = shcmes.split('_');
			detail = detail.split("\n");
			var ele = '<div><ul><li>';
			for(i=0; i< shcmes.length; i++){
				var itm = shcmes[i].split('#');
				if(itm[0].toLowerCase()=='identity')
					ele = ele + '<li></br><span class="line">IDENTITY -></span><span>'+itm[1]+'</span><span class="itemsType"> [ '+itm[0].toUpperCase()+' ]</span></li>';
				else
					ele = ele + '<li><span class="line">LINE '+(i+1)+' -></span><span>'+itm[1]+'</span><span class="itemsType"> [ '+itm[0].toUpperCase()+' ]</span></li>';
			}
			ele = ele + '</ul></div><div><h3>Sample Message</h3>';
			for(i=0; i<detail.length; i++){
				ele = ele + '<p>'+detail[i]+'</p>';
			}
			ele = ele + '</div></div>';
			$('#SchemeDetail > div').empty().append(ele);
			return true;
		});
	}
	InitObj.prototype.dynamicGrid = function (data){
		//this.showItem_toolBar('toolbar');
		var arr = data.header.split(',');
		var attachHeader = [],setColAlign = [],setColTypes = [],setColumnIds = [];
		for(i=0; i<arr.length; i++){
			attachHeader.push('#text_filter');
			setColAlign.push('left');
			setColTypes.push('txt');
			setColumnIds.push(i.toString());
		}
		var gridobj = {
			p_id				: data.id,
			setHeader			: data.header,
			setInitWidths		: data.setInitWidths,
			setColAlign			: setColAlign.join(','),
			setColTypes			: setColTypes.join(','),
			setColumnIds		: setColumnIds.join(',')	,
			enableEditEvents	: 'true,false,true',
			pageSize			: data.pageSize,
			multi_select		: true,
			multiple 			: data.multiple || 'single',
			pageSize			: data.pageSize        || 13,
			pagesInGrp			: data.pagesInGrp  	  || 10,
		 }
		 if(attachHeader==undefined || attachHeader==true) gridobj.attachHeader = attachHeader.join(',');
		this.create_dhx_grid(gridobj);
	}
	InitObj.prototype.dhx_win = function(obj){
		this.create_dhx_window({
			winId				: obj.id,
			height				: obj.height,
			width				: obj.width,
			headerText			: obj.header,
			modal				: (obj.modal===undefined)?true : obj.modal,
			clear_load			: obj.clear_l || null,
			load_file			: obj.file_l || null,
			win_num				: obj.win_nums || 'single',
			gridId				: obj.gridId || null ,
			multiple			: obj.multiple || 'single'
		});

	}
	InitObj.prototype.getSelected = function(gridId,type){
		var type = type || 'single';
		var id = this.grid[gridId+'_t'].getSelectedRowId();
		if(id==null){
			this.message_show('No. Item has been Selected','error');
			return;
		}
		if(id.split(',').length >1 && type == 'single'){
			this.message_show('Invalid Multiple selection');
			return;
		}
		return id;

	}
	InitObj.prototype.addRemoveObj = function (id){
		if(id == "addressbook"){
			this.addressBook('dhxDynFeild');
		}
		else if(id == "scheduler"){
			this.scheduler('dhxDynFeild');
		}
		else if(id == "template"){
			this.MsgTemplate('dhxDynFeild');
		}
		else if(id == "userList"){
			this.userGrid('dhxDynFeild');
		}
		else if(id == "cron"){
			this.queJob('dhxDynFeild');
		}
		else if(id == "gateway"){
			this.gateway('dhxDynFeild');
		}
		else if(id == "sender_id"){
			this.senderid('dhxDynFeild');
		}
		else if(id == "package"){
			this.package('dhxDynFeild');
		}
		else if(id == "shortcode"){
			this.shortcode('dhxDynFeild');
		}
		else if(id == "category"){
			this.category('dhxDynFeild');
		}
		else if(id == "keys"){
			this.key('dhxDynFeild');
		}
		else if(id == "group"){
			this.group('dhxDynFeild');
		}
		else if(id == "prefix"){
			this.prefix('dhxDynFeild');
		}
		else if(id == "operator"){
			this.operator('dhxDynFeild');
		}
		else if(id == "feature"){
			this.feature('dhxDynFeild');
		}
		else if(id == "country"){
			this.country('dhxDynFeild');
		}
		else if(id == "smsreport"){
			this.transaction('dhxDynFeild');
		}
		else if(id == "creditlog"){
			this.creditLog('dhxDynFeild');
		}
		else if(id == "sentbox"){
			this.sendBox('dhxDynFeild');
		}
		else if(id == "dailyreport"){
			this.dailyReport('dhxDynFeild');
		}
		else if(id == "scheme"){
			this.scheme('dhxDynFeild');
		}
		else if(id == 'upload'){
			this.uploadDb('dhxDynFeild');
		}
		else if(id == 'detailsPull'){
			this.detailLog('dhxDynFeild');
		}
		else if(id == 'temp_msg'){
			this.smsMsgTemplate('dhxDynFeild');
		}
		else if(id == 'blocked'){
			this.blockedNumber('dhxDynFeild');
		}




	}


	InitObj.prototype.deleteFun = function(res){

		console.log("Inside delete fun");
		// this function is callback function of dhtmlx message box due to which this refers to dhtmlx component.
		if(res===true){
			var res = null;
			var selType = 'single';
			var selId = null;
			if(obj.prev_id == 'addressbook' ){
				selId = obj.tree['addressbookTree'].getSelectedItemId();
				if( selId=='' || selId == 'books'){
					obj.message_show('** Warning : No address book selected','error');
					return;
				}
				if(obj.toolbar_id =='contact_delete'){
					selId = obj.getSelected('addressGrid','multiple');
					if(selId == null) return;
				}
			}
			else if(obj.prev_id == 'scheme'){
				selId =obj.getSelected('scheme',selType);
				if(selId == null) return;
			}
			else{
				selId =obj.getSelected('dhxDynFeild',selType);
				if(selId == null) return;
			}

      // console.log("awsome point");
			if(obj.prev_id == 'country' || obj.prev_id == 'operator' || obj.prev_id == 'prefix' || obj.prev_id == 'group'|| obj.prev_id == 'feature'){
				res = obj.dhx_ajax('sysManage_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'shortcode' || obj.prev_id == 'category'){
				res = obj.dhx_ajax('pull_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'gateway'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'addressbook'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.toolbar_id,'&id='+selId );
			}
			else if(obj.prev_id == 'template'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'sender_id'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'scheme'){
				res = obj.dhx_ajax('pull_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'scheduler'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'cron'){
				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'keys'){
				res = obj.dhx_ajax('pull_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}
			else if(obj.prev_id == 'temp_msg'){

				res = obj.dhx_ajax('push_c/deleteItem/'+obj.prev_id,'&id='+selId );
			}

			if(res==='sucess'){
				obj.message_show('Item has been deleted sucessfully');

				if(obj.toolbar_id =='address_delete'){
					obj.grid['addressGrid_t'].clearAll();
					obj.tree['addressbookTree'].deleteChildItems('0');
					obj.tree['addressbookTree'].load('push_c/renderAddressbook?object=tree');

				}
				else if(obj.toolbar_id =='contact_delete'){
					obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+obj.tree['addressbookTree'].getSelectedItemId() );
				}
				else if(obj.prev_id == 'scheme'){
				obj.grid['scheme_t'].deleteSelectedRows();
				}
				else{
					obj.grid['dhxDynFeild_t'].deleteSelectedRows();
				}

			}
			else{
				obj.message_show(res,'error');
			}
		}
	}
	InitObj.prototype.truncate = function(res){
		if(res===true){
			if(obj.prev_id == 'addressbook'){
				var bookid = obj.tree['addressbookTree'].getSelectedItemId();
				res = obj.dhx_ajax('push_c/emptyContact/'+bookid);
				if(res=='sucess') obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+bookid );
			}
			else if(obj.prev_id == 'upload'){
				var upid = obj.tree['uploadkeyTree'].getSelectedItemId();
				res = obj.dhx_ajax('pull_c/truncateUpload/'+upid);
				if(res=='sucess') obj.grid['uploadGrid_t'].clearAndLoad( "pull_c/renderUpload/"+upid);
			}

			if(res=='sucess'){
				obj.message_show('Selected Items has been truncated sucessfully');
			}
			else obj.message_show(res,'error');
		}
	}
	InitObj.prototype.userManage = function(res){
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		if(res===true && obj.toolbar_id != null){
			var result = obj.dhx_ajax('userManage_c/manageUser/'+selId+'/'+ (( obj.toolbar_id =='usersuspend')?'suspend':'approve'));
			if(result=='sucess'){
				obj.message_show('User has been '+(( obj.toolbar_id =='usersuspend')?'suspended':'approved')+' Sucessfully');
				obj.grid['dhxDynFeild_t'].deleteSelectedRows();
			}
			else{
				obj.message_show(result,'error');
			}
		}
	}
	InitObj.prototype.calenderFuction = function(id,date){
		if(id=='fromDate') obj.fromDate = date;
		else if(id=='tillDate') obj.tillDate = date;
	}
	InitObj.prototype.initCalanderInToolbar = function(){
		obj.fromDate = null;
		obj.tillDate = null;
		$('.dhx_toolbar_dhx_skyblue div.dhx_toolbar_btn .dhxtoolbar_input').val('Select Date');
		this.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				multi:'single',
				id:'fromDate',
				dateformat:"%Y-%m-%d",
				clickEvFunction:this.calenderFuction,
				param: [ 'from_calander']
			});
		this.create_dhx_calander({ // adding calander in toolbar button :ID = tillDate
			multi:'multi',
			id:'tillDate',
			dateformat:"%Y-%m-%d",
			//time:'show',
			clickEvFunction:this.calenderFuction,
			param: ["till_calander"]
		});

	}

</script>

<script type="text/javascript">//this script is used for creating 
	



	// console.log("testing ajax in script");
	// obj = new InitObj();// initilizing the dhtmlx initializing object
	// obj.dhx_ajax('Auth/ajax_test');


	// console.log("testing ajax here");

</script>

<script type="text/javascript">
	var tabIds = [];
	obj = new InitObj();// initilizing the dhtmlx initializing object
	// obj.dhx_ajax('Auth/ajax_test');


	obj.create_dhx_ribbon({
		parent:	'ribbon',
		icon_p:	 'images/load',
		tabs: 	obj.ribbonJson
	});
	obj.ribb['ribbon']._tabbar.forEachTab(function(tab){ // geting all ribbon tab ids
		var id = tab.getId();
		tabIds.push(id);
	});
	obj.ribb['ribbon']._tabbar.tabs(tabIds[0]).setActive(); // setting first tab as active
	obj.create_dhx_toolbar({ // initilizing dhthmlx toolber
		parent:	'toolbar',
		icon_p:	 'images/load',
		xml: 	obj.toolbarXml
	});
	obj.showItem_toolBar('toolbar'); // disabling the toolbar at initial
	obj.tool['toolbar'].addText('pullCombo',5, '<div id="sortList" style="width:100px; height:18px;" margin-right="20px;">');
	obj.tool['toolbar'].addText('errpullCombo',6, '<div id="errsortList" style="width:100px; height:18px;" margin-right="20px;">');
	obj.tool['toolbar'].addText('from_calander',8, 'from &nbsp;&nbsp;<input style="font-size:11px;padding: 2px 5px;" id="from_calander" type="text" readonly/>');
	obj.tool['toolbar'].addText('till_calander',9,  'till &nbsp;&nbsp;<input style="font-size:11px;padding: 2px 5px;" id="till_calander" type="text" readonly/>');

	obj.load_ext_file('#dhxDynFeild','common_c/load_View/home');



	// this function is responsible for highlighting the clicked big icon button in ribbon
	$('div.dhxrb_big_button').on('click',function(e){
		$('div.dhxrb_big_button').removeClass('selButton');

		$(this).addClass('selButton');
	});
	// this file contains all the events related to ribbon
	<?php require ('assets/js/dhxRibbonEv.js'); ?>
	// this file contains all the events related to toolbar
	<?php require ('assets/js/dhxToolbarEv.js'); ?>
</script>

<?php $this->load->view('layout/footer.php')?>
