<?php
				
try {
	$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
	exit($e->getMessage());
}
        include 'openid.php';
        try
        {
            $openid = new LightOpenID('http://'.$_SERVER['SERVER_NAME'].'/');
            if (!$openid->mode) {
                $openid->identity = 'http://steamcommunity.com/openid/';
                header('Location: '.$openid->authUrl()); 
			} elseif ($openid->mode == 'cancel') {
				echo '';
			} else {
				if ($openid->validate()) {

					$id = $openid->identity;
					$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
					preg_match($ptn, $id, $matches);

					$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=7ABB3D713E5E4685D91656AA54952CE4&steamids=$matches[1]";
					$json_object = file_get_contents($url);
					$json_decoded = json_decode($json_object);
					foreach ($json_decoded->response->players as $player) {
						$steamid = $player->steamid;
						$name = $player->personaname;
						$avatar = $player->avatarmedium;
					}
					
					$hash = md5($steamid . time() . rand(1, 50));
					$sql = $db->query("SELECT * FROM `users` WHERE `steamid` = '" . $steamid . "'");
					$row = $sql->fetchAll(PDO::FETCH_ASSOC);
					if (count($row) == 0) {
						$db->exec("INSERT INTO `users` (`hash`, `steamid`, `name`, `avatar`) VALUES ('" . $hash . "', '" . $steamid . "', " . $db->quote($name) . ", '" . $avatar . "')");
					} else {
						$db->exec("UPDATE `users` SET `hash` = '" . $hash . "', `name` = " . $db->quote($name) . ", `avatar` = '" . $avatar . "' WHERE `steamid` = '" . $steamid . "'");
					}
					foreach($row as $key => $array){
						if($array["Admin"] == 1 ) setcookie('admin', "yes", time()+86400, '/');
					}
					if(stripos($name, 'sazone.lt') !== false){
						setcookie('bonus', 2 , time()+86400, '/');
					}
					setcookie('user', $avatar, time()+86400, '/');
					setcookie('who',$steamid, time()+86400, '/');
					header('Location: http://www.sazone.lt/Newproject/index.php');
				}
			}
		} catch (ErrorException $e) {
			exit($e->getMessage());
		}
?>