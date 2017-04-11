<?
session_start();

if (!defined('CRON_CALL') && isset($_SERVER['HTTP_REFERER'])) {

    $referrerUrl31d3d = $_SERVER['HTTP_REFERER'];
    if ((!isset($_SESSION['referrerUrl31d']) || 
    	($_SESSION['referrerUrl31d'] == '')) && ($referrerUrl31d3d != '')) {

        if (strpos($referrerUrl31d3d, 'mpgtune.com/') == 0) {
    		$_SESSION['referrerUrl31d'] = $referrerUrl31d3d;
     	}
    }
}

require_once('common.php');


define('USER_LEVEL_REGULAR', 1);
define('USER_LEVEL_POWER', 5);
define('USER_LEVEL_ADMIN', 10);

define('START_YEAR', 2016);

define('AVAIL_STR_LENGTH', 3 * 365 + 1);
define('DAYS_IN_A_BLOCK', 250);
define('NUMBER_OF_BLOCKS', 5);

define('DAYS_IN_WEEK', 7);
define('DAYS_IN_MONTH', 30);


if (defined('USE_FORUM_LOGIN_PROCESS')) {

    if (!defined('DEALER_MODE') && !defined('CRON_CALL')) {
        // pun_forum
        define('PUN_ROOT', './forum/');
        define('PUN_TURN_OFF_MAINT', 1);
        define('PUN_QUIET_VISIT', 1);

        require_once PUN_ROOT.'include/common.php';
    }
}


function makeDropDown($fieldName, $linkedTable, $linkedIdField, $linkedDisplayField, $feildValue) {
    global $db, $debug;
    $sql = "SELECT distinct $linkedIdField, $linkedDisplayField " . 
        "FROM $linkedTable " . 
        "ORDER BY $linkedDisplayField";
    $rs = mysql_query($sql, $db);
    if ($debug) {
        $error = mysql_error();
        if ($error != '') {
            echo "<br>Error running sql: '$sql' - Error '$error'<br>";
        }
    }
    echo("<SELECT name=$fieldName>\n<option value='0'>All</option>");
    while($row = mysql_fetch_array($rs)) {
        $url = "";
        extract($row);
        $idValue = $$linkedIdField;
        $textValue = $$linkedDisplayField;
        echo("<OPTION value=$idValue" . ($idValue == $feildValue ? " selected" : "") . ">$textValue</option>\n");
    }
    echo("</SELECT>\n");
}


function getBooleanValue($value) {
    return ($value == 'on' ? 1 : 0);
}

function getIntValue($value) {
    return (int)$value;
}

function getDoubleValue($value) {
    return (double)$value;
}

function getStringValue($value) {
    return 
        str_replace('"', "&quot;",
//        str_replace('<', "&lt;",
//        str_replace('>', "&gt;", 
        $value);//));
}

function getNewStyle($curStyle, $style1, $style2) {
    if ($curStyle == $style2) {
        return $style1;
    }
    return $style2;
}

function setLoggedByCookies($var) {
    global $_SESSION;
    $_SESSION['loggedByCookies54'] = $var;
    
}

function isLoggedByCookies() {
    global $_SESSION;
    return (boolean) $_SESSION['loggedByCookies54'];
    
}

/*function getUserId() {
    global $_SESSION;
    return (int)$_SESSION['userId'];
}*/


function getUserId() {

    global $_SESSION;

    if (!defined('USE_FORUM_LOGIN_PROCESS')) {
    	$userIdAdmin = (int)$_SESSION['ADMIN_LOGIN_userId'];
    	if ($userIdAdmin != 0) {
    		return $userIdAdmin;
    	}

	    $userId = (int)$_SESSION['12345userId'];
	    if ($userId == 0) {
            $userId = loginUsingCookies();
            if ($userId > 0) {
                setLoggedByCookies(true);
            }
        }
        return $userId;

    }
    
   /* if (isMobileUser() || defined('DEALER_MODE')) {

	    return (int)$_SESSION['12345userId'];

	}*/


	$userIdAdmin = (int)$_SESSION['ADMIN_LOGIN_userId'];
	if ($userIdAdmin != 0) {
		return $userIdAdmin;
	}

	global $pun_user;
    $userId = ($pun_user['id']);
    if ($userId > 1) {
    	if ((int)$_SESSION['12345userId'] == 0) {
    		setLoggedUserById($userId);
    	}
    }
    return $userId;

}

function setLoggedUserById($userId) {
    return setLoggedUserFromForum($userId, false);
}

function setLoggedUserFromForum($userId, $fromForum = true, $autoLogin = false) {

    $q = "select * from userinfo where user_id = " . ((int)$userId);
    if ($fromForum) {
        $rs = execSQLFromForum($q);
    } else {
        $rs = execSQL($q);
    }

    $num_of_rows = rowCount($rs);
/*
    $q2 = "INSERT INTO login_log (username, ip, success, attempt_date) " .
            "VALUES ('".str_replace("'", "''", $username)."', '$_SERVER[REMOTE_ADDR]', $num_of_rows, now())";
    $r2 = execSQL($q2);
*/
    if ($num_of_rows == 1)
    {
        //ok
        $row = getFirstRow($rs);
        setLoggedUser($row, $autoLogin);
        return $row[user_id];
    }
    return 0;
}

function loginUsingCookies() {
    global $_COOKIE;
    if (isset($_COOKIE['a']) && isset($_COOKIE['b'])) {
        $userId = (int) myDecrypt($_COOKIE['b']);
//die('cookie ' . $_COOKIE['b'] . '  ' . $userId);
        if ($userId > 0) {
            $value = getMd5Value($userId);
            if ($value == $_COOKIE['a']) {
                // all values match - login user
                setLoggedUserById($userId);
                setLoggedByCookies(true);
            }
        }
    }

    if ($userId < 2) {
    	// need to see if there is a forum cookie
    	$userId = checkForumCookies();
        if ($userId > 1) {
	        setLoggedUserById($userId);
            setLoggedByCookies(true);
	    }
    }

    return $userId;
}

//
// Cookie stuff!
//
function checkForumCookies()
{
        /*
			// remote
$cookie_name = 'punbb_cookie';
$cookie_domain = '';
$cookie_path = '/';
$cookie_secure = 0;
$cookie_seed = 'f4b129ed';


        	if (version_compare(PHP_VERSION, '5.2.0', '>='))
		setcookie($cookie_name, serialize(array($user_id, md5($cookie_seed.$password_hash))), $expire, $cookie_path, $cookie_domain, $cookie_secure, true);
	else
		setcookie($cookie_name, serialize(array($user_id, md5($cookie_seed.$password_hash))), $expire, $cookie_path.'; HttpOnly', $cookie_domain, $cookie_secure);

        */
$cookie_name = 'punbb_cookie';
$cookie_seed = 'f4b129ed';

	global $_COOKIE;

	$now = time();
	$expire = $now + 31536000;	// The cookie expires after a year

	// We assume it's a guest
	$cookie = array('user_id' => 1, 'password_hash' => 'Guest');

	
    // ignore forum cookies if i is set (during log-out)
	if (isset($_COOKIE['i'])) {
		if ($_COOKIE['i'] == 'fw632wq5') {
			return 0;
		}
	}
	// If a cookie is set, we get the user_id and password hash from it
	if (isset($_COOKIE[$cookie_name]))
		list($cookie['user_id'], $cookie['password_hash']) = @unserialize($_COOKIE[$cookie_name]);

    $userId = intval($cookie['user_id']);
	if ($userId > 1)
	{
		// Check if there's a user with the user ID and password hash from the cookie
		require_once('UserFunctions.php');
		$user = getUserDetailsNoFriends($userId);

		if ($user == null) return 0;

		$dbPassword = $user['password'];
        if (md5($cookie_seed.$dbPassword) !== $cookie['password_hash'])
		{
			logout();

			return 0;
		}
		return $userId;
	}
	return 0;
}

function isLoggedIn() {
    return getUserId() > 0;
}


function getMode() {
    global $_GET;
    global $_POST, $systemMode123;

    if (isset($systemMode123)) {
        $mode = $systemMode123;
    } else
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
    } else
    if (isset($_POST['mode'])) {
        $mode = $_POST['mode'];
    } else {
        $mode = '';
    }

    return $mode;
}


function rowCount($rs) {
    if ($rs == null) return 0;
    return mysql_num_rows($rs);
}

function getRowCount($rs) {
    return rowCount($rs);
}

function isAdmin() {
	return $_SESSION['12345user_level'] == 10;
}

function adminSetLoggedAsUser($userId) {
	if (!isAdmin()) { echo '<br>NOT ADMIN<br>'; return; }
    global $_SESSION;
	$_SESSION['ADMIN_LOGIN_userId'] = $userId;
	setLoggedUserById($userId);
}

function login($username, $password, $autoLogin = false) {
    global $_SERVER, $_SESSION;
	$password = md5($password);
    //$q1 = "select * from cars_agents where username = '$username' and password = '$password' ";
    //$r1 = mysql_query($q1) or die(mysql_error());

    //$rs = findUser($email, $username, $firstName, $lastName, $companyName, $address1, $address2, $city, $state, $zip, $phone, $mobile, $fax, $userLevel);
    //$rs = findUser('', $username, '', '', '', '', '', '', '', '', '', '', '', '');
    $q = "select * from userinfo where user_name = '".str_replace("'", "''", $username)."' 
        and password = '".str_replace("'", "''", $password)."' ";
    $rs = mysql_query($q) or mydie(mysql_error());

    $num_of_rows = mysql_num_rows($rs);

    $q1 = "INSERT INTO login_log (username, ip, success, attempt_date) " .
            "VALUES ('".str_replace("'", "''", $username)."', '$_SERVER[REMOTE_ADDR]', $num_of_rows, now())";
    $r2 = mysql_query($q1) or die(mysql_error());

    if ($num_of_rows == 1)
    {
        //ok
        $row = mysql_fetch_array($rs);

        $userId = $row[user_id];
        setLoggedUser($row, $autoLogin);

        $sql =
            "UPDATE userinfo SET " .
                " last_login_on = now()" .
                ", last_login_from_ip" .
                    "'".str_replace("'", "''", $_SERVER[REMOTE_ADDR])."'".
                " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);

        return true;
    }
    return false;
}

function setLoggedUser($row, $autoLogin = false) {
    session_start();
    global $_SESSION, $_COOKIE;
	$userId = $row[user_id];
    $_SESSION[userId] = $userId;
    //$_SESSION[isDealer] = ($a1[BusinessName] != '');
    $_SESSION[username] = $row[username];
    $_SESSION[firstName] = $row[first_name];
$value = getMd5Value($userId);
//die("user $userId value $value");
/*
$cookieKey = 'TestCookie';
echo "current value is " . $_COOKIE[$cookieKey] . '<br>';

$value = 'something from somewhere12355';
*/
//$res = setcookie($cookieKey, $value, time() + 3600, '/');  /* expire in 1 hour */
    if ($autoLogin) {
        $key12 = 'a';
        $res = setcookie($key12, $value, time() + 3600 * 24 * 100, '/');  /* expire in 1 hour * 24 * 100 */
        //if ($res <> 1) 
    //    die("die - res = $res");

       $encryptedId = myEncrypt($userId);
        $res = setcookie('b', $encryptedId, time() + 3600 * 24 * 100, '/');  /* expire in 1 hour * 24 * 100 */
    //    if ($res <> 1) die("die - res = $res");
       $res = setcookie('b', $encryptedId, time() + 3600 * 24 * 100, '/');  /* expire in 1 hour * 24 * 100 */
    }
	$cookie_name = 'punbb_cookie';
//	setcookie($cookie_name, serialize(array(1, '')), time(), '/');
}

function getMd5Value($str) {
   return substr(md5($str . '3qG&HxE'), 0, 15);
}

$keyForEnc48sd = '947663ea6cbb79a7cc0afe95064a1e82lkjhasd987oi34jkluad';
$ivForEnc48sd = '9wasd68adfh78975';

function my_encrypt($string) {
	global $keyForEnc48sd, $ivForEnc48sd;
/*
$key = 'MdfeS5s #gg';
$key = md5($key);
echo "key is '$key'";
*/
/* Open module, trim key to max length */
$td = mcrypt_module_open('twofish','','ecb', '');
$key = substr($keyForEnc48sd, 0, mcrypt_enc_get_key_size($td));

/* Initialize encryption handle
* (use blank IV)
*/
$iv = $ivForEnc48sd;
if (mcrypt_generic_init($td, $key, $iv) != -1) {

/* Encrypt data */
$c_t = mcrypt_generic($td, $string);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
return $c_t;
} //end if
}

function my_decrypt($string) {
	global $keyForEnc48sd, $ivForEnc48sd;
/*
$key = 'MdfeS5s #gg';
$key = md5($key);
*/
$key = '947663ea6cbb79a7cc0afe95064a1e82';

/* Open module, trim key to max length */
$td = mcrypt_module_open('twofish', '','ecb', '');
$key = substr($keyForEnc48sd, 0, mcrypt_enc_get_key_size($td));

/* Initialize encryption handle
* (use blank IV)
*/
$iv = $ivForEnc48sd;
if (mcrypt_generic_init($td, $key, $iv) != -1) {

/* Encrypt data */
$c_t = mdecrypt_generic($td, $string);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
return trim($c_t); //trim to remove
//padding
} //end if
}

function myEncrypt($string) {
    if (function_exists('mcrypt_module_open')) {
        return base64_encode(my_encrypt($string));
    } else {
        return base64_encode($string);
    }
}

function myDecrypt($string) {
    if (function_exists('mcrypt_module_open')) {
        return my_decrypt(base64_decode($string));
    } else {
        return base64_decode($string);
    }
}

function logout() {
    global $_SESSION;
    session_start();
    if (isset($_SESSION['ADMIN_LOGIN_userId'])) {
    	unset($_SESSION['ADMIN_LOGIN_userId']);
    } else {
	    session_unset();
//    session_unregister();
    	session_destroy();
    	unset($_SESSION['12345userId']);
    }

    $res = setcookie('a', '12', time() - 3600 * 24 * 100, '/');  // expire in 1 hour * 24 * 100
    $res = setcookie('b', '12', time() - 3600 * 24 * 100, '/');  // expire in 1 hour * 24 * 100
    $res = setcookie('a', '', time() - 3600 * 24 * 100, '/forum/');  // expire in 1 hour * 24 * 100
    $res = setcookie('b', '', time() - 3600 * 24 * 100, '/forum/');  // expire in 1 hour * 24 * 100

	$cookie_name = 'punbb_cookie';
	setcookie($cookie_name, serialize(array(1, '11')), time() + 31536000, '/', '', 0);

	setcookie('i', 'fw632wq5', time() + 31536000, '/', '', 0);
}

function logout23() {
    global $_SESSION, $_COOKIE;
    session_start();
    if (isset($_SESSION['ADMIN_LOGIN_userId'])) {
    	unset($_SESSION['ADMIN_LOGIN_userId']);
    } else {
	    session_unset();
//    session_unregister();
    	session_destroy();
    	unset($_SESSION['12345userId']);
    }

    $res = setcookie('a', '12', time() - 3600 * 24 * 100, '/');  // expire in 1 hour * 24 * 100
    $res = setcookie('b', '12', time() - 3600 * 24 * 100, '/');  // expire in 1 hour * 24 * 100
    $res = setcookie('a', '', time() - 3600 * 24 * 100, '/forum/');  // expire in 1 hour * 24 * 100
    $res = setcookie('b', '', time() - 3600 * 24 * 100, '/forum/');  // expire in 1 hour * 24 * 100

	$cookie_name = 'punbb_cookie';
	setcookie('punbb_cookie', serialize(array(1, '11')), time() - 31536000, '/', '', 0);
    setcookie('punbb_cookie', '', time() - 3600 * 24 * 100, '/forum/');  // expire in 1 hour * 24 * 100

	store_cookie_header($cookie_name, array(1, '11'));
die('cookie unset');

}


function mydie($message){
    die($message);
}

function setMessage($message) {
    global $_SESSION;
    $_SESSION['message'] = $message;

}

function showMessage() {
    global $_SESSION;
    if ($_SESSION['message'] != '') {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
?>   <tr>
    <td class="error" colspan="2" valign="top" align="center">
     <span class="err_msg"><?= $message ?></span>
    </td>
   </tr>
<?
    }
}

function getDateValue($value) {
	if ($value == '') $value = '12/31/1969';
    return date("m/d/Y H:i", strtotime($value));
//    return (date)$value;
}

function getDateValue4DB($value) {
	if ($value == '') $value = '12/31/1969';
    return "'" . date('Y-m-d H:i:s', strtotime($value)) . "'";
//    return (date)$value;
}


function datediff($startDate, $stopDate) {
	return round((strtotime($stopDate) - strtotime($startDate)) / 86400); // 60 * 60 * 24
}

function adddays($startDate, $days) {
	return date("m/d/Y", (strtotime($startDate) + $days * 86400)); // 60 * 60 * 24
}

function daysFromStart($date) {
	return datediff('1/1/' . START_YEAR, $date);
}

function displayDate($value) {
    $result = getDateValue($value);
    if ($result == '12/31/1969') { return ''; } else { return $result; }
}

function sendRedirect($url) {
    Header("Location: " . $url);
    exit();
}

