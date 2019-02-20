<form id="addKeys">
	<div>
    	<h2>Key Details</h2>
        <div>
        	<table>
                <tr>
                	<td>Category</td>
                    <td><select id="pcategory" name="category" style="width:134px;"><option value="none">--Select--</option>
							<?php 
                                
                                if($category!='none'){
                                    foreach($category as $row){
                                        if(strtolower($row->category) == 'rt' && $route =='route'){
                                            echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->category).'</option>';
                                        }elseif(strtolower($row->category) != 'rt'){
                                            if(strtolower($row->category) == 'is' && in_array('PULL',$priv)){
                                                echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->category).'</option>';
                                            }else{
                                                echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->category).'</option>';
                                            }
                                        }
                                    }
                                }
                            ?>
                            </select>
                    </td>
                </tr>
            	<tr>
                	<td>ShortCode</td>
                    <td><select id="pshortcode" name="shortcode" style="width:134px;"><option value="none">--Select--</option>
						<?php 
                            if($shortcode!='none'){
                                foreach($shortcode as $row){
                                    echo '<option value="'.$row->id.'">'.strtoupper($row->name).'</option>';
                                }
                            }
                        ?>
                        </select>
                     </td>
                </tr>
                <tr>
                	<td>Main Key</td>
                    <td><input type="text" name="mainkey"style="width:160px;" /></td>
                </tr>
                <tr id="authnumber">
                	<td>Numbers</td>
                    <td><textarea  name="numbers"  style="width:168px; height:25px; font-size:12px;"></textarea></td>
                </tr>
                <tr>
                	<td>Disable Message</td>
                    <td><textarea  name="disable" style="width:168px; height:55px; font-size:12px;"></textarea></td>
                </tr>
                <tr id="sucesMes">
                	<td>Sucess Message</td>
                    <td><textarea  name="sucess"  style="width:168px; height:55px; font-size:12px;"></textarea></td>
                </tr>
                <tr id="failMes">
                	<td>Failure Message</td>
                    <td><textarea  name="fail"  style="width:168px; height:55px; font-size:12px;"></textarea></td>
                </tr>
            </table>
        	
        </div>
    </div>
    <div>
    	<h2>Key Extra Info.</h2>
        <div id="keyExtra">
        </div>
    </div>
    <p style="clear:left"></p>
    <p><input type="reset" id="keyreset" class="button"/><input type="submit" value="Submit" id="keysub" class="button"/></p>
</form>
<script type="text/javascript">
(function(){

	$('#pcategory').change(function(e) {
		var fieilds = ['#failMes','#sucesMes','#authnumber'];
		var showFields = [];
		var cate =  ($(this).children('option:selected').text()).replace(/ /g,'');
		$('div#keyExtra').empty()
		if( cate=='VP' || cate=='PTP'){
			if( cate=='VP'){
				showFields.push('#failMes');
			}
			else{
				showFields.push('#authnumber');
			}
			$('div#keyExtra').load("vas/sms/pull_c/pullView/"+cate+"?object=load");
		}
		else if(cate=='FB'){
			showFields.push('#sucesMes');
		}
		else if(cate=='RT'){
			showFields.push('#failMes');
		}
		else{
			showFields.push('#failMes');
		}
		for(i=0; i < fieilds.length; i++){
			if($.inArray(fieilds[i],showFields) >-1){
				$(fieilds[i]).show();
			}
			else $(fieilds[i]).hide();
		}
		showFields = [];
	});
	$('#keyreset').click(function(e) {
        $('div#keyExtra').empty();
    });
	$('#addKeys').submit(function(e) {
        e.preventDefault();
		var cate = $('#pcategory').children('option:selected').text();
		var code = $('#pshortcode').val();
		var subkeys =[];
		if(cate=='VP' || cate=='PTP'){
			$('#keyExtra #subkeyFeild div').each(function(index, element) {
				
				subkeys.push( (cate=='VP')?$(this).children('p.subkey').text()+'_'+$(this).children('p.message').text()
				:$(this).children('p.subKey').text()+'_'+$(this).children('p.addbok').data('val')+'_'+$(this).children('p.temp').data('val')+'_'+$(this).children('p.message').text());
			});
		}
		
		var res = obj.dhx_ajax('vas/sms/pull_c/keys',$(this).serialize()+'&subkeys='+subkeys.join(','));
		if(res=='sucess'){
			obj.message_show('New Key Added Sucessfully'); 
			$(this)[0].reset();
			$('div#keyExtra').empty();
			obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/pull_c/renderKeys');
		}else{
			obj.message_show(res,'error'); 
		}
    });
}());


</script>
<style>
#addKeys{ font-size:12px; width:100%; height:100%; padding:15px 10px 10px 10px;-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;}

#addKeys input, #addKeys select{ padding:3px 5px; font-size:12px;}
#addKeys > div{ float:left; border: 1px solid #a4bed4; padding:10px; position:relative;-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;}
#addKeys > div:first-child{
	width:310px; height:280px;
}
#addKeys > div:nth-child(2){ width:512px; margin-left:10px; height:280px;}
#addKeys > div table tr td{ padding:3px 5px;}
#addKeys > div h2{ position:absolute; top:-8px; padding:2px 10px; background-color:white; color:blue;}
#addKeys > div:first-child div p span{ margin-right:15px;}
#addKeys > div:first-child div p span:nth-child(3){ margin-left:10px;}
#addKeys > div:first-child div p:last-child,#addKeys > div:first-child div p:nth-child(2){ margin-top:7px;}
#addKeys > div:first-child div p:last-child input,#addKeys > div:first-child div p:nth-child(3) input,#addKeys > div:first-child div p:nth-child(2) input{ width:350px;}
#addKeys > div:first-child div p{ margin-top:8px;}

#addKeys > p:last-child{ text-align:right; margin-top:10px;}
#addKeys > p:last-child input:last-child{ margin-left:10px; }
#sucesMes,#failMes,#authnumber{ display:none;}
#sucesMes input{ width:353px;}
.button {
    padding: 5px 16px !important;}
</style>




 

