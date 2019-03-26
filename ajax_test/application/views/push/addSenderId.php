<form action="#" id="addSenderId">
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
     <div><h3>Sender ID Detail</h3>
     <table>
        <tr>
        	<td>Sender ID :</td>
            <td><input type="text" name="senderid" placeholder="Only Alphabetical up to 11 character"/></td>
        </tr>
        <tr>
        	<td>Organization Name :</br><span>* Required</span></td>
            <td><textarea name="descrption" value="">
            </textarea></td>
        </tr>
    </table>
    </div>
    <p><input id="senReset" class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript">
	$('#addSenderId div table tr td select[name="operator"]').change(function(e) {
        if($(this).val()!=='none'){
			$('#load').show();
			$('#addSenderId div table tr td select[name="gateway"]').removeAttr('disabled');
			$('#addSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>');
			var res = obj.dhx_ajax('common_c/getDetail/userGateway/'+$(this).val() );

			if(res !=='none'){
				res = JSON.parse(res);
				for(i=0; i < res.length; i++){
					$('#addSenderId div table tr td select[name="gateway"]').append('<option value="'+res[i].id+'">'+res[i].name+'</option>');
				}
			}
			setTimeout(function(){  $('#load').hide(); }, 400);
		}
		else{
			$('#addSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
		}
    });
	$('#senReset').click(function(e) {
		$('#addSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
    });
	$('#addSenderId').submit(function(e) {
		e.preventDefault();
		$('#load').show();
        var res = obj.dhx_ajax('push_c/addSenderId',$(this).serialize() );
		if(res=='sucess'){ obj.message_show('New Sender ID requested Sucessfully');
			$('#addSenderId div table tr td select[name="gateway"]').empty().append('<option value="default">Default</option>').attr('disabled','disabled');
			obj.grid['dhxDynFeild_t'].clearAndLoad('push_c/renderSenderId');
			$(this)[0].reset();
		}
		else obj.message_show(res,'error');
		setTimeout(function(){  $('#load').hide(); }, 400);
    });
</script>
<style>
#addSenderId { font-size:12px; padding:15px 10px;}
#addSenderId > div { position:relative; border:1px solid #a4bed4; padding:10px; margin-bottom:15px;}
#addSenderId > div h3 { position:absolute; top:-10px; left:15px; padding:2px 10px; background-color:white;}
#addSenderId > div table tr td, #addSenderId > div table tr td select, #addSenderId > div table tr td input,#addSenderId > div table tr td textarea{ padding:3px 5px; font-size:12px;}
#addSenderId > div table tr td select, #addSenderId > div table tr td input,#addSenderId > div table tr td textarea{ width:235px;}
#addSenderId > div table tr td textarea { height:50px;}
#addSenderId > div table tr td span { font-size:10px; color:red;}
#addSenderId > p{ text-align:right;}
#addSenderId > p input{ margin-left:10px;}
</style>
