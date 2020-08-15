<?php
include_once 'include/db.php';
$db = new DbConnect();
$query = "select kullaniciadi,Level,Animator, LoggedIn from oyuncular where Level > 0 OR Animator > 0";
$deyim = $db->getDb()->prepare($query);
$json = array();
$i = 0;
if ($deyim->execute())
{
	while ($rs = $deyim->fetch(PDO::FETCH_ASSOC))
	{
		$json[$i++] = $rs;
	}
}
$json2['admins'] = $json;
echo json_encode($json2);
$db->closeDB();
?>