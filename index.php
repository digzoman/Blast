<!DOCTYPE html>
<html>
<head>
	<title>Balst by CrowdLinker</title>
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/list-selection.js"></script>
	<link type="text/css" rel="stylesheet" href="css/list-selection.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<style>
		body{color:gray;font-family:Calibri;}
		#linkeInConnections{max-width:720px;min-width:200px;}
		.image{border:5px solid #fff}
		.image {margin:5px;width:60px}
		.context{overflow:hidden;margin-left:78px;margin-top:7px;position:absolute}
		.context p{margin:0;padding:0;overflow:hidden;font-size:80%;width:140px}
		.context .headline{	font-style:italic;font-size:70%;}
		.context .name{font-weight:bold}
		
	</style>
	<script type="text/javascript" src="https://platform.linkedin.com/in.js">
		api_key: 8ro5j61ufm7u
		//scope:r_network,w_messages,rw_nus rw_groups,r_contactinfo,r_emailaddress,r_fullprofile r_basicprofile
		scope:r_network,w_messages
		authorize: false
credentials_cookie: true
		onLoad: onLinkedInLoad
	</script>
	<script>
		function onLinkedInLoad() {
			 IN.Event.on(IN, "auth", function() {onLinkedInLogin();});
			 IN.Event.on(IN, "logout", function() {onLinkedInLogout();});
		}		
		
		function onLinkedInLogin() {
			document.getElementById("linkeInConnections").innerHTML = "<img src='img/ajax_loader_gray_128.gif' />";
			IN.API.Connections("me")
				.fields("id", "firstName", "lastName", "pictureUrl", "publicProfileUrl","headline","location")
				.result(function(result, metadata) {
					setConnections(result.values, metadata);
				});			
		
			IN.API.Profile("me")  
			.fields("id", "firstName", "lastName", "pictureUrl", "publicProfileUrl","headline")	
			.result(function(result, metadata) {
				saveToken(result.values, metadata);
			});
		}	

		function saveToken(profile){
			$.post('https://secure1708.hostgator.com/~tazamed/crowdlinker.com/o/blast/savetoken.php', {"name" : profile[0].firstName + ' ' + profile[0].lastName } );
		}
		
		function onLinkedInLogout() {
			setConnections({}, {total:0});
		}			

		function iformat(string,s){			
			var l = string.length;
			if ( l > s )
				return string.substr(0,s-3)+'...';
			else
				return string.substr(0,s);
		}
		
		function setConnections(connections) {
			var connHTML = "<ul class='list-items'>";
			for (id in connections) {
				connHTML+="<li>";
					connHTML+= "<span class='context'>";
						name=connections[id].firstName+" "+connections[id].lastName;
						connHTML+="<p class='name'>"+iformat(name,25)+"</p>"		
						if ( connections[id].hasOwnProperty('headline') )
							connHTML+="<p class='headline'> "+iformat(connections[id].headline,50)+"</p>"							
						if ( connections[id].hasOwnProperty('location') )
							connHTML+="<p class='location'> "+connections[id].location["name"]+"</p>"		
					connHTML+= "</span>";
					connHTML+="<span class='ticked false'></span>";
					if (connections[id].hasOwnProperty('pictureUrl')) 
						connHTML+="<img class='image' src="+connections[id].pictureUrl+" />";
					else
						connHTML += "<img class='image'  src='img/icon_no_photo_80x80.png'>";
				connHTML+= "</li>";		
			}
			connHTML = connHTML + "</ul>" ;
			document.getElementById("linkeInConnections").innerHTML = connHTML;
			$.listSelection(".list-items",{height:80,width:220,cols:3,rows:3});
		}		
		
	</script>
</head>
<body>
	<br />Welcome to Blast by CrowdLinker <br />
	<hr />
	<script type="IN/Login"></script>
	<div id="linkeInConnections"></div>	
	<!-- <a id="btn" class="list_selection_ubuttons" onclick="return get();">Get Data</a>	-->
	<script>
		function get(){
				alert($.listSelection.getSelection(".list-items",".name"));
		}
	</script>
</body>
</html>