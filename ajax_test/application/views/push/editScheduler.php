<div id="editScheduler">
	<form name="unicode" >
    	<h3> Edit Scheduler Message</h3>
        <table>
        	<tr>
            	<td>Select Template</td>
                <td><select id="selTemp"><option value="none">--Select--</option>
                	<?php
						if($template !=='none'){
							foreach($template as $row){
								echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_title.'</option>';
							}
						}
					?>
                </select></td>
            </tr>
            <tr>
            	<td>Message type</td>
                <td><select  id="eomMsgtype" name="messageType"  >
                                <option <?php if($detail->messageType==1) echo 'selected'; ?> value="1">Text</option>
                                <option  <?php if($detail->messageType==2) echo 'selected'; ?> value="2">Unicode</option>
                                <option  <?php if($detail->messageType==3) echo 'selected'; ?> value="3">Text Flash</option>
                                <option <?php if($detail->messageType==4) echo 'selected'; ?> value="4">Unicode Flash</option>
                        </select>
               </td>
            </tr>
            <tr>
            	<td>Count : <span id="messageCount" style="color:red;">0</span> ( <span id="letterCount" style="color:blue;">0</span> )</td>
                <td> <textarea  name="nepbox" class="unicode" id="message" name="message" value="" ><?php echo $detail->message;?></textarea></td>
            </tr>

        </table>

    </form>
	 <p><input type="reset" class="button" id="editSchRes" value="Reset"/><input  class="button" id="editSchSub"  type="submit" value="Submit"/></p>
</div>
<script type="text/javascript">
(function(){
	var msgState = false;
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
	$('#editSchSub').click(function(e) {
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
       	var res = obj.dhx_ajax('push_c/editQuejob/'+selId,$('#editScheduler form').serialize());
		if(res=='sucess'){
			obj.winTooble = true;
			obj.wind.window('scheduler').close();
			obj.message_show('Schdeduler Updated Sucessfully');
		}
		else{
			obj.message_show(res,'error');
		}
    });

	$('#editSchRes').click(function(e) {
        $('#editScheduler form')[0].reset();
    });
}());
</script>
<style>
#editScheduler{ font-size:12px; padding:15px 10px 10px 10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;

}
#editScheduler form select, #editScheduler form textarea{ padding:2px 10px; width:260px;}
#editScheduler form textarea{ height:180px;}
#editScheduler form{ border:1px solid #a4bed4;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding:15px 10px 10px 10px;
position:relative;
}
#editScheduler form tr td{ padding:3px 10px;}
#editScheduler form h3{ position:absolute; top:-8px; left:15px; background-color:white; padding:3px 10px; color:blue; }
#editScheduler  > p{ text-align:right; margin-top:10px;}
#editScheduler  > p input:nth-child(2){ margin-left:10px;}
</style>
