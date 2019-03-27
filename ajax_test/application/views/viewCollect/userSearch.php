<div id="usersearch">
	<form>
    	<table>
        	<tr>
            	<td>User ID</td>
                <td><input type="number" name="userid"></td>
            </tr>
        	<tr>
            	<td>Name</td>
                <td><input type="text" name="name"></td>
            </tr>
            <?php if($admin =='admin'){?>
            <tr>
            	<td>Reseller</td>
                <td><input type="text" name="reseller"></td>
            </tr>
            <?php }?>
            <tr>
            	<td>Feature</td>
                <td><select name="feature">
                	<option value="none">--Select--</option>
                    <?php 
					if($feature!='none'){
						foreach($feature as $row){
							echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_feature.'</option>';
						}
					}
					?>
                </select></td>
            </tr>
            <tr>
            	<td>Group Type</td>
                <td><select name="group">
                	<option value="none">--Select--</option>
                    <?php 
					if($group!='none'){
						foreach($group as $row){
							echo '<option value="'.$row->id.'">'.$row->name.'</option>';
						}
					}
					?>
                </select></td>
            </tr>
            <tr>
            	<td>State</td>
                <td><select name="state">
                	<option value="none">--Select--</option>
                	<option value="online">Online</option>
                    <option value="offline">Ofline</option>
                </select></td>
            </tr>
        </table>
        <p><input class="button" type="reset" id="searchReset" value="Reset"/><input class="button" id="userSubmit" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>

<script type="text/javascript">

$('#usersearch form').submit(function(e) {
	
    e.preventDefault();
	var query = 'userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend')+'/search';
	obj.grid['dhxDynFeild_t'].clearAndLoad( query+"?object=grid&"+$(this).serialize(),function(e){
	obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
		if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');
	} );
	
});
$('#searchReset').click(function(e) {
        obj.grid['dhxDynFeild_t'].clearAndLoad( 'userManage_c/renderUser/'+( (obj.ribb['ribbon'].getValue('combo_st')==1)?'approve':'suspend')+"?object=grid");
		obj.searchQuery = '';
    });

</script>
<style>
#usersearch{ -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#usersearch,#usersearch table tr td,#usersearch input,#usersearch select{
	font-size:12px; 
}
#usersearch form{-webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box;}
#usersearch table{ margin:0 auto;}
#usersearch table input,#usersearch table  select{ width:150px; padding:3px 5px;}
#usersearch table tr td{ padding:3px 8px;}
#usersearch form > p { text-align:right;  margin-top:13px;}
#usersearch form > p input:last-child{ margin-left:10px;}
</style>
