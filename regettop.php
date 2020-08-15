<?php
/*if(!empty($_GET["now"]))$notime = 1;
else $notime = 0;*/
include_once 'include/db.php';
$db2 = new DbConnectLocalTops();
$query = "select utime from lastupdate where id = 1";
$deyim = $db2->getDb()->prepare($query);
$deyim->execute();
$rs = $deyim->fetch(PDO::FETCH_ASSOC);
$LastUpdateTimestamp = $rs["utime"];
if(/*$notime == 1 || */($rs["utime"] + 1800) < time())
{
	$db = new DbConnect();
	$query = "UPDATE lastupdate SET utime = ".time()." where id = 1";
	$deyim = $db2->getDb()->prepare($query);
	$deyim->execute();
	$LastUpdateTimestamp = time();
	$TopList = array("score", "money", "yariskazandi", "savaskazandi", "derbykazandi", "etkkazandi", "Kills", "onlinet", "Kills_Deaths");
	for($a = 0; $a < 8;$a++)
	{
		$query = "select kullaniciadi,".$TopList[$a]." from oyuncular ORDER BY ".$TopList[$a]." DESC LIMIT 50";
		$deyim = $db->getDb()->prepare($query);
		$i = 0;
		if ($deyim->execute())
		{
			$deyim2 = $db2->getDb()->prepare("TRUNCATE TABLE ".$TopList[$a]);
			if ($deyim2->execute())
			{
				while ($rs = $deyim->fetch(PDO::FETCH_ASSOC))
				{
					$query = "INSERT INTO ".$TopList[$a]." (kullaniciadi,".$TopList[$a].") VALUES (?,?)";
					$deyim2 = $db2->getDb()->prepare($query);
					$deyim2->bindParam(1, $rs["kullaniciadi"]);
					$deyim2->bindParam(2, $rs[$TopList[$a]]);
					$deyim2->execute();
				}
			}
		}
	}
	$query = "select kullaniciadi, (Kills/Deaths) as KD from oyuncular WHERE Kills >= 1000 ORDER BY KD DESC LIMIT 50";
	$deyim = $db->getDb()->prepare($query);
	$i = 0;
	if ($deyim->execute())
	{
		$deyim2 = $db2->getDb()->prepare("TRUNCATE TABLE Kills_Deaths");
		if ($deyim2->execute())
		{
			while ($rs = $deyim->fetch(PDO::FETCH_ASSOC))
			{
				$query = "INSERT INTO Kills_Deaths (kullaniciadi,".$TopList[$a].") VALUES (?,?)";
				$deyim2 = $db2->getDb()->prepare($query);
				$deyim2->bindParam(1, $rs["kullaniciadi"]);
				$deyim2->bindParam(2, $rs["KD"]);
				$deyim2->execute();
			}
		}
	}
	$db->closeDB();
}
$db2->closeDB();
?>