<?php
require_once 'regettop.php';
$TopList = array("score", "money", "yariskazandi", "savaskazandi", "derbykazandi", "etkkazandi", "Kills", "onlinet", "Kills_Deaths");
$db = new DbConnectLocalTops();
$json2 = array();
for($a = 0; $a < 9;$a++)
{
	$query = "select * from ".$TopList[$a]." LIMIT 50";
	$deyim = $db->getDb()->prepare($query);
	$json = array();
	$i = 0;
	if ($deyim->execute())
	{
		while ($rs = $deyim->fetch(PDO::FETCH_ASSOC))
		{
			$json[$i++] = $rs;
		}
		$json2[$TopList[$a]] = $json;
	}
}
$json2["LastUpdate"] = $LastUpdateTimestamp;
echo json_encode($json2);
$db->closeDB();
?>