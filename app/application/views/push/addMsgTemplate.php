<form id="addMsgTemplate" action="#" name="unicode">
	
	<div id="reqtype">
    	<h3>Select Request Type</h3>
	<table>
    	<tr>
        	<td><input class="typeChoosen" type="radio" name="reqtype" value="all"/> All Request</td>
            <td><input class="typeChoosen" type="radio" name="reqtype" value="specific"/>Sender ID</td>
        </tr>
   </table>
   <table id="senderType" class="displayOff">
        <tr >
        	<td><img style="height:17px;" src="vas/sms/images/load/DownRight.png"/><input class="sendertype" type="radio" name="sendertype" value="default"/> Default Sender ID</td>
            <td><input class="sendertype" type="radio" name="sendertype" value="specific"/> Specific Sender ID</td>
        </tr>
    </table>
    <table id="operType" class="displayOff">
        <tr >
        	<td><img style="height:17px;" src="vas/sms/images/load/DownRight.png"/>
			<?php 
                if($operator!='none'){
                    foreach($operator as $row){
                        echo'<span><input class="opeType" type="radio"name="opeType" value="'.$row->fld_int_id.'"/>'.strtoupper($row->acronym).'</span>';
                    }
                }
            ?>
            </td>
        </tr>
    </table>
    
    <table id="senderIDs"  class="displayOff">
         <tr>
            <td><img style="height:17px;" src="vas/sms/images/load/DownRight.png"/>Sender ID</td>
             <td> : <input autocomplete="off" name="senderid" id="senderName" list="browsers" type="text" />
             	<datalist id="browsers">
                 <?php if($senderid!='none'){
						foreach($senderid as $row){
							echo '<option value="'.$row->fld_chr_senderid.'">';
						}
					 }
				  ?>
				</datalist>
             </td>
        </tr> 
        </table>
   </div>
   <!--<p id="msgNote" style="color:red; font-size:10px; margin:10px 0;">** Every new line character occupies 4 character length in SMS [ \r\n ]</p>-->
   <div id="contentTemp">
   		<h3>Enter Template </h3>
        <table>   	
        <tr>
        	<td>Header</td>
            <td> : <textarea class="textarea" id="tempHeader" name="header"></textarea></td>
        </tr>
        <tr>
        	<td>Footer</td>
            <td> : <textarea class="textarea" id="tempFooter" name="footer"></textarea></td>
        </tr>
        
    </table>
    </div>
    <p><input id="resetTem" class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){
$('.typeChoosen').on('click',function(e){
	if($(this).val()=='specific') $('#senderType').show();
	else{
		$('#senderType').hide();
		$('#senderType input').prop('checked', false);
		$('#senderIDs').hide();
		$('#senderIDs input[type="text"').val('');
		$('#operType').hide();
		$('#operType input').prop('checked', false);
	}
});
$('.sendertype').on('click',function(e){
	if($(this).val()=='specific'){
		$('#senderIDs').show();
		$('#operType').hide();
	} 
	else{
		$('#operType').show();
		$('#senderIDs').hide();
	}
});
$('#addMsgTemplate').submit(function(e) {
    e.preventDefault();
	var arr =[];
	if($('.typeChoosen:checked').val()=='all'){
		arr.push('typeChoosen=all');
	}
	else if($('.typeChoosen:checked').val()=='specific'){
		arr.push('typeChoosen=specific');
		if($('.sendertype:checked').val()=='default'){
			arr.push('sendertype=default');
			if($('.opeType:checked').val()===undefined){
				// error message
				obj.message_show("Operator Feild is required",'error');
			}
			else{
				arr.push('senderId='+$('.opeType:checked').val());
			}
		}
		else if($('.sendertype:checked').val()=='specific'){
			arr.push('sendertype=specific');
			if($('#senderName').val().replace(/ /g,'')===''){
				// error message
				obj.message_show("Sender ID Feild is required",'error');
			}
			else{
				arr.push('senderId='+$('#senderName').val().replace(/ /g,''));
			}
		}
		else{
			// error message
			obj.message_show("Sender ID Type Feild is required",'error');
		}
	}
	else{
		obj.message_show("Requst Type Feild is required",'error');
		// error message
	}
	if($('#tempHeader').val().replace(/ /g,'')!=''){
		arr.push('header='+$('#tempHeader').val());
	}
	if($('#tempFooter').val().replace(/ /g,'')!==''){
		arr.push('footer='+$('#tempFooter').val());
	}
	if($('#tempFooter').val().replace(/ /g,'')=='' && $('#tempHeader').val().replace(/ /g,'')==''){
		obj.message_show("Footer Feild or Header Feild is required",'error');
		return
	}
	console.log(arr);
	var res = obj.dhx_ajax('vas/sms/push_c/msgTemplate',arr.join('&') );
	if(res==='sucess'){
		obj.message_show('New Template has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderMsgTemplate');
		$(this)[0].reset();
		obj.wind.window('newmsgtemplate').close();
	}
	else{
		console.log((res));
		obj.message_show(res,'error');
	}
	
});
$('#resetTem').on('click',function(e){
	$('#senderType').hide();
	$('#senderIDs').hide();
	$('#operType').hide();
});

}());

</script>
<style>
#addMsgTemplate{ padding:10px; font-size:12px;}
#reqtype, #contentTemp{
	position:relative;
	border:1px solid #a4bed4;
	padding:10px;
}
#reqtype > table:nth-child(2){ padding:0 5px;
}
#reqtype > table:nth-child(3){ margin:5px 0 0 98px;
}
#reqtype > table:nth-child(4){ margin:5px 0 0 119px;}
#reqtype > table:nth-child(5){ margin:5px 0 0 124px;}
#contentTemp{ margin:10px 0;}
#reqtype input,#contentTemp  input{ padding:3px 10px;}

#reqtype > table:nth-child(4) tr td,#contentTemp table tr td{ padding:3px 5px;}
#reqtype h3, #contentTemp h3{ padding:2px 10px; background-color:white; position:absolute; top:-8px; left:15px;
color:blue;
}
#contentTemp table tr td input{ width:250px;}
.textarea { width:320px; height:50px;}
#contentTemp table tr td:first-child{ vertical-align:top;}
#addMsgTemplate p:last-child{ text-align:right;}
#addMsgTemplate p:last-child input{ margin:0 0 0 10px;}
#senderName,#tempHeader,#tempFooter{ font-size:12px;}

</style>















