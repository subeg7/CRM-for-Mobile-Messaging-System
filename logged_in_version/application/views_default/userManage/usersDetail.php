<div id='userDetail'>
	<table>
    	<tr>
        	<td>Organization</td>
            <td>: <?php echo ucwords($userDetail['detail'][0]->company); ?></td>
            <td>Phone Number</td>
            <td>: <?php echo $userDetail['detail'][0]->orgphone; ?></td>
        </tr>
        <tr>
        	<td>Contact Person </td>
            <td>: <?php echo ucwords($userDetail['detail'][0]->person); ?></td>
            <td>Contact Person Number</td>
            <td>: <?php echo $userDetail['detail'][0]->personnumber; ?></td>
        </tr>
        <tr>
        	<td>Located Country</td>
            <td>: <?php echo strtoupper($userDetail['country'][0]->fld_chr_acro); ?></td>
            <td>User Group</td>
            <td>: <?php echo strtoupper($userDetail['group'][0]->name); ?></td>
        </tr>
        <tr>
        	<td>Username</td>
            <td style="text-transform:none !important;">: <?php echo $userDetail['detail'][0]->username; ?></td>
            <td>User E-mail</td>
            <td>: <?php echo $userDetail['detail'][0]->email; ?></td>
        </tr>
        <tr>
        	<td>User ID</td>
            <td>: <?php echo $userDetail['detail'][0]->tranid; ?></td>
            <td>User Balance Type</td>
            <td>: <?php echo strtoupper($userDetail['detail'][0]->baltype); ?></td>
        </tr>
        <tr>
        	<td>User Reseller</td>
            <td>: <?php echo ucwords($userDetail['detail'][0]->resname); ?></td>
            <td>Address</td>
            <td>: <?php echo ucwords($userDetail['detail'][0]->address); ?></td>
        </tr>
        
    </table>
    <table>
    	<?php if(isset($userDetail['api']) && $userDetail['api']!=NULL){?>
        <tr>
        	<td>Api Key</td>
            <td>: <?php echo $userDetail['api'][0]->fld_api_key; ?></td>
        </tr>
        <?php } 
        if(isset($userDetail['route']) && $userDetail['route']!=NULL){?>
        <tr>
        	<td>Pull key</td>
            <td>: <?php echo $userDetail['route'][0]->nonce; ?></td>
        </tr>
        <tr>
        	<td>Route Url</td>
            <td>: <?php echo $userDetail['route'][0]->fld_route_url; ?></td>
        </tr>
        <?php } ?>
    </table>
    <h1>Assigned Privileges :-</h1>
	<div>
    	<div><ul>
    	<?php 
			foreach($userDetail['privileges']['privileges'] as $key=>$val){
				echo '<li><span>'.$key.' </span><span> : &nbsp;&nbsp;&nbsp;'.$val.'</span></li>';
			}
		?>
        </ul></div>
    </div>

</div>

<style>
#userDetail{ padding:10px; font-size:12px;}
#userDetail table tr td:first-child,#userDetail table tr td:nth-child(3){ color:#b30f0f;}
#userDetail table tr td:nth-child(2),#userDetail table tr td:nth-child(4){ color:#353535;}
#userDetail table tr td{padding:3px;}
#userDetail div{
	border: 1px solid #a4bed4; padding:10px; position:relative; margin-top:15px;
}
#userDetail h1{ color:blue; margin-top:10px;}
#userDetail > div{ margin-top:5px; padding-top:5px; height:250px; overflow:auto;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;    box-sizing: border-box; }
#userDetail div h3{ position:absolute; top:-10px; background-color:white; left:15px; padding:3px 5px; color:blue;}
#userDetail div ul li{ padding:2px 5px;}
#userDetail div ul li span:first-child{ color:#018287;}
#userDetail div ul li span:last-child{ color:#353535;}
</style>















