<div id="sendsms">
	<ul id="sendSmsMainHeader">
    	<li><a id="normalSms#" class="heighlight_sendSMS" href="#">Normal SMS</a></li>
        <li><a id="eomSms#" href="#">EOM SMS</a></li>
       
    </ul>
     <div class="clearBoth" style="height:0; "></div>
     <div id="normalSms">
     	<form id="normalSmsForm"  name="unicode">
            <div id="smsSenderid" class="floatLeft">
            	<h3>Sender ID
                <span class="allhelp">
                	<span>
                    	<p>
                        	<span class="closeHelp">x</span>a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.
                        </p>
                    </span><img src="vas/sms/images/load/allhelp.png"></span>
                </h3>		
            <div>
                <?php 
					if($operator !='none'){
						foreach($operator as $row){
							echo '<div><h4 data-val="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</h4><select><option value="none">--Select--</option>';
							if($senderid!='none'){
								foreach($senderid as $send){
									if($send->operator == $row->fld_int_id) echo '<option value="'.$send->fld_int_id.'"> '.$send->fld_chr_senderid.' </option>';
								}
							}
							echo'</select></div>';
						}
                 	}
				 ?>
                </div>
            </div>
            
            <div id="smsAddress" class="floatLeft">
            	<h3>Address Book & Cell Numbers<span class="allhelp"><span><p><span class="closeHelp">x</span>a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.</p></span><img src="vas/sms/images/load/allhelp.png"></span></h3>
                <div>
                	<div>
                        <h4>Upload Numbers</h4>
                       	<input id="uploadNumbers" type="file" style="margin:0 0 5px 0" />
                        <p id="msgUpNumberSch" style=" font-size:11px;"></p>
                    </div>
                	<div>
                        <h4>Insert Numbers</h4>
                       	<textarea value="" style="width:98%; height:90px; font-size:12px;" id="schNumbers" ></textarea>
                    </div>
                    
                    <div>
                        <h4>Select Address Book</h4>
                        <select id="aadbooklists">
                            <option value="none">--Select--</option>
                            <?php 
								if($addressbook !='none'){
									foreach($addressbook as $row){
										echo '<option value="'.$row->fld_int_id.'">'.ucwords($row->fld_chr_name).'</option>';
									}
								}
							?>
                        </select>
                    </div>
                    <div id="addbookfeild" style="padding:8px; " >
                        <ul>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="smsMessage" class="floatLeft">
            	<h3>Messages<span class="allhelp"><span><p><span class="closeHelp">x</span>a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.</p></span><img src="vas/sms/images/load/allhelp.png"></span></h3>
            	<div>
                	<div>
                	 <h4>Send Process Type</h4>
                    Default : <input type="radio" checked name="procesType" value="default"/> &nbsp;&nbsp;&nbsp; Quick<input type="radio" name="procesType" value="quick"/>
                </div>
                    <div>
                        <h4>Select SMS Template</h4>
                        <select id="selTemp">
                            <option value="none">--Select--</option>
                            <?php 
								if($template !=='none'){
									foreach($template as $row){
										echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_title.'</option>';
									}
								}
							?>
                        </select>
                    </div>
                    <p> MESSAGE Type &nbsp;&nbsp;
                    <select  id="nromalMsgtype" >
                            <option value="1">Text</option>
                            <option value="2">Unicode</option>
                            <option value="3">Text Flash</option>
                            <option value="4">Unicode Flash</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp; Count : <span id="messageCount">0</span> ( <span id="letterCount">0</span> )
                    </p>
                    <textarea  name="nepbox" class="unicode" id="message" name="message" value="" style="font-size:12px;"> </textarea>
                </div> 
            </div>
            <div class="clearBoth" style="height:0; border:none; padding:0;"></div>
            <div><input type="reset" class="button" value="Reset"/><input  class="button" type="submit" value="Send"/></div>
        </form>
     </div>
     <div id="eomlSms">
     	<div>
        	
        	<h3>Upload Messages</h3>
            <form id="eom">
            <p><span style="color:#0078d7;">EOM Type : </span>
            <input type="radio" value="heom"  name="eomType"/><span>Header</span>
            <input type="radio" value="eom" name="eomType"/><span>Non Header</span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="color:#0078d7;">Message Type : </span>&nbsp;&nbsp;<select id="eomMsgType">
            <option value="none">--Select--</option>
            <option value="1">Text</option>
            <option value="2">Unicode</option>
            <option value="3">Text Flash</option>
            <option value="4">Unicode Flash</option>
            </select> &nbsp;&nbsp;|&nbsp;&nbsp;
            <span  style="color:#0078d7;">Upload ( CSV ( ms-dos ) ) : </span><input style="width:200px;" type="file" name="eomMessage" id="eomMessage"/></p>
            </form>
        </div>
        
        <div id="eomSenderid">
        	<h3>Sender ID</h3>
            <div>
            <?php 
				if($operator !='none'){
					foreach($operator as $row){
						echo '<div><h4 data-val="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</h4><select><option value="none">--Select--</option>';
						if($senderid!='none'){
							foreach($senderid as $send){
								if($send->operator == $row->fld_int_id) echo '<option value="'.$send->fld_int_id.'"> '.$send->fld_chr_senderid.' </option>';
							}
						}
						echo'</select></div>';
					}
				}
			 ?>
             </div>
        </div>
        <div id="afterUpload" class="displayOff">
        	<div id="dataupload"><div id="uploaded"></div></div>
            <p><input form="eom" type="reset" value="Reset" id="resetUploadeom" class="button"/><input style="margin-left:10px;" type="submit" value="Submit" id="submitUploadeom" class="button"/></p>
        </div>
        <div id="eomInfo"  >
        	<h3>What is EOM ?</h3>
            <p>Eom is event oriented message , which allows the users to send unique messages to each designated recivers . if the users has significant amount of unique message respect to receivers then it reduces the burden of sending individual message each time seperately.</p>
<p>If you want to communicate with a large number of people at once but want to give specific information to specific people then EOM can help you. Individual get what is relevent to them. No need to mannually send single SMS to single number.
</p>
        </div>
        <div id="eomInfo"  >
        	<h3>How to send EOM Message ?</h3>
            <p>EOM provides you two option for uploading messages .Select as per requirment.</p>
            <ul>
            	<li>With Header : this allows you to send seperate messages with heading <img src="vas/sms/images/load/with_header.JPG"/>
                </li>
            </ul>
        </div>
     </div>
</div>
<script language="javascript" type="text/javascript">
/***********playing animation for sliding down the pages***********/
(function(){
var msgState=false;
obj.excludeNumber = [];
obj.verifiedNumber = null;
var gridTxt ={rows:[]}; 
$('#aadbooklists').change(function(e) {
    if($(this).val()!='none'){
		var txt = $(this).children('option:checked').text();
		var val = $(this).val();
		var exist = false;
		if($('#addbookfeild ul li').length > 0){
			$('#addbookfeild ul li').each(function(index, element) {
				if($(this).data('val') == val){
					obj.message_show('**Warning: Addressbook already selected','error');
					exist = true;
				}
			});
		}
		if(exist == false)
		$('#addbookfeild ul').append('<li data-val="'+$(this).val()+'">'+txt+'<span class="verify">VERIFY</span><span class="close">x</span></li>');
	}
});
$('#selTemp').change(function(e) {
	if($(this).val() !='none'){
		var res = obj.dhx_ajax('vas/sms/common_c/getDetail/template/'+$(this).val() ); 
		if(res!=='none'){
			res = JSON.parse(res);
			var msg_length  = obj.count_msg(res[0].fld_chr_msg,res[0].fld_int_mst);
			$('#messageCount').empty().append(msg_length.messageLength);
			$('#letterCount').empty().append(msg_length.charLength);
			$('#nromalMsgtype').val(res[0].fld_int_mst);
			if(res[0].fld_int_mst==1 || res[0].fld_int_mst==3){
				$('#message').val('').removeAttr('onkeyup');
			} 
			else if(res[0].fld_int_mst==2 || res[0].fld_int_mst==4){
				$('#message').val('').attr('onkeyup',"conversion();");
			} 
			$('#message').val(res[0].fld_chr_msg)
			console.log(msg_length);
		}
		else{
			obj.message_show('**Error : unable to locate template','error');
			return false;
		}
	}
	else{
		$('#messageCount').empty().append('0');
		$('#letterCount').empty().append('0');
		$('#message').val('');
		$('#nromalMsgtype').val('1');
	}
});
$('#sendSmsMainHeader li a').on('click',function(e){
	$(this).addClass('heighlight_sendSMS').parent('li').siblings('li').children('a').removeClass('heighlight_sendSMS');
	if($(this).attr('id')== 'normalSms#'){ $('#normalSms').slideDown(300); }
	else{ $('#normalSms').slideUp(300);  }
	e.preventDefault();
});
$('#resetUpload').click(function(e) {
	console.log('sdfsd');
	$('#eomInfo').show()
	$('#afterUpload').hide();
    
});
$('#nromalMsgtype').change(function(e) {
	$('#messageCount').empty().append('0');
	$('#letterCount').empty().append('0');
    if($(this).val()==1 || $(this).val()==3){
		$('#message').val('').removeAttr('onkeyup');
	} 
	else if($(this).val()==2 || $(this).val()==4){
		$('#message').val('').attr('onkeyup',"conversion();");
	} 
	$('#selTemp').val('none');
});
$('#message').on('keyup',function(e){
	var msg_length  = obj.count_msg($(this).val(),$('#nromalMsgtype').val());
	$('#messageCount').empty().append(msg_length.messageLength);
	$('#letterCount').empty().append(msg_length.charLength);
	var charCode = e.keyCode || e.which;
	if(charCode.toString() !== '8' && charCode.toString() !== '46'){
		var msg_length  = obj.count_msg($(this).val(),$('#nromalMsgtype').val());
		if( parseInt(msg_length.messageLength) > 8) {
			obj.message_show("** Sorry , We can't send more than 9 message ",'error');
			msgState = true;
		}else{ msgState= false;}
	}
	else{
		 msgState= false;
	}
});
$("#message").bind('paste', function(e) {
    if(msgState == true) e.preventDefault();
});	
$('#message').on('keypress',function(e){
	if(msgState == true) e.preventDefault();
	
});
/* verifing  and reseting normal message upload numbers */
$('#msgUpNumberSch').on('click','span',function(e){
	
	if($(this).hasClass('verifyed')){
		var ele = "<div class='headerMenu'><header><h2 style='background-image:url(vas/sms/images/load/verifynumber.png);'>Verification</h2><i>x</i></header><section id='headerSection'></section></div>";
		$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
		$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
			$(this).append(ele);
			$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
				obj.load_ext_file('#headerSection','vas/sms/common_c/load_View/faultVerify');
			});
		});
	}
	else if($(this).hasClass('reset')){
		$('#msgUpNumberSch').empty();
		$('#uploadNumbers').val('');
		obj.verifiedNumber = null;
	}
			
	});
$('#uploadNumbers').change(function(e) {
        obj.verifiedNumber = null;
		var ext = $(this).val().split(".").pop().toLowerCase();	
		if($.inArray(ext, ['csv','txt']) == -1) {
			obj.message_show('**Error : Invalid Upload file , Upload CSV (MS-Dos) or TXT Format','error');
			return false;
		}		
		var file = e.target.files[0];
		
		if(file != undefined) {	
			var reader = new FileReader();			
			reader.readAsText(file);
			
			reader.onload = function(e) {
				var fileLine=e.target.result.split("\n");
				var i,j,chunk = 200;
				var arr = []; 
				var repeat=[];
				var fault=[];
				var valid = [];
				for (i=0; i<fileLine.length; i++) {
					if(fileLine[i].replace(/ /g,'')!=''){	
						if(fileLine[i].replace(/ /g,'').indexOf(",")!=-1){
							obj.message_show('** Warning : Invlalid format </br> Each number must be in new line ','error');
							return;
						}
						arr.push(fileLine[i].replace(/ /g,'').replace(/\r/g,''))
					}
					
				}
				for (i=0,j=arr.length; i<j; i+=chunk) {
					var temparray = arr.slice(i,i+chunk).join(',');
					
					var res = obj.dhx_ajax('vas/sms/common_c/verifyNumber','number='+temparray );
					
					if(res!=='error'){
						res = JSON.parse(res);
						
						if( res.fault!=undefined){
							fault= fault.concat(res.fault);
							
						}
						if( res.repeat!=undefined){
							repeat = repeat.concat(res.repeat);
						}
						if(res.valid_Number!=undefined){
							valid = valid.concat(res.valid_Number);
						}
					}
				}
				obj.verifiedNumber = {
										valid_Number:valid,
										fault:fault,
										repeat:repeat,
									};
				
				
				$('#msgUpNumberSch').empty().append('<span class="msgTitle">Valid :</span><span>'+obj.verifiedNumber.valid_Number.length+'  </span><span  class="msgTitle">Fault :</span><span>'+fault.length+'</span> <span  class="msgTitle">Repeat :</span><span>'+repeat.length+'</span> <span class="verifyed msgTitleOp" >[ VERIFY ]</span><span class="reset msgTitleOp">[ RESET ]</span>');
			}
		}
    });
$('#eomMessage').change(function(e) {
		var eomMsgType = $('#eomMsgType').val();
		var eomMsgTypeName = $('#eomMsgType').children('option:selected').text();
		var eomType = $('input[name="eomType"]:checked').val();
		if( eomType === undefined  || eomMsgType==='none' ){
			if( eomType === undefined ) obj.message_show('**Error : Select EOM Type','error');
			if( eomMsgType==='none' ) obj.message_show('**Error : Select Message Type','error');
			$(this).val('');
			return;
		}
		
		var ext = $(this).val().split(".").pop().toLowerCase();	
		if($.inArray(ext, ['csv','txt']) == -1) {
			obj.message_show('**Error : Invalid Upload file , Upload CSV Format','error');
			return false;
		}		
		var file = e.target.files[0];
		
		if(file != undefined) {	
			var reader = new FileReader();			
			reader.readAsText(file);
			$('#eomInfo').hide();
			$('#afterUpload').show();
			obj.dynamicGrid	({
				id:'uploaded',
				header:'Number, Message,Message Count,Message Type',
				setInitWidths : '120,270,140,120',
				pageSize:6
			});
			
			reader.onload = function(e) {
				var fileLine=e.target.result.split("\n");
				var header = (eomType == 'heom')?fileLine[0].split(','):null;
				var size = fileLine[0].split(',').length;
				var i = (eomType == 'heom')?1:0;
				for( i; i<fileLine.length; i++){
					
					if(header ==null){
						var data = fileLine[i].split(',');
						var msg = ''; var number;
						for(j=0; j<data.length;j++){
							if(j != (data.length-1)) msg = msg + data[j]+'\r\n';
							else number = data[data.length-1];
						}
						if(data.length==size){
							var msg_length  = obj.count_msg(msg.toString(),eomMsgType);
							gridTxt.rows.push({id:(i+1).toString(), data:[ number.toString(), msg.toString() ,msg_length.messageLength +'('+msg_length.charLength+')',eomMsgTypeName] });
						}
					}
					else if(header !==null){
						var data = fileLine[i].split(',');
						var msg = ''; var number;
						for(j=0; j<data.length;j++){
							if(j != (data.length-1)){ 
								if(j == (data.length-2)) msg = msg + header[j]+':'+ data[j];
								else msg = msg + header[j]+':'+ data[j]+'\r\n';
							}
							else number = data[data.length-1];
						}
						if(data.length==size){
							var msg_length  = obj.count_msg(msg.toString(),eomMsgType);
							gridTxt.rows.push({id:(i+1).toString(), data:[ number.toString(), msg.toString() ,msg_length.messageLength +'('+msg_length.charLength+')',eomMsgTypeName] });
						}
					}
				}
				
				obj.grid['uploaded_t'].parse(gridTxt,"json");
			}
		}
});
$('#resetUploadeom').click(function(e) {
	obj.grid['uploaded_t'].clearAll();
	$('#eomInfo').show();
	$('#afterUpload').hide();
	gridTxt ={rows:[]}; 
});
$('#submitUploadeom').click(function(e) {
	$('#load').show();
    e.preventDefault();
	var sendidList =[];
	$('#eomSenderid select').each(function(index, element) {
		sendidList.push($(this).siblings('h4').data('val')+'_'+$(this).val());
	});
	var res = obj.dhx_ajax('vas/sms/push_c/sendEomSms','data='+JSON.stringify(gridTxt)+'&senderid='+sendidList.join(',')+'&msgType='+$('#eomMsgType').val());
	res = res.split('__');
	if(res[0]=='sucess'){
		
		obj.message_show('** SMS Sent Sucessfully');
		obj.grid['uploaded_t'].clearAll();
		$('#eomInfo').show();
		$('#afterUpload').hide();
		$('#eomMessage').val('');
		gridTxt ={rows:[]}; 
		// showing details
		var ele = "<div class='headerMenu'><header><h2 style='background-image:url(vas/sms/images/load/header.png);'>Send SMS Details</h2><i>x</i></header><section id='sendSmsSection'></section></div>";
		$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
		$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
			$(this).append(ele);
			$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
				$('#sendSmsSection').append(JSON.parse(res[1]).join('</p><p>'));
			});
		});
	}
	else{
		if(res[0]=='fail')	obj.message_show(res[1],'error');
		else obj.message_show(res[0],'error');
	}
	
	
	setTimeout(function(){  $('#load').hide(); }, 400);
});
/*$('#sendSmsMainHeader li a img').on('click',function(e){
	console.log('image');
});*/
$('#addbookfeild ul').on('click','li span.verify',function(e){
	var txt = $(this).parent('li').text();
	txt = txt.substr(0,txt.length-7);
	var addId = $(this).parent('li').data('val');
	var ele = "<div class='headerMenu'><header><h2 style='background-image:url(vas/sms/images/load/"+txt+"header.png);'>"+txt+"~Contact list</h2><i>x</i></header><section id='headerSection'></section></div>";
	$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
	$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
		$(this).append(ele);
		$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
			obj.load_ext_file('#headerSection','vas/sms/common_c/load_View/verifyNumber/'+addId);
		});
	});
		
});
$('#addbookfeild ul').on('click','li span.close',function(e){
	$(this).parent('li').remove();
	if($('#addbookfeild ul li').length == 0){
		obj.excludeNumber = [];
	}
});
$('#normalSmsForm').submit(function(e) {
        e.preventDefault();
		var dataArr = [];
		var schDate= [];
		var validNumber = [];
		var addBookId = [];
		var sendidList =[];
		var message = ($('#message').val()!=='')?$('#message').val():null;
		var msgType = $('#nromalMsgtype').val();
		var evtName = $('input[name="procesType"]:checked').val();
		
		
		if( message == null){ obj.message_show('**Warning : No Message found','error'); return; }
		
		if(obj.verifiedNumber !== null){
			if( (obj.verifiedNumber.fault !==undefined && obj.verifiedNumber.fault.length >0) || (obj.verifiedNumber.repeat !== undefined && obj.verifiedNumber.repeat.length > 0 )){
				var r = confirm("Some of the numbers found fault or repeated in upload file , Do you really want to continue ?");
				if (r == false) { return; } 
				if(obj.verifiedNumber.valid_Number.length== 0) { obj.message_show('**Warning : No valid number found','error'); return; }
			}
			validNumber = obj.verifiedNumber.valid_Number;
		}
		console.log(validNumber);
		if( $('#schNumbers').val().replace(/ /g,'')!=='' ){
			validNumber = validNumber.concat($('#schNumbers').val().split(','));
			/*
			
			var res = obj.dhx_ajax('vas/sms/common_c/verifyNumber','number='+$('#schNumbers').val().replace(/ /g,'') );
			
			if(res!=='error'){
				res = JSON.parse(res);
				if( res.fault!=undefined ){obj.message_show(res.fault.join(',')+' found to be fault in Insert Number feild','error'); return; }
				if( res.repeat!=undefined){obj.message_show(res.repeat.join(',')+' found to be repeated Insert Number feild','error'); return;}
				
				if(validNumber !=null) validNumber = validNumber.concat(res.valid_Number);
				else validNumber = res.valid_Number;
			}
			else { obj.message_show('**Error : Number validation error','error'); return; }
		*/}
		if($('#addbookfeild ul li').length > 0){
			$('#addbookfeild ul li').each(function(index, element) {
                addBookId.push($(this).data('val'));
            });
		}
		if(addBookId.length ==0 && validNumber.length==0)  { obj.message_show('**WarningEE: No valid number found','error'); return; }
		$('#smsSenderid select').each(function(index, element) {
            sendidList.push($(this).siblings('h4').data('val')+'_'+$(this).val());
        });
		
		if(validNumber != null)dataArr.push('numbers='+validNumber.join(','));
		dataArr.push('addressbook='+addBookId.join(','));
		dataArr.push('sendidList='+sendidList.join(','));
		dataArr.push('message='+encodeURIComponent(message));
		dataArr.push('messageType='+msgType);
		dataArr.push('processType='+evtName);
		
		if(obj.excludeNumber.length > 0)dataArr.push('excludeNumber='+obj.excludeNumber.join(','));
		else dataArr.push('excludeNumber=none');
		$('#load').show();
		//console.log(dataArr);
		//return;
		var res = obj.dhx_ajax('vas/sms/push_c/sendSms', dataArr.join('&'));
		res = res.split('__');
		if(res[0]=='sucess'){
			console.log(JSON.parse(res[1]))
			if(evtName=='default'){
				obj.message_show('** SMS Queued Sucessfully');
			}
			else{
				obj.message_show('** SMS Sent Sucessfully');
			}
			$('#message').val('').removeAttr('onkeyup');
			$(this)[0].reset();
			$('#msgUpNumberSch').empty();
			$('#addbookfeild ul').empty();
			$('#messageCount').empty().append('0');
			$('#letterCount').empty().append('0');
			obj.verifiedNumber = null;
			obj.excludeNumber = [];
			// showing details
			var ele = "<div class='headerMenu'><header><h2 style='background-image:url(vas/sms/images/load/header.png);'>Send SMS Details</h2><i>x</i></header><section id='sendSmsSection'></section></div>";
			$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
			$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
				$(this).append(ele);
				$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
					$('#sendSmsSection').append(JSON.parse(res[1]).join('</p><p>'));
				});
			});
		}
		else{
			if(res[0]=='fail')	obj.message_show(res[1],'error');
			else obj.message_show(res[0],'error');
		}
		setTimeout(function(){  $('#load').hide(); }, 400);
		//console.log(res);
		
    
    
});
$('#schNumbers,#message').on('focusin',function(e){
	$(this).css('box-shadow','3px 3px 5px #888888');
});
$('#schNumbers,#message').on('focusout',function(e){
	$(this).css('box-shadow','none');
});
}());
</script>
<style>
#schNumbers,#message,#addbookfeild li{
	transition-property:all;
    transition-duration: .500s;
    transition-timing-function: ease;
  
}
#sendsms{ height:485px; font-size:12px; width:100%;  overflow:hidden; 
margin-top:15px; 
}
#sendsms input, #sendsms select{ font-size:12px;}
#sendSmsMainHeader{ height:31px; border-bottom: 3px solid #0078d7;}
#sendSmsMainHeader li{ float:left;  font-size:13px; border: 1px solid #a4bed4;border-bottom: 1px solid #0078d7; height:30px; width:120px; }
#sendSmsMainHeader li:last-child{ border-left:none;}
#sendSmsMainHeader li a{ text-decoration:none; padding: 8px 14px;
display: block; color:black; 
}


.heighlight_sendSMS{
	background-color:#0078d7;
	border: 1px solid white;
	border-bottom: 1px solid #0078d7;
	color:white !important;
}

#normalSms,#eomlSms{background-color:#fbfbfb;width:100%; height:450px;  backface-visibility:hidden;

border-left:1px solid #c7c7c7;
border-right:1px solid #c7c7c7;
border-bottom:1px solid #c7c7c7;
-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;
padding:20px 10px 10px 10px;
}

#normalSms form > div{ height:370px; border:1px solid #a4bed4; margin-right:5px; position:relative; background-color:white;
padding:10px 0;
-webkit-box-sizing: border-box; -moz-box-sizing: border-box;   
box-sizing: border-box; }

#eomlSms{ overflow:auto; padding-bottom:10px;}
#eomlSms >div{ border:1px solid #a4bed4; margin-right:5px; position:relative; background-color:white;}
#normalSms form > div:first-child{ width:245px;}
#normalSms form > div:nth-child(2){ width:295px;}
#normalSms form > div:nth-child(3){ width:355px; margin-right:0;}
#normalSms form > div:last-child{ border:none; width:100%;height:auto; margin-top:10px; text-align:right; background-color:transparent;}
#normalSms form >  div> h3,#eomlSms div> h3{ position:absolute; backface-visibility:hidden; left:10px; top:-9px; background-color:white; padding:2px 5px; }


#normalSms div:last-child input{ margin-left:10px;  }
#eomlSms> div:first-child{ height:25px; padding:10px;}
#eomlSms div p { font-size:12px;}
#afterUpload{ background-color:transparent !important; border:none !important; min-height:0;; margin:12px 0;}
#eomInfo{ min-height:40px; margin-top:10px; padding:15px 10px;}
#smsSenderid ,#smsAddress,#smsMessage{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box;   
box-sizing: border-box;}

#smsSenderid > div div , #smsAddress >div div, #smsMessage >div div{-webkit-box-sizing: border-box; -moz-box-sizing: border-box;   
box-sizing: border-box;  position:relative;margin-top:7px; }
#smsSenderid > div div,#smsAddress > div div{ padding:0px; margin-top:7px;}


#smsSenderid > div div h4,#smsAddress>div div h4,#smsMessage >div div h4{ margin:0 0 7px 0; color:#7d1313;
}
#normalSms form > div >div{ padding:0 10px;
overflow:auto; -webkit-box-sizing: border-box; -moz-box-sizing: border-box;   
box-sizing: border-box; height:100%;}
#smsSenderid select ,#smsAddress select,#smsMessage >div div select{  width:100%; font-size:12px; padding:3px 7px;}


#addbookfeild li{ position:relative;
	
	-webkit-box-sizing: border-box; -moz-box-sizing: border-box;   
box-sizing: border-box; padding:5px 85px 5px 10px; border:1px solid #d1d1d1; 

}
#addbookfeild li:hover{
	box-shadow: 3px 3px 5px #888888;
}


#addbookfeild li:nth-child(n+2){ margin-top:4px;}
#addbookfeild li span:first-child{ font-size:9px; text-decoration:underline;
	position:absolute; right:35px; top:7px; letter-spacing:1px; color:blue; cursor:pointer;
}
#addbookfeild li span:last-child{ cursor:pointer; padding:2px 5px;background-color:#d1d1d1; color:white; position:absolute;right:5px; top:3px;}
#addbookfeild li span:first-child:hover{color:red;}
#addbookfeild li span:last-child:hover{ background-color:red;}
#smsMessage >div p{ margin:10px 0;}
#smsMessage >div p select{  font-size:12px; padding:3px 10px;}
#messageCount{ color:red;}
#letterCount{ color:blue;}
#message { font:13px; width:325px; height:190px; border:1px solid #a4bed4;}

#eomlSms  input{ font-size:12px;}
#eomlSms select{ padding:3px 5px; font:12px;}
#afterUpload >p { text-align:right; }
#dataupload{ width:675px; height:315px; overflow:auto; -webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;}
#uploaded{ width:650px;}
#message{ font-size:13px;}
#addbookfeild{ margin-top:10px !important; border:1px dashed #a4bed4; width:273px; margin:0 auto; -webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box; height:97px; overflow:auto;}
#eomSenderid,#eomInfo,#afterUpload{ float:left;}
#eomSenderid{ width:200px;  height:350px; margin-top:12px; padding:10px;-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box; position:relative;}
#eomInfo,#afterUpload{ width:650px;}
#eomSenderid h3{ position:absolute; top:-8px; left:10px; padding:2px 10px; background-color:white;}
#eomSenderid > div{ height:100%; width:100%; overflow:auto;}
#eomSenderid select{ width:100%;}
#eomSenderid > div div{ margin-top:10px;}
#eomSenderid > div div h4{ margin-bottom:10px;color: rgb(0, 120, 215);}
#msgUpNumberSch span.verifyed, #msgUpNumberSch span.reset{ cursor:pointer;}
#msgUpNumberSch span.msgTitle { color:rgb(158, 21, 21);} 
#msgUpNumberSch span.msgTitleOp{ color:blue;}
</style>






