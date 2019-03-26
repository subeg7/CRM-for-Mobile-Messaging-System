<form id="addBalance" action="#">

	<table>
    	<tr>
        	<td>Balance Category :</td>
            <td><p><?php echo strtoupper($balType);?></p></td>
        </tr>
        <?php if( strtoupper($balType)=='SEPERATE' || $usermanage === TRUE){?>
        <tr>
        	<td>Balance Type :</td>
            <td><select name="operator">
            	<option value="none" >--Select--</option>
                <?php if($usermanage === TRUE){
					echo '<option value="appbal" >App. Balance</option>';
				}?>
                <?php
							foreach($operator as $row){
								echo '<option value="'.$row->fld_int_id.'" >'.strtoupper($row->acronym).'</option>';
							}

				?>
            </select></td>
        </tr>
		<?php  } ?>

        <tr>
        	<td>Balance Unit :</td>
            <td><input autocomplete="off" name="unit" type="text" /></td>
        </tr>
        <tr>
        	<td>Description :</td>
            <td><textarea name="description" cols="15" role="6" ></textarea></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

$('#addBalance').submit(function(e) {
    e.preventDefault();
	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;
	var res = obj.dhx_ajax('userManage_c/addBalance',$(this).serialize()+'&userid='+selId );
	if(res==='sucess'){
		obj.message_show('Balance Has been Updated Sucessfully');
		obj.winTooble = true;
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}

});

</script>
<style>
#addBalance { font-size:12px;}
#addBalance table{ margin:0 auto; padding-top:15px;}
#addBalance table tr td { padding:3px 5px;}
#addBalance table tr td input,#addBalance table tr td select,#addBalance table tr td textarea,#addBalance table tr td p{ padding:4px 6px; font-size:12px; margin-left:5px;}
#addBalance >p{ text-align:right; padding-right:24px; margin-top:10px;}
#addBalance >p input{ margin-left:5px;}
</style>
