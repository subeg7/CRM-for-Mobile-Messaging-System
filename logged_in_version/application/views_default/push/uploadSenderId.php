<form action="#" id="uploadSenderId">
	<div><h3>Operator & Gateway</h3>
	<table>
    	<tr>
        	<td>Select Operator</td>
            <td><select name="operator">
            	<option value="none">--Select--</option>
                <?php
					if($operator !=='none'){
						foreach($operator as $row){
							echo '<option value="'.$row->fld_int_id.'"> '.strtoupper($row->acronym).' </option>';
						}
					}
				?>
            </select></td>
        </tr>
        <tr>
        	<td>Select Gateway</td>
            <td><select name="gateway" disabled>
            	<option value="default">Default</option>
            </select></td>
        </tr>
     </table>
     </div>
     <div><h3>Upload File</h3>
     <table>
        <tr>
        	<td><input id="upSenderId" type="file" name="up_senderid"/></td>
            <td></td>
        </tr>
        <tr>
        	<td><p id="upsendFileMesg"></p></td>
            <td></td>
        </tr>

    </table>
    </div>
    <p><input id="senReset" class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript">
(function(){
	var arr= {};
	$('#uploadSenderId div table tr td select[name="operator"]').change(function(e) {
        if($(this).val()!=='none'){
			$('#load').show();
			$('#uploadSenderId div table tr td select[name="gateway"]').removeAttr('disabled');
			$('#uploadSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>');
			var res = obj.dhx_ajax('common_c/getDetail/userGateway/'+$(this).val() );

			if(res !=='none'){
				res = JSON.parse(res);
				for(i=0; i < res.length; i++){
					$('#uploadSenderId div table tr td select[name="gateway"]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
				}
			}
			setTimeout(function(){  $('#load').hide(); }, 400);
		}
		else{
			$('#uploadSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
		}
    });
	$('#senReset').click(function(e) {
		$('#uploadSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
		$('#upsendFileMesg').empty();
    });
	$('#upSenderId').change(function(e) {
		arr = {validId:[],invalidId:[]};
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
				for (i=0; i<fileLine.length; i++) {
					var line = fileLine[i].replace(/ /g,'').replace(/\r/g,'');
					if(/^[a-zA-Z]+$/.test(line) && line.length <12){
						arr.validId.push(line);
					}
					else arr.invalidId.push(line);
				}
				$('#upsendFileMesg').append('Valid ID : '+arr.validId.length+ ' | Invalid ID : '+arr.invalidId.length+ ' [ <span>VERIFY</span> ][ <span>RESET</span> ] ');
			}
		}
    });
	$('#upsendFileMesg').on('click','span',function(e){
		if($(this).text().replace(/ /g,'') == 'RESET'){
			$('#upsendFileMesg').empty();
			$('#upSenderId').val('');
		}
		else if($(this).text().replace(/ /g,'') == 'VERIFY'){
			var ele = "<div class='headerMenu'><header><h2 style='background-image:url(assets/images/verifynumber.png);'>Verification</h2><i>x</i></header><section id='headerSection'><div style='padding:5px 10px; height:420px; overflow:auto;'><div id='faultSender' style='height:400px;'></div></div></section></div>";
			$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
			$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
				$(this).append(ele);
				$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
					if(arr.validId.length > 0 || arr.invalidId.length > 0){
						var res = obj.create_dhx_tabar({ id:'faultSender',tab_text:['Valid_ID','Invlaid_ID']});
						var keys = Object.keys(arr);
						console.log(arr);
						console.log(keys);
						console.log(res);
						for(i=0;i<res.length; i++){
							$('#'+res[i]).css('font-size','12px').css('padding','10px');
							$('#'+res[i]).append('<p class="verifyP">'+arr[keys[i]].join('</p><p class="verifyP">')+'</p>');
						}
					}
				});
			});
		}
	});
	$('#uploadSenderId').submit(function(e) {
		e.preventDefault();
		var dataArr =[];
		if($('#uploadSenderId div table tr td select[name="operator"]').val()=='none'){
			obj.message_show('Please Select Operator','error'); return;
		}


		if($('#upSenderId').val()==''){
			obj.message_show('Please Upload Sender ID','error'); return;
		}
		if(arr.invalidId.length!= undefined && arr.invalidId.length > 0 ){
			var r=confirm("You have invalid sender ID do you still want to continue ? if you continue your valid sender ID will be uploaded");
			if (r == false) { return; }
		}
		if(arr.validId.length== undefined || arr.validId.length == 0 ){
			obj.message_show('No Valid Sender ID found','error'); return;
		}
		dataArr.push('operator='+$('#uploadSenderId div table tr td select[name="operator"]').val());
		dataArr.push('gateway='+$('#uploadSenderId div table tr td select[name="gateway"]').val());
		dataArr.push('senderid='+arr.validId.join(',') );

		$('#load').show();
        var res = obj.dhx_ajax('push_c/uploadSenderId',dataArr.join('&') );
		console.log(res);
		if(res=='sucess'){ obj.message_show('New Sender ID Uploaded Sucessfully');
			$('#uploadSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
			obj.grid['dhxDynFeild_t'].clearAndLoad('push_c/renderSenderId');
			$(this)[0].reset();
			$('#upsendFileMesg').empty();
		}
		else obj.message_show(res,'error');
		setTimeout(function(){  $('#load').hide(); }, 400);
    });
}());
</script>
<style>
#uploadSenderId { font-size:12px; padding:15px 10px;}
#uploadSenderId > div { position:relative; border:1px solid #a4bed4; padding:10px; margin-bottom:15px;}
#uploadSenderId > div h3 { position:absolute; top:-10px; left:15px; padding:2px 10px; background-color:white;}
#uploadSenderId > div table tr td, #uploadSenderId > div table tr td select, #uploadSenderId > div table tr td input{ padding:3px 5px; font-size:12px;}
#uploadSenderId > div table tr td select, #uploadSenderId > div table tr td input{ width:235px;}
#uploadSenderId > p{ text-align:right;}
#uploadSenderId > p input{ margin-left:10px;}
#upsendFileMesg{ font-size:10px; color:red;}
#upsendFileMesg span{ color:blue; cursor:pointer;}
#mainLoadidng{ z-index:1007;}
.verifyP{ width:130px; float:left;}
</style>
