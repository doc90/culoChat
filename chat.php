<html>
<head>
	<link rel="stylesheet" href="include/style.css" />
	<script src="include/jquery.js" type="text/javascript"></script>
	<?php
	session_start();
	include('funz.php');
	$db=connetti();
	$query="select count(*) as count from messages";
	$result=seleziona($db, $query);
	$count=0;
	if(get_num_rows($result)>0)
	{
		$riga=scorri_record($result);
		$count=$riga['count'];
	}
	echo '<script>var initialCount='.$count.';</script>';
	?>
	<script>
		
		function getCount()
		{
			$.getJSON("get.php", function(x){
				if(x.count > initialCount)
					location.reload();
			});
		};
		
		$(document).ready(function(){
		  $('#chat tr:even').css('background-color','#D4E3FC');
		});
	</script>
</head>
<body>
	<script>
		var r = self.setInterval("getCount()",500);
		
	</script>
<?php
if(!isset($_SESSION['logged']) || !($_SESSION['logged']==1))
	header("location: index.php");
$query='select * from messages m left join users u on m.userid=u.id order by timestamp desc limit 100';
$result=seleziona($db, $query);
$npost=get_num_rows($result);
echo '<div style="display:inline-block;width:88%">
<table id="chat" width="100%" cellpadding="1" cellspacing="2">';
for($i=0;$i<=$npost;$i++)
{
	$riga=scorri_record($result);
	echo '<tr><td><b>'.$riga['nick'].'</b></td><td style="font-size:11px;">'.utf8_decode(htmlentities($riga['content'])).'</td><td style="font-size:10px;" align="right">'.$riga['timestamp'].'</td></tr>';
}

$totUser=0;
$query="select count(*) as totuser from users";
$result=seleziona($db, $query);
if(get_num_rows($result)>0)
{
	$riga=scorri_record($result);
	$totUser=$riga['totuser'];
}

echo '</table></div>
<div style="display:inline-block;width:8%;vertical-align:top">
Messaggi: '.$count.'<br />
Utenti: '.$totUser.'<br /><br />
Top 5';

$query="select u.nick as nick, count(*) as tot from messages m left join users u on m.userid=u.id group by nick order by tot desc limit 5";
$result=seleziona($db, $query);
$npost=get_num_rows($result);
echo '<table width="100%" cellpadding="0" cellspacing="0">';
for($i=0;$i<=$npost;$i++)
{
	$riga=scorri_record($result);
	echo '<tr><td>'.$riga['nick'].'</td><td>'.$riga['tot'].'</td></tr>';
}

echo'</table></div>';
?></body></html>