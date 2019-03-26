<form id="schAdd" name="unicode">
<div id="addscheduler">
	<div>
    	<h2>Sender ID</h2>
        <div id="senderIdListSch">
        <?php
			if($operator!='none'){
				foreach($operator as $row){
					echo '<div><h3 data-val="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</h3><select><option value="none">--Select--</option>';
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
    <div>
    	<h2>Address Book & Cell Numbers</h2>
        <div>
            <div>
                <h3>Upload Numbers</h3>
                <input type="file" id="uploadNumbers" name="SchUpfile"/>
                <p id="msgUpNumberSch"></p>
            </div>
            <div>
                <h3>Enter Numbers</h3>
               <textarea value="" id="schNumbers"></textarea>
            </div>
            <div>
                <h3>Select Addressbook</h3>
                <select id="schAddSel"><option value="none">--Select--</option>
                <?php
                    if($addressbook !=='none'){
                        foreach($addressbook as $row){
                            echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_name.'</option>';
                        }
                    }
                ?>
                </select>
            </div>
            <div id="addbooklist"></div>
        </div>
    </div>
    <div>
    	<h2>Message</h2>
        <div>
            <div>
                <h3>Select Template</h3>
                <select id="selTemp"><option value="none">--Select--</option>
                	<?php
						if($template !=='none'){
							foreach($template as $row){
								echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_title.'</option>';
							}
						}
					?>
                </select>
            </div>
            <div>
                <h3>Message Type/Count</h3>
                <select  id="eomMsgtype" style="margin-bottom:5px;" >
                                <option value="1">Text</option>
                                <option value="2">Unicode</option>
                                <option value="3">Text Flash</option>
                                <option value="4">Unicode Flash</option>
                        </select>
                &nbsp;&nbsp;&nbsp;Count : <span id="messageCount" style="color:red;">0</span> ( <span id="letterCount" style="color:blue;">0</span> )
                 <textarea  name="nepbox" class="unicode" id="message" name="message" value="" style="margin-top:5px;"> </textarea>
            </div>
        </div>
    </div>
    <div>
    	<h2>Scheduler</h2>
        <div>
        	 <div><h3>Event Name</h3>
                <input type="text" id="eventName" />
            </div>
            <div>
            	<h3>Enter Dates</h3>
                <input type="text" id="schdate" /><input type="button" id="addtime" value="Add" class="button"/>
            </div>
            <div id="datelist">

            </div>
        </div>
    </div>
    <div class="clearBoth" style="height:0px; padding:0; border:none; width:820px; margin-bottom:10px; "></div>
	<p><input type="reset" class="button" value="Reset"/><input  class="button"  type="submit" value="Submit"/></p>
</div>
</form>
<script type="text/javascript">
(function(){
	obj.verifiedNumber = null;
	obj.excludeNumber = [];
	var msgState = false;
	obj.create_dhx_calander({ // adding calander in toolbar button :ID = fromDate
				time:'show',
				multi:'single',
				id:'schdate',
				param: ['schdate']
	});
	$('#selTemp').change(function(e) {
		if($(this).val() !='none'){
			var res = obj.dhx_ajax('common_c/getDetail/template/'+$(this).val() );
			if(res!=='none'){
				res = JSON.parse(res);
				var msg_length  = obj.count_msg(res[0].fld_chr_msg,res[0].fld_int_mst);
				$('#messageCount').empty().append(msg_length.messageLength);
				$('#letterCount').empty().append(msg_length.charLength);
				$('#eomMsgtype').val(res[0].fld_int_mst);
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
			$('#eomMsgtype').val('1');
		}
    });
	$('#addbooklist').on('click','p span.verify',function(e){
		var txt = $(this).parent('p').text();
		txt = txt.substr(0,txt.length-7)
		var addId = $(this).parent('p').data('val');
		var ele = "<div class='headerMenu'><header><h2 style='background-image:url(assets/images/verifynumber.png);'>"+txt +"~ Addressbook Contact Lists</h2><i>x</i></header><section id='headerSection'></section></div>";
		$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
		$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
			$(this).append(ele);
			$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
				obj.load_ext_file('#headerSection','common_c/load_View/verifyNumber/'+addId);
			});
		});

	});
	$('#addbooklist').on('click','p span.close',function(e){
		$(this).parent('p').remove();
		if($('#addbooklist p').length==0){
			obj.excludeNumber = [];
		}
	});
	$('#schAddSel').change(function(e) {
		if($(this).val()!=='none'){
			var txt = $(this).children('option:selected').text();
			var val = $(this).val();
			var exist = false;
			if($('#addbooklist p').length > 0){
			$('#addbooklist p').each(function(index, element) {
				if($(this).data('val') == val){
					obj.message_show('**Warning: Addressbook already selected','error');
					exist = true;
				}
            });
		}
			if(exist == false)
			$('#addbooklist').append('<p data-val="'+val+'">'+txt+' <span class="verify">VERIFY</span><span class="close">x</span></p>');
		}
	});
	$('#eomMsgtype').change(function(e) {
		$('#messageCount').empty().append('0');
		$('#letterCount').empty().append('0');
		if($(this).val()==1 || $(this).val()==3){
			$('#message').val('').removeAttr('onkeyup');
		}
		else if($(this).val()==2 || $(this).val()==4){
			$('#message').val('').attr('onkeyup',"conversion();");
		}
	});
	$('#message').on('keyup',function(e){
		var msg_length  = obj.count_msg($(this).val(),$('#eomMsgtype').val());
		$('#messageCount').empty().append(msg_length.messageLength);
		$('#letterCount').empty().append(msg_length.charLength);
		var charCode = e.keyCode || e.which;
		if(charCode.toString() !== '8' && charCode.toString() !== '46'){
			var msg_length  = obj.count_msg($(this).val(),$('#eomMsgtype').val());
			if( parseInt(msg_length.messageLength) > 8) {
				obj.message_show("** Sorry , We can't send more than 8 message ",'error');
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
	$('#addtime').click(function(e) {
        var dates = $('#schdate').val();
		if(dates !==''){
			var unixdate = parseInt(new Date(dates).getTime()/1000);
			if( Math.floor(new Date().getTime()/1000) >= unixdate ){
				obj.message_show('**Warning : Invalid Selected Date','error');
			}
			else{
				$('#datelist').append('<p>'+dates+' <span class="close">x</span></p>');
				$('#schdate').val('');
			}
		}
    });
	$('#datelist').on('click','p span.close',function(e){
		$(this).parent('p').remove();
	});

	$('#msgUpNumberSch').on('click','span.verifyed',function(e){
		var ele = "<div class='headerMenu'><header><h2 style='background-image:url(assets/images/verifynumber.png);'>Verification</h2><i>x</i></header><section id='headerSection'></section><style></style></div>";
		$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
		$('#mainLoadidng').append(ele);
		$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {

			$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
				obj.load_ext_file('#headerSection','common_c/load_View/faultVerify');
			});
		});

	});
	$('#msgUpNumberSch').on('click','span.reset',function(e){
		$('#msgUpNumberSch').empty();
		$('#uploadNumbers').val('');
		obj.verifiedNumber = null;

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
							obj.message_show('** Warning : Invlalid format','error');
							return;
						}
						arr.push(fileLine[i].replace(/ /g,'').replace(/\r/g,''))
					}

				}
				for (i=0,j=arr.length; i<j; i+=chunk) {
					var temparray = arr.slice(i,i+chunk).join(',');

					var res = obj.dhx_ajax('common_c/verifyNumber','number='+temparray );
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

				$('#msgUpNumberSch').empty().append('<span>Fault :</span><span>'+fault.length+'</span> <span>Repeat :</span><span>'+repeat.length+'</span> <span class="verifyed">[ VERIFY ]</span><span class="reset">[ RESET ]</span>');
			}
		}
    });

	$('#schAdd').submit(function(e) {
        e.preventDefault();
		var dataArr = [];
		var schDate= [];
		var validNumber = [];
		var addBookId = [];
		var sendidList =[];
		var message = ($('#message').val()!=='')?$('#message').val():null;
		var msgType = $('#eomMsgtype').val();
		var evtName = $('#eventName').val();
		var shcDateList = [];
		if( message == null){ obj.message_show('**Warning : No Message found','error'); return; }
		if($('#datelist p').length == 0){	obj.message_show('**Warning : No scheduler Date found','error'); return;}
		// collecting date list
		$('#datelist p').each(function(index, element) {
			var unix = parseInt(new Date($(this).text().substring(0,($(this).text().length - 2 ))).getTime()/1000);
			shcDateList.push(unix);
		});
		if(obj.verifiedNumber !== null){
			validNumber = obj.verifiedNumber.valid_Number;
			/*
			if( (obj.verifiedNumber.fault !==undefined && obj.verifiedNumber.fault.length >0) || (obj.verifiedNumber.repeat !== undefined && obj.verifiedNumber.repeat.length > 0 )){
				var r = confirm("Some of the numbers found fault or repeated in upload file , Do you really want to continue ?");
				if (r == false) { return; }
				if(obj.verifiedNumber.valid_Number.length== 0) { obj.message_show('**WarningPP : No valid number found','error'); return; }
			}
			validNumber = obj.verifiedNumber.valid_Number;
		*/}
		if( $('#schNumbers').val().replace(/ /g,'')!=='' ){
			validNumber = validNumber.concat($('#schNumbers').val().replace(/ /g,'').split(','));
			/*
			var res = obj.dhx_ajax('common_c/verifyNumber','number='+$('#schNumbers').val().replace(/ /g,'') );

			if(res!=='error'){
				res = JSON.parse(res);
				if( res.fault!=undefined ){obj.message_show(res.fault.join(',')+' found to be fault in Enter Number feild','error'); return; }
				if( res.repeat!=undefined){obj.message_show(res.repeat.join(',')+' found to be repeated Enter Number feild','error'); return;}

				if(validNumber !=null) validNumber = validNumber.concat(res.valid_Number);
				else validNumber = res.valid_Number;
			}
			else { obj.message_show('**Error : Number validation error','error'); return; }
		*/}
		//console.log(validNumber);
		if($('#addbooklist p').length > 0){
			$('#addbooklist p').each(function(index, element) {
                addBookId.push($(this).data('val'));
            });
		}
		if(addBookId.length ==0 && validNumber.length==0)  { obj.message_show('**WarningEE: No valid number found','error'); return; }
		$('#senderIdListSch select').each(function(index, element) {
            sendidList.push($(this).siblings('h3').data('val')+'_'+$(this).val());
        });

		if(validNumber.length > 0)dataArr.push('numbers='+validNumber.join(','));
		dataArr.push('addressbook='+addBookId.join(','));
		dataArr.push('sendidList='+sendidList.join(','));
		dataArr.push('message='+message);
		dataArr.push('messageType='+msgType);
		dataArr.push('eventName='+evtName);
		dataArr.push('dates='+shcDateList.join(','));
		if(obj.excludeNumber.length > 0)dataArr.push('excludeNumber='+obj.excludeNumber.join(','));
		else dataArr.push('excludeNumber=none');
		$('#load').show();
		console.log(validNumber);
		//return;
		var res = obj.dhx_ajax('push_c/sendSms', dataArr.join('&'));

		res = res.split('__');

		if(res[0]=='sucess'){
			obj.message_show('** Scheduler Job Added Sucessfully');
			obj.grid['dhxDynFeild_t'].clearAndLoad('push_c/renderSchduler');
			$(this)[0].reset();
			$('#msgUpNumberSch').empty();
			$('#uploadNumbers').val('');
			$('#datelist').empty();
			$('#messageCount').empty().append('0');
			$('#letterCount').empty().append('0');
			var ele = "<div class='headerMenu'><header><h2 style='background-image:url(assets/images/header.png);'>Send SMS Details</h2><i>x</i></header><section id='sendSmsSection'></section></div>";
			$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
			$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
				$(this).append(ele);
				$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
					$('#sendSmsSection').append(JSON.parse(res[1]).join('</p><p>'));
				});
			});
			obj.wind.window('newscheduler').close();
		}
		else{
			obj.message_show(res[1],'error');
		}
		setTimeout(function(){  $('#load').hide(); }, 400);
		obj.verifiedNumber = null;
		obj.excludeNumber = [];
		console.log(res);

    });
}());
</script>
<style>
#addscheduler{ width:100%; height:100%; padding:10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
font-size:11px;
}
#addscheduler  select{ font-size:11px;}
#addscheduler > div { position:relative; float:left;  height:325px; border:1px solid #a4bed4; margin:0 10px 0 0;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding:10px;
}
#addscheduler > div:first-child{ width:150px; }
#addscheduler > div:nth-child(2){ width:220px; }
#addscheduler > div:nth-child(3){ width:220px; }
#addscheduler > div:nth-child(4){ margin-right:0; width:210px;}
#addscheduler > div > div {
	/*position:relative; float:left;*/ width:100%; height:100%;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
/*padding:10px 0;*/ margin-top:5px; overflow:auto;
}
#addscheduler > div > div  > div{ margin-bottom:10px;}
#addscheduler > div > div  > div h3{ margin-bottom:5px;}
#addscheduler > div div select{ padding:3px 5px; width:100%;}
#addscheduler > div div textarea{ width:100%; -webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
font-size:11px; height:80px;}
#addscheduler > div:nth-child(3) div textarea{ height:180px;}
#addscheduler > div div input { font-size:11px;}
#addscheduler > div h2{ position:absolute; top:-8px; left:15px; padding:2px 5px; background-color:white; color:blue;}
#addscheduler > div div h3{ color: #7d1313; left:0px;}
#addbooklist,#datelist{ padding:5px !important; margin:0px !important; border:1px dashed #a4bed4; }
#addbooklist{
	min-height:77px;
	-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
#addbooklist p, #datelist p{ border:1px solid #d1d1d1; padding:5px 26px 5px 5px; margin-bottom:5px; font-size:10px; position:relative;  }
#datelist p{ padding:5px 15px 5px 5px !important;}
#addbooklist p span,#datelist p span{ position:absolute; cursor:pointer;}
#addbooklist p span:first-child, #datelist p span:first-child{ top:5px; right:25px; font-size:9px !important; color:blue; text-decoration:underline;}
#addbooklist p span:last-child, #datelist p span:last-child{ right:3px; top:3px; background-color: #d1d1d1; color:white; padding:2px 5px; }
#addbooklist p span:last-child:hover, #datelist p span:last-child:hover{ background-color: red;}
#addscheduler > div:nth-child(3) div:nth-child(3) select{ width:80px;}
#addscheduler > div:nth-child(3) div:nth-child(3) textarea{ margin-top:10px; font-size:11px; height:196px;}
#addscheduler > div:nth-child(4) div input.button{ padding:3px 10px !important;}

#datelist { height:180px !important;}
#addscheduler > p{ font-size:12px; text-align:right}
#addscheduler > p input{ margin-left:10px;}
#msgUpNumberSch{ font-size:10px; margin-top:5px;}
#msgUpNumberSch span:nth-child(odd){ color:rgb(158, 21, 21);}
#msgUpNumberSch span:nth-child(even){ color:blue;}
#msgUpNumberSch span.verifyed, #msgUpNumberSch span.reset{ color:red; cursor:pointer;}
#eventName{font-size:11px; padding: 3px 5px; width:174px;}
#schdate{padding:3px 5px; margin-right:10px; width:120px; font-size:11px;}
#mainLoadidng{ z-index:1007;}
</style>
