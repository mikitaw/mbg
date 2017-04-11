<?
require_once('functions.php');
if (empty($_POST)) sendRedirect("index.php");
require_once('common.php');
	//require_once('game.php');
//require_once('playerClass.php');
//require_once('gameRoundClass.php');

$error = 0;
//$username = '';
//$password = '';
$message = 'This text should not be displayed';
$lackOfData = "Server has not received all necessary data";
$data = array($message);
$mode = $_POST['mode'];
//$user = (string)getUserId();

//sleep (2);
	
switch ($mode) {
case 'gameList':
	require_once('gameClass.php');
	
	$game = new game();
	$rs = $game->getList();

	$error = mysql_error();
	if ($error != '') {
		$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
	} elseif (mysql_num_rows($rs) == 0) {
		$message = 'There are no games';
	} else {
		$message = 'List of games is successfully loaded';
		//$data[] = $nextRoundNumber;
		while($row = mysql_fetch_array($rs)) {
			//$url = "";
			$gameID = (int)$row['gameID'];
			$gameName = $row['gameName'];
			$roundNumber = (int)$row['roundNumber'];
			$created_on = $row['created_on'];
			$updated_on = $row['updated_on'];
			$array = array("gameID"=>$gameID,
				"gameName"=>$gameName,
				"roundNumber"=>$roundNumber,
				"created_on"=>$created_on, 
				"updated_on"=>$updated_on);
			//$array0 = json_encode($array);
			$data[] = $array;
		}
	}
	break;				
case 'playerList':
	require_once('gamePlayerClass.php');
	
	$data[1] = 0; //result
	$gamePlayer = new gamePlayer();
	/*$gamePlayer->setValues($_POST);*/
	$rs = $gamePlayer->getList();
		
	$error = mysql_error();
	if ($error != '') {
		$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
	} elseif (mysql_num_rows($rs) == 0) {
		$message = 'There are no players';
	} else {
		$data[1] = 1; //result
		$message = 'List of players is successfully loaded';
		while($row = mysql_fetch_array($rs)) {
			$data[] = array("gameID"=>(int)$row['gameID'],
				"playerID"=>(int)$row['playerID'],
				"playerName"=>$row['playerName'],
				"created_on"=>$row['created_on'], 
				"updated_on"=>$row['updated_on'],
				"playerOrder"=>$row['playerOrder']);
			//$array0 = json_encode($array);
		}
	}
	break;
case 'loginForm':
	require_once('UserFunctions.php');
    
	if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
		$message = $lackOfData;
	} else {
		$username = getStringValue($_POST['login']);
		$username = strtolower($username);
		$password = getStringValue($_POST['password']);
		//$autoLogin = false;
		$autoLogin = (($_POST['autoLogin'] == 'true') ? true : false);
		//$autoLogin = true;
		$result = 0;

		if (login($username, $password, $autoLogin)) {
        //$message = "Welcome";
        /*
        setcookie("my_page_id", $id,
            time() + 100 * 356 * 24 * 60 * 60); // 100 years
        */
			$message = 'Successful login';
			$result = 1;
		//$_POST['message'] = 'cool, man, cool';

		} else {
			$message = 'Invalid username/password';
		//$_POST['message'] = 'Invalid username/password';
		}
	}
	$data[1] = $result;
	/*$data[] = array("login"=>$username,
				"password"=>$password, 
				"result"=>$result);*/
	break;
case 'recordScore':
	require_once('scoreClass.php');
	if ((!isset($_POST['gameID'])) || (!isset($_POST['playerID'])) || (!isset($_POST['score']))) {
		$message = $lackOfData;
	} else {
		$score = new score();
		$score->setValues($_POST);
		$score->create();
		//$fst = (string) $rs;
		$result = 0;
		$error = mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			$message = 'Score is successfully saved';
			$result = 1;
		}
	}
	$data[1] = $result;
	break;
case 'deleteGame':
	require_once('gameClass.php');
	if (!isset($_POST['gameID'])) {
		$message = $lackOfData;
	} else {
		$game = new game();
		$game->setValues($_POST);
		$game->delete();
		//$fst = (string) $rs;
		$result = 0;
		$error = mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			if (isset($_POST['gameName'])) {
				$message = 'Game "'.$_POST['gameName'].'" is successfully deleted';
			} else {
				$message = 'Game is successfully deleted';
			}
			$result = 1;
		}
	}
	$data[1] = $result;
	break;
case 'deletePlayer':
	require_once('playerClass.php');
	if (!isset($_POST['playerID'])) {
		$message = $lackOfData;
	} else {
		$player = new player();
		$player->setValues($_POST);
		$player->delete();
		//$fst = (string) $rs;
		$result = 0;
		$error = mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			if (isset($_POST['playerName'])) {
				$message = 'Player "'.$_POST['playerName'].'" is successfully deleted';
			} else {
				$message = 'Player is successfully deleted';
			}
			$result = 1;
		}
	}
	$data[1] = $result;
	break;
case 'updateGame':
	require_once('gameClass.php');
	if (!isset($_POST['gameID'])) {
		$message = $lackOfData;
	} else {
		$game = new game();
		$game->setValues($_POST);
		$game->update();
		//$fst = (string) $rs;
		$result = 0;
		$error = mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			if ((isset($_POST['gameName'])) && (!isset($_POST['roundNumber']))) {
				$message = 'The name of the game is successfully saved';
			} elseif ((!isset($_POST['gameName'])) && (isset($_POST['roundNumber']))) {
				$message = 'The next round is started';
			} else {
				$message = 'The game updated successfully';
			}
			$result = 1;
		}
	}
	$data[1] = $result;
	break;
case 'saveNewGame':
	require_once('gameClass.php');
	if ((!isset($_POST['gameName'])) || (!isset($_POST['created_on']))) {
		$message = $lackOfData;
	} else {
		$rs = array("gameName"=>$_POST['gameName'],
		"roundNumber"=>0,
		"created_on"=>$_POST['created_on'],
		"updated_on"=>$_POST['created_on']);
		$game = new game();
		$game->setValues($rs);
		$id = $game->create();
		$result = 0;
		$error = mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			$message = 'Game "'.$_POST['gameName'].'" is successfully created';
			$result = 1;
			$data[2] = $rs;
			$data[2]['gameID'] = (int)$id;
		}
	}
	$data[1] = $result;
	break;
case 'saveNewPlayer':
	require_once('playerClass.php');
	if ((!isset($_POST['playerName'])) || (!isset($_POST['created_on']))) {
		$message = $lackOfData;
	} else {
		$rs = array("playerName"=>$_POST['playerName'],
		"created_on"=>$_POST['created_on'],
		"updated_on"=>$_POST['created_on']);
		$player = new player();
		$player->setValues($rs);
		$id = $player->create();
		$error = mysql_error();
		if (isset($_POST['gameID'])) {
			require_once('gamePlayerClass.php');
			$gamePlayer = new gamePlayer();
			$rs2 = array("playerID"=>$id,
				"gameID"=>$_POST['gameID'],
				"playerOrder"=>0);
			$gamePlayer->setValues($rs2);
			$gamePlayer->create();
		}
		$result = 0;
		$error .= mysql_error();
		if ($error != '') {
			$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
		} else {
			$message = 'Player "'.$_POST['playerName'].'" is successfully created';
			$result = 1;
			$data[2] = $rs;
			$data[2]['playerID'] = (int)$id;
		}
	}
	$data[1] = $result;
	break;
case 'logout':
	logout();
	$message = 'Successful logout';
	break;
case 'scoreList':
	require_once('scoreClass.php');
	
	$scores = new score();
	$rs = $scores->getList();

	$error = mysql_error();
	if ($error != '') {
		$message = '<span style="color: red">Error SQL: ' . $error . '</span>';
	} elseif (mysql_num_rows($rs) == 0) {
		$message = 'There are no scores';
	} else {
		$message = 'List of scores is successfully loaded';
		//$data[] = $nextRoundNumber;
		while($row = mysql_fetch_array($rs)) {
			//$url = "";
			$playerName = $row['playerName'];
			$score = (int)$row['score'];
			$array = array("playerName"=>$playerName,
				"score"=>$score);
			//$array0 = json_encode($array);
			$data[] = $array;
		}
	}
	break;	
default:
	$message = 'Server received an unknown request type :(';
}
$data[0] = $message;
header('Content-Type: application/json');
echo json_encode($data);
?>