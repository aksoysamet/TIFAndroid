<?php
header("Content-Type: application/json;charset=utf-8");
include_once 'include/db.php';
$db = new DbConnectLocalPanel();
$query = "select * from habericerik ORDER BY `added_date` DESC";
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
$json2['haberler'] = $json;
echo json_encode($json2);
$db->closeDB();
?>