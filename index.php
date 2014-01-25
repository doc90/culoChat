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
			 
			<form action="index.php?a=send" method="post">
				<input type="text" style="width:90%" name="txt" autofocus="true"/>
				<input type="hidden" name="userid" value =<?php echo '"'.$_SESSION['userid'].'"' ?>/>
				<input type="submit" value="Invia" />
			</form>
			<iframe src="chat.php" id="chat" height="85%" width="100%" frameborder="0"></iframe>
			<div style="text-align:right;font-size:10px;"><br />codice scritto "alla brutto dio". se qualcuno vuol metterci le mani -> <a href="www.rar" style="text-align:right;font-size:10px;">download</a></div>
			<?php

		break;
	
	case 'send':
		if(!isset($_SESSION['logged']) || !($_SESSION['logged']==1))
			header("location: index.php");
		$db=connetti();
		$query='insert into messages(userid, timestamp, content) values ('.$_POST['userid'].', now(), \''.utf8_encode(mysql_escape_string($_POST['txt'])).'\')';
		//echo $query;
		esegui($db, $query);
		header("location: index.php?a=chat");
		break;	
	
	case 'login':
		try{
			$db=connetti();
			
			$query="select * from users where nick='".$_POST['user']."' and pass='".sha1($_POST['pass'])."'";
			$result=seleziona($db, $query);
			if (get_num_rows($result)>0){
				$riga=scorri_record($result);
				$_SESSION['userid']=$riga['id'];
				$_SESSION['logged']=1;
				$_SESSION['user']=$_POST['user'];
				header("location: index.php?a=chat");
			}
		}
		catch(exception $e){
			header("location: index.php");
		}
		break;
		
	case 'register':
		?>
		<form action="index.php?a=newuser" method="post">
			Nick <input type="text" name="nick"><br />
			Pass <input type="password" name="pass"/><br />
			Ripeti pass <input type="password" name="pass2" /><br />
			<input type="submit" value="Registrati" />
		</form>
		<?php
		
		break;
		
	case 'newuser':
		if(!isset($_POST['nick']) || !isset($_POST['pass']) || !isset($_POST['pass2']))
			echo 'd';
			//header("location: index.php");
		if($_POST['pass'] != $_POST['pass2'])
			echo 'a';
			//header("location: index.php");
		
		$db=connetti();
		$query="select * from users where nick='".$_POST['nick']."'";
		$result=seleziona($db, $query);
		if(get_num_rows($result)>0){
			echo 'nick già in uso. <a href="index.php?a=register">indietro</a>';
			exit;
		}
		$query="insert into users(nick, pass) values ('".$_POST['nick']."','".sha1($_POST['pass'])."')";
		esegui($db, $query);
		echo "bella zio, sei registrato. ora loggati <a href='index.php'>qui</a>";
		break;
	
	default: 
		
		?>
		
		<form action="index.php?a=login" method="post">
			Username <input type="text" name="user"/>
			Password <input type="password" name="pass" />
			<input type="submit" value="Login" />
			<a href="index.php?a=register">Registrati</a>
		</form>
		
		<?php
		
		break;
}


?>
</body>
</html>