<?php
	$id = $_POST["who"];
	$identity = $_POST["ident"];
	$gameid = $_POST["game"];
	$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
	$url = 'http://steamcommunity.com/inventory/' . $id. '/' . $gameid. '/2?l=english&count=5000 ';
	$obj = json_decode(file_get_contents($url), true);
	if($obj["success"] == 1 ){
		if($gameid==730){
			$stmt = $db->prepare("SELECT * FROM `itemsinfo` WHERE `market_hash_name` = ?");
			$realinventory = array();
			if($identity=="bot") $updating = array();
			$assets = $obj["assets"];
			$descriptions = $obj["descriptions"];
			foreach($descriptions as $key => $description){
 				foreach($assets as $key =>$asset){
 					if($description["classid"]==$asset["classid"] && $description["tradable"]==true && $description["marketable"]){
						if(array_key_exists($asset["assetid"],$realinventory)==false){
							$realinventory[$asset["assetid"]]																			=$description;
							$realinventory[$asset["assetid"]]["assetid"] 																		=$asset["assetid"];
							if (strpos($description["market_hash_name"], 'SG 553 | Army Sheen') !== false) $realinventory[$asset["assetid"]]['icon_url']								=$realinventory[$asset["assetid"]]['icon_url_large'];
							if (strpos($description["market_hash_name"], 'Factory New') !== false)		$realinventory[$asset["assetid"]]["wear"]								="FN";
							else if (strpos($description["market_hash_name"], 'Minimal Wear') !== false) 	$realinventory[$asset["assetid"]]["wear"]								="MW";
							else if (strpos($description["market_hash_name"], 'Field-Tested') !== false) 	$realinventory[$asset["assetid"]]["wear"]								="FT";
							else if (strpos($description["market_hash_name"], 'Well-Worn') !== false) 	$realinventory[$asset["assetid"]]["wear"]								="WW"; 
							else if (strpos($description["market_hash_name"], 'Battle-Scarred') !== false) 	$realinventory[$asset["assetid"]]["wear"]								="BS";
							if (strpos($description["market_hash_name"], 'StatTrak') !== false) $realinventory[$asset["assetid"]]["st"]										="true";
							if(array_key_exists(10,$realinventory[$asset["assetid"]]['descriptions'])&& strpos($realinventory[$asset["assetid"]]['descriptions'][10]['value'], '<br>') !== false) $realinventory[$asset["assetid"]]['stickers'] 					=$description['descriptions'][10]['value'];
							else if(array_key_exists(6,$realinventory[$asset["assetid"]]['descriptions'])&& strpos($realinventory[$asset["assetid"]]['descriptions'][6]['value'], '<br>')!== false) $realinventory[$asset["assetid"]]['stickers'] 				=$description['descriptions'][6]['value'];
							if(array_key_exists("actions",$description)){ 
								$tempReplace = str_replace("%owner_steamid%",$id,$description['actions'][0]['link']);
								$tempReplac = str_replace("%assetid%",$asset["assetid"],$tempReplace);
								$realinventory[$asset["assetid"]]['inspectlink'] = $tempReplac;
							}
								$stmt->execute([$description["market_hash_name"]]);
								$result = $stmt -> fetch();
								$realinventory[$asset["assetid"]]["price"]																	=$result["Price"];
								if($result["Overstock"] == 1 && $identity=="user") $realinventory[$asset["assetid"]]["overstock"]										="yes";
								else if ($result["Allowitem"] == 1 && $identity=="user") $realinventory[$asset["assetid"]]["banned"]										="yes";
								if($identity=="bot") array_push($updating, $description["market_hash_name"]);
						}
					}
				}
			}
			if($identity=="bot")  updateoverstock($updating , $db);
			echo json_encode($realinventory);
		}else if ($gameid==570){
			$stmt = $db->prepare("SELECT `Price` FROM `itemsinfo` WHERE `market_hash_name` = ?");
			$realinventory = array();
			$assets = $obj["assets"];
			$descriptions = $obj["descriptions"];
			foreach($descriptions as $key => $description){
	 			foreach($assets as $key =>$asset){
	 				if($description["classid"]==$asset["classid"] && $description["tradable"]==true && $description["marketable"]){
						if(array_key_exists($asset["assetid"],$realinventory)==false){
							$realinventory[$asset["assetid"]]													=$description;
							$realinventory[$asset["assetid"]]["assetid"] 												=$asset["assetid"];
							if (strpos($description["market_hash_name"], 'StatTrak') !== false) $realinventory[$asset["assetid"]]["st"] 				="true";
							if(array_key_exists(10,$description['descriptions'])) $realinventory[$asset["assetid"]]['stickers'] 					=$description['descriptions'][10]['value'];
							else if(array_key_exists(6,$description['descriptions'])) $realinventory[$asset["assetid"]]['stickers'] 				=$description['descriptions'][6]['value'];
							if(array_key_exists("actions",$description)){ 
								$tempReplace = str_replace("%owner_steamid%",$id,$description['actions'][0]['link']);
								$tempReplac = str_replace("%assetid%",$asset["assetid"],$tempReplace);
								$realinventory[$asset["assetid"]]['inspectlink'] = $tempReplac;
							}
								$stmt->execute([$description["market_hash_name"]]);
								$result = $stmt -> fetch();
								$realinventory[$asset["assetid"]]["price"]											=$result["Price"];
							}
						}
					}
			}
			echo json_encode($realinventory);
		}else echo json_encode(['error' => "Unknown game"]);
	}else echo json_encode(['error' => "Please log in"]);

function updateoverstock($array ,$db){
	$query = $db->prepare("UPDATE `itemsinfo` SET `Overstock` = :Overstock WHERE `market_hash_name` = :name");
	$updating = array_count_values($array);
	foreach ($updating as $key =>$value) {
		if (strpos($key, 'Knife') !== false && $value>=5){
			updatepositive($key,$query);
		}else if (strpos($key, 'Knife') !== false && $value<5){
			updatenegetive($key,$query);
		} else if((strpos($key, "Knife") !== false and strpos($key, "StatTrak") !== false) && $value>=3 ){
			updatepositive($key,$query);
		}else if((strpos($key, "Knife") !== false and strpos($key, "StatTrak") !== false) && $value<3 ){
			updatenegetive($key,$query);
		}else if (strpos($key, 'Key') !== false && $value>=50) {
			updatepositive($key,$query);
		}else if (strpos($key, 'Key') !== false && $value<50) {
			updatenegetive($key,$query);
		}else if (strpos($key, 'Case') !== false && strpos($key, 'Key') == false && $value>=5){
			updatepositive($key,$query);
		}else if (strpos($key, 'Case') !== false && strpos($key, 'Key') == false && $value<5){
			updatenegetive($key,$query);
		}else if ((strpos($key, "Package") !== false or strpos($key, "Sticker") !== false or strpos($key, "Pin") !== false) && $value>=5){
			updatepositive($key,$query);
		}else if ((strpos($key, "Package") !== false or strpos($key, "Sticker") !== false or strpos($key, "Pin") !== false) && $value<5){
			updatenegetive($key,$query);
		}else if (strpos($key, "Souvenir") !== false &&  $value>=3){
			updatepositive($key,$query);
		}else if (strpos($key, "Souvenir") !== false &&  $value<3){
			updatenegetive($key,$query);
		}else if ($value>=4){
			updatepositive($key,$query);
		}else if ($value<4){
			updatenegetive($key,$query);
		}
	}
}
function updatepositive($key,$query){
	$query->execute(array(
   		"Overstock" => 1,
  		"name" => $key
	));
}
function updatenegetive($key ,$query){
	$query->execute(array(
   		"Overstock" => 0,
  		"name" => $key
	));
}
?>