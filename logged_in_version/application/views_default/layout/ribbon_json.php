<script language="javascript" type="text/javascript">
var ribbonJson = [
			{id: "sms", text: "SMS", items: [
				{type: "block", text: "send sms",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"sendsms",text: "Send SMS",isbig: true, img: "/sendsms.png" },
				]},
				{type: "block", text: "contacts",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"addressbook",text: "Address Book",isbig: true, img: "/adressbook.png" },
				]},
				{type: "block", text: "scheduler",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"scheduler",text: "Scheduler",isbig: true, img: "/scheduler.png" },
				]},
				{type: "block", text: "template",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"template",text: "Templates",isbig: true, img: "/template.png" },
				]},
				
			]},
			{id: "users", text: "Users", items: [
				{type: "block", text: "Users",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"userList", text: "Users List" , isbig: true, img: "/users.png"},
					{type:"buttonCombo", id: "combo_st",text:'', items: [
						{value: "1", text: "Approved Clients", selected: true},
						{value: "0", text: "Suspended Clients"}
					]},
					{type: "button", id:"gen_exl_list", text: "Generate EXCEL" , img: "/excel.png"},
					
				]},
				{type: "block", text: "add/remove & report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"cl_sms_tran", text: "Client Transaction Report",  img: "/transaction.png"},
					{type: "button", id:"addBal", text: "Add/remove Balance",  img: "/balance.png"},				
					{type: "button", id:"rst_pass", text: "Password Reset" , img: "/reset.png"},
				]},
				{type: "block", text: "assign",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"assignshortcode", text: "Assign ShortCode" , img: "/assignshortcode.png"},				
					{type: "button", id:"assigngateway", text: "Assign Gateway" , img: "/assigngateway.png"},
				]},
				
									
			]},
			{id: "push", text: "Push", items: [
				{type: "block", text: "cron",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"cron",isbig: true,text: "Cron", isbig: true, img: "/cron.png"},
				]},
				{type: "block", text: "gateway",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"gateway", isbig: true,text: "Gateway", img: "/gateway.png"},
				]},
				{type: "block", text: "sender ID",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"sender_id",isbig: true,text: "Sender ID",  img: "/senderid.png"}					
				]},
				{type: "block", text: "package",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"package",isbig: true,text: "Packages", img: "/package.png"},
				]}
			]},
			{id: "pull", text: "Pull", items: [
				{type: "block", text: "shortcode",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"shortcode",isbig: true,text: "Shortcode", isbig: true, img: "/shortcode.png"},
				]},
				{type: "block", text: "category",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"category", isbig: true,text: "Category", img: "/category.png"},
				]},
				{type: "block", text: "keys",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"keys",isbig: true,text: "Key List",  img: "/keys.png"}					
				]},
				{type: "block", text: "uploaddb",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"upload",isbig: true,text: "upload DB", img: "/uploaddb.png"},
				]},
				{type: "block", text: "scheme",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"scheme",isbig: true,text: "Scheme",  img: "/scheme.png"}
				]}
			]},
			{id: "manage", text: "Manage", items: [
				{type: "block", text: "groups",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"group",isbig: true,text: "Groups",  img: "/usergroup.png"}
				]},
				{type: "block", text: "cell prefix",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"prefix", isbig: true,text: "Cell Prefix", img: "/prefix.png"},
				]},
				{type: "block", text: "operator",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"operator",isbig: true,text: "Operator",  img: "/operator.png"}
				]},
				{type: "block", text: "county",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"country",isbig: true,text: "Country",  img: "/country.png"}
				]}
			]},
			{id: "report", text: "Report", items: [
				{type: "block", text: "push report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"smsreport", text: "Transaction",isbig: true, img: "/transaction.png" },
					{type: "button", id:"creditlog",text: "Credit",isbig: true, img: "/creditreport.png" },
					{type: "button", id:"sentbox",text: "Sent",isbig: true, img: "/sentbox.png" },
					{type: "button", id:"dailyreport",text: "Daily Report",isbig: true, img: "/dailyreport.png" },
				]},
				{type: "block", text: "pull report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"pullreport", text: "Report",isbig: true, img: "/pullreport.png" },
					{type: "button", id:"error_report",text: "Error",isbig: true, img: "/errorreport.png" },
					{type: "button", id:"feedback",text: "Feedback",isbig: true, img: "/feedback.png" },
					
				]}
			]},
			
			
		];
</script>