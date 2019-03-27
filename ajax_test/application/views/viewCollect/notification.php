<div id="notifications">
	<div id="notice_att">
    	<input style="width:100%;" id="todayNotic" type="button" class="button" value="Today"/>
        <p><input id="notice_more" style="margin:0;" type="checkbox" value="more"> More..</p>
        <form id="notice_form" style="display:none;">
        	<table>
            	<tr>
                	<td>Type</td>
                    <td><select style="width:126px; padding:2px 0;" name="type">
                    		<option value="none">--Select--</option>
                            <option value="1"> Addressbook </option>
                            <option value="2"> Que Job</option>
                            <option value="3"> Sender ID </option>
                            <option value="8"> Key </option>
                            <?php if($user=='admin'){?>
                            <option value="4"> Operator </option>
                            <option value="5"> Prefix </option>
                            <option value="6"> Group </option>
                            <option value="7"> Country </option>
                            <?php } ?>
                    	</select>
                    </td>
                </tr> 
                <?php if($user=='admin'){?>
                <tr>
                	<td>User ID</td>
                    <td><input name="usreid" type="number" style="width: 126px; padding:2px 0;"/></td>
                </tr> 
               	<?php } ?>
				<tr>
                	<td>From</td>
                    <td><input name="from" id="sDate" type="text" readonly style="width: 126px; padding:2px 0;"/></td>
                </tr>  
                <tr>
                	<td>Till</td>
                    <td><input name="till" id="tDate" type="text" readonly style="width: 126px; padding:2px 0;"/></td>
                </tr>
                <tr>
                	<td></td>
                    <td><input type="submit" value="submit"  class="button"/></td>
                </tr>            
            </table>
        </form>
    </div>
    <div id="notification_area">
    	<div id="notice_grid"></div>
    </div>
</div>

<script type="text/javascript">
	obj.create_dhx_calander({ 
				multi:'multiple',
				id:'noticeDate',
				dateformat:"%Y-%m-%d",
				param: [ 'sDate','tDate']
			});
	$('#notice_more').click(function(e) {
        if($(this).is(':checked')){
			$('#notice_form').show();
		}
		else{
			$('#notice_form').hide();
		}
    });
obj.dynamicGrid({ id:'notice_grid',header:"Date,Description",setInitWidths:'140,440',multiple:'multiple',attachHeader:false,pagesInGrp:6,pageSize:8});
$('#todayNotic').click(function(e) {
	obj.grid['notice_grid_t'].clearAndLoad('common_c/getTodayNotice');
});
$('#notice_form').submit(function(e) {
    e.preventDefault();
	obj.grid['notice_grid_t'].clearAndLoad('common_c/searchNotice'+"?object=grid&"+$('#notice_form').serialize(),function(){
	
		if(obj.grid['notice_grid_t'].getUserData( "","message")!=undefined && obj.grid['notice_grid_t'].getUserData( "","message")!=''){ obj.message_show('** Warning : '+obj.grid['notice_grid_t'].getUserData( "","message"),'error');
		}
	});
	
});
</script>
<style>
#notifications input ,#notifications select{ font-size:12px;}
#notifications{ width:100%; height:100%; overflow:auto; padding:10px;
-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;  
box-sizing: border-box;
font-size:12px;
}
#notifications > div{ float:left;}
#notice_att{
	width:180px; 
	height:99%;
	margin-right:10px;
	border:1px solid #d1d1d1;
	-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;
padding:10px;
}

#notification_area{
	width:580px; 
	height:99%; overflow:auto;
	-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;
}
#notice_att > p{ margin:15px 0;}
#notice_form { border-top:1px dotted #d1d1d1; padding-top:10px;
-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;}
#notice_form table tr td:last-child{ padding:3px 5px;}

#notice_form table tr:last-child td:last-child{ text-align:right;}
#notification_area ul li{ margin-bottom:5px; font-size:12px;}
</style>
