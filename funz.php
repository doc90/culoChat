<?php
$ver='0.0.0';
header('Content-type: text/html; charset=UTF-8');
date_default_timezone_set('UTC');
$_HOME=$_SERVER['DOCUMENT_ROOT'];

include('/class/user.php');

define ("DEBUG", True);

if (file_exists($_HOME.'/include/config.php')) {
        $config = include $_HOME.'/include/config.php';
} else {
        echo "config.php non esiste!";
        exit;
}

if (file_exists($_HOME.'/include/patch.php') && !DEBUG) {
        echo 'Eliminare il file /include/patch.php';
}

///////////////////////
// FUNZIONI DATABASE //
///////////////////////

function connetti() {
        global $config;
        try {
                $db= new PDO("mysql:host=".$config['host']."; dbname=".$config['nomedb']."; charset=UTF8", $config['userdb'], $config['passdb']);
        }
        catch (PDOException $ex) {
                echo $ex->getMessage();
                exit;
        }
        return $db;
}

function SqlQuery (&$db, $query, $parametri=array()) {
        $r=$db->prepare($query);
        $r->execute($parametri);
        return $r;
}

function get_num_rows($result) {
        return $result->rowCount();
}

function scorri_record ($result) {
        return $result->fetch(PDO::FETCH_ASSOC);        
}



function load_user($id) {
	$user = new User();
	$db=connetti();
	$query="SELECT users.id as id, nick, groups.id as groupid, title FROM users left join user_groups on users.id=user_groups.iduser left join groups on user_groups.idgroup=groups.id where users.id=?";
	$parametri=array($id);
	$result=SqlQuery($db, $query, $parametri);
	if(get_num_rows($result)>0)
	{
		$riga=scorri_record($result);	
		
		$user->id=$riga['id'];
		$user->nick=$riga['nick'];
		$user->group=$riga['groupid'];
		$user->grouptitle=$riga['title'];
		
		$query='select * from permissions left join group_permission on permissions.id=group_permission.idpermission where idgroup=?';
		$parametri=array($user->group);
		$result=SqlQuery($db, $query, $parametri);
		while($riga=scorri_record($result)) {
			$user->perm[$riga['permission']]=true;;
		}		
	}
	return $user;
}

?>