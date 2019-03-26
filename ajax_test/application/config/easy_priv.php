<?php 
/*************** Easy Vas application privilege configuration*******************/
// this is hybrid privileges for both push and pull 
$config['PRIVILEGES'] = array(
							'USER_MANAGE'=>'this pirvileges allows to create users',
							'PUSH'=>'this pirvileges allows to send SMS',
							'PULL'=>'this pirvileges allows to do all pull operations',
						);
$config['USER_MANAGE_PRIV'] = array( 
								'USER_VIEW'=>'User View only privilege ',
								'USER_MANAGE'=>'User Manage privilege',
						 );
// ADMIN privillages : this privileges is only accessible  to admin 
$config['ADMIN_PRIV'] = array( 
								
								'GATEWAY_MANAGE'=>'gateway manage',
								'SENDERID_ASSIGN'=>'sender id authorization',
								'CATEGORY'=>'pull key category ',
								'SYSTEM_MANAGE'=>'system manage',
								'SHORTCODE_MANAGE'=>'shortcode manage',
								'ASSIGN_FEATURE'=>'assign features',
						 );
// PUSH privileges
$config['PUSH_PRIV'] = array( 
								'SMS_ADDERSS_SCHEDULE_TEMPLATE'=>'send sms , address book, schedule and templates',
								'CRON'=>'all cron process',
								'SENDERID_VIEW'=>'sender id view',
								'SENDERID_MANAGE'=>'sender id manage',
								'SENDERID_REQUEST'=>'sender id request',
								
								'PUSH_REPORT'=>'own push report',
								'REMOTE_USER'=>'This privileges refer to api using users',
								'GATEWAY_VIEW'=>'gateway view',
								'GATEWAY_ASSIGN'=>'gateway assign',
								'AVOID_SENDERID'=>'if senderid is not valid it sends with default sender id',
								
						 );
// PULL privileges
$config['PULL_PRIV'] = array( 
								'SHORT_CODE_ASSIGN'=>'user can asssign shortcode to other users',
								'SHORTCODE_VIEW'=>'shortcode view',
								'KEY_VIEW'=>'key view',
								'KEY_REQUEST'=>'key request',
								'KEY_MANAGE'=>'key manage',
								'PULL_REPORT'=>'own pull report',
								'REMOTE_ROUTE'=>'This privileges routes the pull requet to remote users address',
								'UPLOAD_DATA'=>'upload database',
								'SCHEME'=>'scheme manage',
						 );
						 
						 
						 
						 
// DEPENDENCY privileges ,when one privileges assigning one  privileges other privileges needed check
$config['DEPENDENT_PRIV'] = array( 
								'USER_MANAGE'=>'USER_VIEW', // to have user manage privilege the one must  have user view
								'GATEWAY_MMANAGE'=>'GATEWAY_VIEW',
								'GATEWAY_ASSIGN'=>'GATEWAY_VIEW',// to have gateway assign privileges must have gateway view but doesn't need gateway manage privileges because the gateway manage is admin privileges which won't be available to ther users this is for future  purpose
								'SENDERID_REQUEST'=>'SENDERID_MANAGE',
								'SENDERID_MANAGE'=>'SENDERID_VIEW',
								'KEY_MANAGE'=>'KEY_VIEW',
								'CRON'=>'SMS_ADDERSS_SCHEDULE_TEMPLATE | USER_MANAGE',
								'UPLOAD_DATA'=>'SCHEMES',
								'SCHEME'=>'UPLOAD_DATA',
								'PUSH_REPORT'=>'SMS_ADDERSS_SCHEDULE_TEMPLATE | USER_MANAGE',
								'PULL_REPORT'=>'KEY_MANAGE | USER_MANAGE',
								'SHORTCODE_MANAGE'=>'SHORTCODE_VIEW',
								'REMOTE_ROUTE'=>'KEY_REQUEST | KEY_MANAGE',
								'KEY_REQUEST'=>'KEY_MANAGE'
						 );
$config['CLOSED_PRIV'] = array( 
								'USER_MANAGE'=>'PULL|PUSH', // to have user manage privilege the one must not have  above privileges
								
						 );						 
						 
// assigning certain features , other transactions needed certain level of privileges on assigning one and assigned one
$config['ASSIGN_VERIFY']= array(
								'assign_balance'=>array('module'=>'PUSH','privilege'=>'SAST','TO'=>'user'),
								'reset_password'=>array('privilege'=>'UM','TO'=>'own'),
								'assign_shortcode'=>array('module'=>'PULL','privilege'=>'KEYM','TO'=>'user'),
								'assign_gateway'=>array('module'=>'PUSH','privilege'=>'SAST','TO'=>'user'),
						  );					 
						 
$config['BALANCE_TYPE']= array(		
								//'SINGLE'=>'In this balance type user have to allocate balance for individual operator',
								'SEPERATE'=>'In this  balance  type user have single balance for all operator',
								'POSTPAID'=> "In this balance tye user won't have any balance at all, all transaction will be determined at the usage quantity at certain time period and user no need to allocated balance for individual operator"
							);				 
						 
						 
						 
						 
						 
						 
						 
						 
?>