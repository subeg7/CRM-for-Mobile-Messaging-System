<div id="searchKeys">
    <form action="#">
    	<table>
        	<tr>
            	<td>Main key</td>
                <td><input type="text" name="keyname"/></td>
            </tr>
            <tr>
            	<td>State</td>
                <td><select name="state">
                	<option value="none">--Select--</option>
                	<option value="enable"> Endabled </option>
                    <option value="disable"> Disabled </option>
                </select></td>
            </tr>
            <tr>
            	<td>Shortcode</td>
                <td><select name="shortcode">
                	<option value="none">--Select--</option>
                	<?php
						if($shortcode!='none'){
							foreach($shortcode as $row){
								echo '<option value="'.$row->id.'">'.strtoupper($row->name).'</option>';
							}
						}
					?>
                </select></td>
            </tr>
            <tr>
            	<td>Category</td>
                <td><select name="category">
                	<option value="none">--Select--</option>
                	<?php
						if($category!='none'){
							foreach($category as $row){
								echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->category).'</option>';
							}
						}
					?>
                </select></td>
            </tr>
        </table>
        <p><input id="searchReset" class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>
<script type="text/javascript">
(function(e){

	$('#searchReset').click(function(e) {
        obj.grid['dhxDynFeild_t'].clearAndLoad( "pull_c/renderKeys?object=grid");
		obj.searchQuery = 'push_c/renderSenderId';
    });
	$('#searchKeys form').submit(function(e) {
		e.preventDefault();
		if(obj.prev_id !== 'keys'){
			obj.message_show('** Invalid Scope for search','error');
			obj.wind.window('search').close();
			return;
		}
		if($('#searchKeys input[name="keyname"]').val().indexOf(' ') !== -1){
			obj.message_show('Main key Contains Space Character','error');
			return;
		}

		obj.grid['dhxDynFeild_t'].clearAndLoad( "pull_c/renderKeys/search?object=grid&"+$(this).serialize(),function(e){
			obj.searchQuery = (obj.grid['dhxDynFeild_t'].getUserData( "","query")).split('__').join('&');
			if(obj.grid['dhxDynFeild_t'].getUserData( "","session")==="message") obj.message_show(obj.grid['dhxDynFeild_t'].getUserData( "","message"),'error');
		} );
    });
}());
</script>
<style>
#searchKeys { font-size:12px; padding:10px;}
#searchKeys form table { margin:0 auto;}
#searchKeys form table tr td{
	padding:3px 5px;
}
#searchKeys form table tr td input{ padding:3px 10px; font-size:12px;}
#searchKeys form p { text-align:right; margin:10px 12px 0 0;}
#searchKeys form p input{ margin-left:5px;}
</style>
