<div id="sentboxdetails">
	<div id="sentboxtabbar"></div>
</div>
<script type="text/javascript" language="javascript">
var operator = ('<?php echo $operator; ?>').split(',');
var details = JSON.parse('<?php echo $numbers; ?>');
var user = '<?php echo $user;?>';
var detailsNumber = {};
var state = {1:'sucess',2:'fail',4:'buffered',8:'sucess',16:'rejected',32:'sucess'}
var tabBars = obj.create_dhx_tabar({
						id:			'sentboxtabbar',
						tab_text:	operator,
					});
/*console.log(tabBars);
console.log(details);*/
for( i =0; i <details.length; i++){
	if(detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()] ==undefined)
		detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()] = [];
	if($.inArray('sentboxtabbar_tb_'+(details[i].acronym).toLowerCase(),tabBars) > -1){
		if(user=='admin'){
			if( (i+1)%4==0 || (i+1)==details.length ){
				
				detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()].push('[ '+details[i].fld_mobile_number+' - <span class="statecolor">'+state[details[i].admin_state]+'</span> ]</br></br>');
			}
			else{
				detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()].push('[ '+details[i].fld_mobile_number+' - <span class="statecolor">'+state[details[i].admin_state]+'</span> ]');
			}
		}else{
			if( (i+1)%4==0 || (i+1)==details.length )
				detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()].push('[ '+details[i].fld_mobile_number+' - <span class="statecolor">sucess</span> ]</br></br>');
			else
				detailsNumber['sentboxtabbar_tb_'+(details[i].acronym).toLowerCase()].push('[ '+details[i].fld_mobile_number+' - <span class="statecolor">sucess</span> ]');
		}
		
	}
}
for( i =0; i < tabBars.length; i++){
	if(detailsNumber[tabBars[i]]!=undefined)
		$('#'+tabBars[i]).empty().append( detailsNumber[tabBars[i]].join(' ') );
}

</script>
<style>
#sentboxdetails{ height:100%; width:100%;}
#sentboxtabbar{ height:450px; width:100%; font-size:12px;}
.statecolor{ color:red;}

</style>