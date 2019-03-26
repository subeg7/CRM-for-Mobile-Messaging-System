<style>
#homeView{ height:490px; font-size:12px; width:100%;
background-color:#fbfbfb;
margin-top:15px;
}
#homeView h2{ font-size:15px; font-weight:normal; color:white;background-color:#0078d7;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding:15px 15px;
}
#homeView h2 img{ vertical-align:middle; margin-right:10px; width:18px;}
.homeDiv{ margin:20px;}
#homeLeft{ width:550px; }
#homeRight{  width:320px; margin:20px 30px 0 0;}
#homeRight h3{font-size:14px;  padding-bottom:9px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;

}
.homeDiv h3{ font-size:14px; color:black; padding-bottom:10px; margin-bottom:5px; border-bottom:1px solid #d1d1d1; }
.homeDiv h3 img, #homeRight h3 img{ vertical-align:middle; margin-right:10px; width:16px;}
.homeDiv table{ margin-left:30px;}
.homeDiv table tr td { padding:4px 5px; font-size:12px; color:#635f5f;}
.homeDiv table tr td:last-child { color:#0078d7;}
#homeNotification{ width:100%; height:320px; border:2px solid #c3c6c9; background-color:white;
padding:10px; overflow:auto;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
#homeNotification ul { margin:0 0 0 10px;}
#homeNotification ul li { line-height:14px; font-size:11px; padding:3px 30px 3px 5px; list-style:circle; border:1px solid #d1d1d1; margin-bottom:5px;
position:relative;
}
#homeNotification ul li span{color:#6c6d6f; }
#homeNotification ul li img{ width:15px; position:absolute; right:5px; top:3px; cursor:pointer;}
#homeNotDelete { margin-top:10px;}
</style>

<div id="homeView">
	<h2><img src="images/load/enterhome.png"/>Welcome </h2>
    <div id="homeLeft" class="floatLeft">
        <div id="homeBalance" class="homeDiv">
            <h3><img src="images/load/homeBalance.png"/>Total Account balance till today </h3>
            <table>
            	<?php
				if($balance=='postpaid'){
					foreach($operator as $row){
						echo '<tr>
								<td>'.$row.'</td>
								<td>: POSTPAID</td>
							</tr>';
					}
				}elseif($balance=='none'){
					foreach($operator as $row){
						echo '<tr>
								<td>'.$row.'</td>
								<td>: ----</td>
							</tr>';
					}
				}else{
					foreach($balance as $key=>$row){
						echo '<tr>
								<td>'.$key.'</td>
								<td>: '.$row.'</td>
							</tr>';
					}
				}
				?>

            </table>
        </div>
        <div id="homeTransaction"  class="homeDiv">
            <h3><img src="images/load/homeTransaction.png"/>Today's Total Transaction </h3>
            <table>
            	<?php
					if($transaction !='none'){
						foreach($transaction as $key=>$row){
							echo '<tr>
								<td>'.$key.'</td>
								<td>: '.$row.'</td>
							</tr>';
						}
					}else{
						foreach($operator as $row){
						echo '<tr>
								<td>'.$row.'</td>
								<td>: 000 </td>
							</tr>';
					}
					}
				?>

            </table>
        </div>
        <div id="homeTransaction"  class="homeDiv">
            <h3><img src="images/load/homeInfo.png"/>Account Informations </h3>
            <table>
                <tr>
                    <td>Organization Name</td>
                    <td> : <?php echo ucwords($data->company); ?></td>
                </tr>
                <tr>
                    <td>Email  </td>
                    <td> : <?php echo $data->email; ?></td>
                </tr>
                <tr>
                    <td>Contact Person </td>
                    <td> : <?php echo ucwords($data->contact_person); ?></td>
                </tr>
                <tr>
                    <td>Mobile Number  </td>
                    <td> : <?php echo $data->contact_number; ?></td>
                </tr>
                <tr>
                    <td>Contact Number  </td>
                    <td> : <?php echo $data->phone; ?></td>
                </tr>
                <tr>
                    <td>Address  </td>
                    <td> : <?php echo ucwords($data->address); ?></td>
                </tr>

            </table>
        </div>
    </div>
    <div id="homeRight" class="floatRight">
    	<h3><img src="images/load/homeNotification.png"/>Quick Unseen Notification [ <span style="color:red;">within 7 days</span> ]</h3>
        <div id="homeNotification">
        	<ul>
            <?php
				if($notification!='none'){
					foreach($notification as $row){
						echo '<li data-id="'.$row->fld_int_id.'">'.$row->description.'<img src="images/load/homeClose.png"/></li>';
					}
				}else{
					echo '<p><span class="red">****</span> No Notification found for display <span class="red">****</span></p>';
				}
			?>

            </ul>

        </div>
        <button  id="homeNotDelete" class="button"> Clear All</button>
    </div>
    <div class="clearBoth" style="height:0;"></div>
</div>
<script language="javascript" type="text/javascript">
$('#homeNotification ul li img').on('click',function(e){
	var notiId = $(this).parent('li').data('id');
	$('#dhxDynFeild div#load').show();
	var res = obj.dhx_ajax('common_c/noticeViewState/'+notiId );
	if(res=='sucess'){

		$(this).parent('li').remove();
		if($('#homeNotification ul li').length== 0){
			$('#homeNotification').append( '<p><span class="red">****</span> No Notification found for display <span class="red">****</span></p>');
		}
	}
	else{
		obj.message_show(res,'error');
	}

	setTimeout(function(){  $('#dhxDynFeild div#load').hide(); }, 900);
});
$('#homeNotDelete').on('click',function(e){
	var notiIdArr = [];
	if($('#homeNotification ul li').length== 0){
		obj.message_show('** : There is No Notification found');
	}else{
		$('#homeNotification ul li').each(function() {
			notiIdArr.push($(this).data('id'));
		});
		$('#dhxDynFeild div#load').show();
		// console.log("calling ajax");
		var res = obj.dhx_ajax('common_c/noticeViewState/'+notiIdArr.join('_') );
		// console.log("home view is called");

		if(res=='sucess'){
			//obj.message_show('Operation Sucessfull');
			$('#homeNotification ul').empty();
			$('#homeNotification').append( '<p><span class="red">****</span> No Notification found for display <span class="red">****</span></p>');
		}
		else{
			obj.message_show(res,'error');
		}

		setTimeout(function(){  $('#dhxDynFeild div#load').hide(); }, 900);
	}
});
</script>
