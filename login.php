<?php
session_start();
include("funz.php");
$action='';
if (isset($_GET['a']))
	$action=$_GET['a'];

switch ($action) {
	case 'login':
		try{
			if((strlen($_POST['pass'])==0) || (strlen($_POST['nick'])==0))
			header("location: index.php");
			
			$db=connetti();
			
			$query='select id from users where lcase(nick)=? and pass=?';
			$parametri=array(strtolower($_POST['user']), sha1($_POST['pass']));
			$result=SqlQuery($db, $query, $parametri);
			if (get_num_rows($result)>0){
				$riga=scorri_record($result);
				session_destroy();
				session_start();
				$_SESSION['logged']=1;
				$_SESSION['user']=serialize(load_user($riga['id']));
				header("location: index.php?a=chat");
			}
			else {
				header("location: index.php");
			}
		}
		catch(exception $e){
			header("location: index.php");
		}
		break;
		
	case 'register':
		?>
		<script>
		function validateForm()
		{
			var nome=document.forms["new"]["nick"].value;
			if (nome==null || nome=="") {
				alert("Inserire un nick");
				return false;
			}
			
			var pass=document.forms["new"]["pass"].value;
			if (pass==null || pass=="") {
			  	alert("Il campo Password non può essere lasciato vuoto");
			  	return false;
			}
			
			var pass2=document.forms["new"]["pass2"].value;
			if (pass2!=pass) {
			  	alert("Le password non corrispondono");
			  	return false;
			}
		}
		</script>
		<form name="new" action="login.php?a=newuser" method="post" onsubmit="return validateForm()">
			Nick <input type="text" name="nick"><br />
			Pass <input type="password" name="pass"/><br />
			Ripeti pass <input type="password" name="pass2" /><br />
			<input type="submit" value="Registrati" />
		</form>
		<?php
		
		break;
		
	case 'newuser':
		if(!isset($_POST['nick']) || !isset($_POST['pass']) || !isset($_POST['pass2']))
			header("location: index.php");
		if($_POST['pass'] != $_POST['pass2'])
			header("location: index.php");
		if((strlen($_POST['pass'])==0) || (strlen($_POST['nick'])==0))
			header("location: index.php");
		
		$db=connetti();
		$query="select * from users where lcase(nick)=?";
		$parametri=array(strtolower($_POST['nick']));
		$result=SqlQuery($db, $query, $parametri);
		if(get_num_rows($result)>0){
			echo 'nick già in uso. <a href="login.php?a=register">indietro</a>';
			exit;
		}
		$query="insert into users(nick, pass) values (?, ?)";
		$parametri=array($_POST['nick'], sha1($_POST['pass']));
		SqlQuery($db, $query, $parametri);
		
		$query="insert into user_groups (select null, id, ? from users where nick=?)";
		$parametri=array(1,$_POST['nick']);
		SqlQuery($db, $query, $parametri);

		echo "bella zio, sei registrato. ora loggati <a href='index.php'>qui</a>";
		break;
	
	case 'out':
		session_destroy();
		header("location: index.php");
		break;
		
	default:
		echo 'cazzo fai?';
		break;
}

?>