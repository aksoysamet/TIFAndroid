<?php
include_once 'include/db.php';
$db = new DbConnectLocalPanel();
$query = "select * from versiyon";
$deyim = $db->getDb()->prepare($query);
$json = array();
if ($deyim->execute())
{
	$json = $deyim->fetch(PDO::FETCH_ASSOC);
}
//$json["versiyon"] = "1.0.0";
//$json["onlyadmins"] = false;
echo json_encode($json);
?>