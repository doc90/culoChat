<html>
	<head>
		<title>La chat senza l'internet</title>
		<link rel="stylesheet" href="include/style.css" />
	</head>
	<body>
<?php
session_start();
include("funz.php");
$action='';
if (isset($_GET['a']))
	$action=$_GET['a'];

switch ($action) {
	case 'chat':
			if(!isset($_SESSION['logged']) || !($_SESSION['logged']==1))
				header("location: index.php");
			?>
			<script>
			function validate() {
				var txt=document.forms["msg"]["txt"].value;
				if (txt==null || txt=="") {
				  	return false;
				}
			}
			</script>
			<form name="msg" action="index.php?a=send" method="post" onsubmit="return validate()">
				<input type="text" style="width:90%" name="txt" autofocus="true" autocomplete="off"/>
				<input type="submit" value="Invia" />
				<input type="button" value="Logout" onclick="location.href='login.php?a=out'"/>
			</form>
			<iframe src="chat.php" id="chat" height="85%" width="100%" frameborder="0"></iframe>
			<div style="text-align:right;font-size:10px;"><br />codice scritto "alla brutto dio". se qualcuno vuol metterci le mani -> <a href="www.rar" style="text-align:right;font-size:10px;">download</a></div>
			<?php

		break;
	
	case 'send':
		if(!isset($_SESSION['logged']) || !($_SESSION['logged']==1))
			header("location: index.php");
		$user=unserialize($_SESSION['user']);
		$db=connetti();
		$query='insert into messages(userid, timestamp, content) values (? , now(), ?)';
		$parametri=array($user->id, utf8_encode(mysql_escape_string($_POST['txt'])));
		SqlQuery($db, $query, $parametri);
		header("location: index.php?a=chat");
		break;	
	
	default: 
		
		?>
		
		<form action="login.php?a=login" method="post">
			Username <input type="text" name="user"/>
			Password <input type="password" name="pass" />
			<input type="submit" value="Login" />
			<input type="button" value="Registrati" onclick="location.href='login.php?a=register'"/>
		</form>
		
		<?php
		
		break;
}


?>
</body>
</html>