<div id="adressbookdetail">
	<div>
    	<h2>Address Book Detail</h2>
	<table>
    	<tr>
        	<td>AddressBook Name</td>
            <td> : <?php echo ucwords($addressDetail[0]->fld_chr_name);?></td>
        </tr>
        <tr>
        	<td>Description:</td>
            <td> : <?php echo ucwords($addressDetail[0]->fld_chr_desc);?></td>
        </tr>
        <tr>
        	<td>Created On:</td>
            <td> : <?php date_default_timezone_set('Asia/Kathmandu'); echo date('Y-m-d H:i:s',$addressDetail[0]->fld_chr_ondate);?></td>
        </tr>
       
    </table>
    </div>
    <div>
    	<h2>Cell Number Count</h2>
	<table>
        <?php 
		foreach($counts as $key=>$val){
			echo '<tr><td>'.$key.'</td><td> : '.$val.'</td></tr>';
		}
		?>
    </table>
    </div>
</div>
<style>
#adressbookdetail { font-size:12px; padding:10px;; -webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; }
#adressbookdetail > div{ padding:10px; border: 1px solid #a4bed4; margin-top:5px; position:relative;; -webkit-box-sizing: border-box; 
-moz-box-sizing: border-box; 
box-sizing: border-box; }
#adressbookdetail > div h2{ color:blue; position:absolute; top:-8px; left:15px; padding:2px 10px; background-color:white;}
#adressbookdetail > div:last-child{ margin-top:15px;}
#adressbookdetail > div table tr td{ padding:3px 5px;}
#adressbookdetail > div table tr td:first-child{ color:#951313;}
</style>
<?php 
/*var_dump($counts);
var_dump($addressDetail);
*/