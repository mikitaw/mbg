<?

 /* 
 * UserManager.php
 * Manager for table userinfo
 * Revised
 *
 */


    function getUserList() {
        $sql =
            "SELECT " .
                "user_id " .
                ", email " .
                ", user_name " .
                ", password " .
                ", first_name " .
                ", last_name " .
                ", company_name " .
                ", address1 " .
                ", address2 " .
                ", city " .
                ", state " .
                ", zip " .
                ", known_zip " .
                ", phone " .
                ", mobile " .
                ", fax " .
                ", public " .
                ", receive_news " .
                ", receive_promotions " .
                ", user_level " .
                ", created_on " .
                ", updated_on " .
                ", last_login_on " .
                ", created_from_ip " .
                ", updated_from_ip " .
                ", last_login_from_ip " .
                
                "FROM userinfo ORDER BY ".
                "user_id";
            
        return execSQL($sql);
    }

    function getUserDetails($id) {

        $sql = 
                "SELECT " .
                "u.user_id " .
                ",email " .
                ",user_name " .
                ",password " .
                ",first_name " .
                ",last_name " .
                ",company_name " .
                ",address1 " .
                ",address2 " .
                ",city " .
                ",state " .
                ",zip " .
                ",known_zip " .
                ",phone " .
                ",mobile " .
                ",fax " .
                ",public " .
                ",receive_news " .
                ",receive_promotions " .
                ",user_level " .
                ",u.created_on " .
                ",u.updated_on " .
                ",last_login_on " .
                ",created_from_ip " .
                ",updated_from_ip " .
                ",last_login_from_ip " .
                ",image " .
                ",metrics " .
                ", IFNULL(fr.id, 0) as is_friend " .
                
                "FROM userinfo u " .
                    "LEFT OUTER JOIN friend fr ON u.user_id = fr.friend_user_id AND fr.user_id = " . getUserId();

        if (strstr(strtolower($sql), 'where') == 0) {
            $sql .= " WHERE ";
        } else {
            $sql .= " AND ";
        }
            
        $sql .= "u.user_id = $id";
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        return $row;
    }


    function getUserDetailsNoFriends($id) {

        $sql = 
                "SELECT " .
                "u.user_id " .
                ",email " .
                ",user_name " .
                ",password " .
                ",first_name " .
                ",last_name " .
                ",company_name " .
                ",address1 " .
                ",address2 " .
                ",city " .
                ",state " .
                ",zip " .
                ",known_zip " .
                ",phone " .
                ",mobile " .
                ",fax " .
                ",public " .
                ",receive_news " .
                ",receive_promotions " .
                ",user_level " .
                ",u.created_on " .
                ",u.updated_on " .
                ",last_login_on " .
                ",created_from_ip " .
                ",updated_from_ip " .
                ",last_login_from_ip " .
                ",image " .
                ",metrics " .
                
                "FROM userinfo u ";

        $sql .= "WHERE u.user_id = $id";
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        return $row;
    }


    function createUser(
            $email, $username, $password, $firstName, $lastName, $companyName, $address1, $address2, $city, $state, $zip, $knownZip, $phone, $mobile, $fax, $public, $receiveNews, $receivePromotions, $userLevel, $createdOn, $updatedOn, $lastLoginOn, $createdFromIp, $updatedFromIp, $lastLoginFromIp) {

        $password = md5($password);
        $sql =
            "INSERT INTO userinfo ( " .
                "email" .
                ", user_name" .
                ", password" .
                ", first_name" .
                ", last_name" .
                ", company_name" .
                ", address1" .
                ", address2" .
                ", city" .
                ", state" .
                ", zip" .
                ", known_zip" .
                ", phone" .
                ", mobile" .
                ", fax" .
                ", public" .
                ", receive_news" .
                ", receive_promotions" .
                ", user_level" .
                ", created_on" .
                ", updated_on" .
                ", last_login_on" .
                ", created_from_ip" .
                ", updated_from_ip" .
                ", last_login_from_ip" .
                ", image" .
                ", dealer_id" .
            ") VALUES (" .
                ""."'".str_replace("'", "''", $email)."'".
                    ", "."'".str_replace("'", "''", $username)."'".
                    ", "."'".str_replace("'", "''", $password)."'".
                    ", "."'".str_replace("'", "''", $firstName)."'".
                    ", "."'".str_replace("'", "''", $lastName)."'".
                    ", "."'".str_replace("'", "''", $companyName)."'".
                    ", "."'".str_replace("'", "''", $address1)."'".
                    ", "."'".str_replace("'", "''", $address2)."'".
                    ", "."'".str_replace("'", "''", $city)."'".
                    ", "."'".str_replace("'", "''", $state)."'".
                    ", "."'".str_replace("'", "''", $zip)."'".
                    ", ".$knownZip.
                    ", "."'".str_replace("'", "''", $phone)."'".
                    ", "."'".str_replace("'", "''", $mobile)."'".
                    ", "."'".str_replace("'", "''", $fax)."'".
                    ", ".$public.
                    ", ".$receiveNews.
                    ", ".$receivePromotions.
                    ", ".$userLevel.
                    ", "."now()".
                    ", "."now()".
                    ", "."now()".
                    ", "."'".str_replace("'", "''", $createdFromIp)."'".
                    ", "."'".str_replace("'", "''", $updatedFromIp)."'".
                    ", "."'".str_replace("'", "''", $lastLoginFromIp)."'".
					", 'noUserImg.gif'" .
					", " . getDealerId() .
				
                ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        
        // create forum user
        //INSERT INTO forum_users (username, group_id, password, email, email_setting, save_pass, timezone, language, style, registered, registration_ip, last_visit) 
    	//VALUES('username123', 4, '32250170a0dca92d53ec9624f336ca24', 'username123@username.com', 1, 1, -6 , 'English', 'Oxygen', 1274113934, '127.0.0.1', 1274113934)

    	$now = time();
		$sql = 'INSERT INTO forum_users (id, username, group_id, password, email, email_setting, save_pass, timezone, language, style, registered, registration_ip, last_visit) VALUES' . 
		'('.$id.', \''.str_replace("'", "''", $username).'\', '. 4 .', \''.str_replace("'", "''", $password).'\', \''. str_replace("'", "''", $email) . '\', ' . $receiveNews.', 0, -5, \'English\', \'Oxygen\', 0, \''.$createdFromIp.'\', '.$now.')';
		execSQLInForumDB($sql);

        
        return $id;
    }

    function createUserFromForum($id,
            $email, $username, $password, $firstName, $lastName, $companyName, $address1, $address2, $city, $state, $zip, $knownZip, $phone, $mobile, $fax, $public, $receiveNews, $receivePromotions, $userLevel, $createdOn, $updatedOn, $lastLoginOn, $createdFromIp, $updatedFromIp, $lastLoginFromIp, 
            $autoLogin, $metrics) {
        global $_SESSION;
        $sql =
            "INSERT INTO userinfo ( " .
                "user_id" .
                ",email" .
                ", user_name" .
                ", password" .
                ", first_name" .
                ", last_name" .
                ", company_name" .
                ", address1" .
                ", address2" .
                ", city" .
                ", state" .
                ", zip" .
                ", known_zip" .
                ", phone" .
                ", mobile" .
                ", fax" .
                ", public" .
                ", receive_news" .
                ", receive_promotions" .
                ", user_level" .
                ", created_on" .
                ", updated_on" .
                ", last_login_on" .
                ", created_from_ip" .
                ", updated_from_ip" .
                ", last_login_from_ip" .
                ", referrer_url" .
                ", image" .
                ", metrics " .
            ") VALUES (" .
                "$id,"."'".str_replace("'", "''", $email)."'".
                    ", "."'".str_replace("'", "''", $username)."'".
                    ", "."'".str_replace("'", "''", $password)."'".
                    ", "."'".str_replace("'", "''", $firstName)."'".
                    ", "."'".str_replace("'", "''", $lastName)."'".
                    ", "."'".str_replace("'", "''", $companyName)."'".
                    ", "."'".str_replace("'", "''", $address1)."'".
                    ", "."'".str_replace("'", "''", $address2)."'".
                    ", "."'".str_replace("'", "''", $city)."'".
                    ", "."'".str_replace("'", "''", $state)."'".
                    ", "."'".str_replace("'", "''", $zip)."'".
                    ", ".$knownZip.
                    ", "."'".str_replace("'", "''", $phone)."'".
                    ", "."'".str_replace("'", "''", $mobile)."'".
                    ", "."'".str_replace("'", "''", $fax)."'".
                    ", ".$public.
                    ", ".$receiveNews.
                    ", ".$receivePromotions.
                    ", ".$userLevel.
                    ", "."now()".
                    ", "."now()".
                    ", "."now()".
                    ", "."'".str_replace("'", "''", $createdFromIp)."'".
                    ", "."'".str_replace("'", "''", $updatedFromIp)."'".
                    ", "."'".str_replace("'", "''", $lastLoginFromIp)."'".
                    ", "."'".str_replace("'", "''", $_SESSION['referrerUrl31d'])."'".
					", 'noUserImg.gif'" .
                    ", ".$metrics.
                ")";

        execSQL($sql);
//        execSQLFromForum($sql);

        $sql = 'SELECT * FROM userinfo WHERE user_id = ' . $id;

        $rs = execSQL($sql);
//        $rs = execSQLFromForum($sql);
        $row = getFirstRow($rs);

        setLoggedUser($row, $autoLogin);

        checkForInvitation($id);
/*
        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];
        return $id;
*/
    }

    function checkForInvitation($newUserId) {
        global $_SESSION;
        if (!isset($_SESSION['invitationId'])) return;
        $invitationId = $_SESSION['invitationId'];
		$conf = $_SESSION['invitationConf'];
		if (($invitationId > 0) && ($conf == getMd5Value($invitationId))) {
			$sql = 'SELECT i.from_user_id, i.from_email FROM invitation i ' .
			"WHERE i.invitation_id = $invitationId ";
			$rs = execSQLFromForum($sql);
			$row = getFirstRow($rs);
			if ($row != null) {
				$fromUserId = $row['from_user_id'];
				$fromEmail = $row['from_email'];

				// need to insert friendship records for both users
                $sql =
                    "INSERT INTO friend ( " .
                        "user_id" .
                        ", friend_user_id" .
                        ", created_on" .
                        
                    ") VALUES (" .
                    "".$fromUserId.
                        ", ".$newUserId.
                        ", now() " .
                    ")";
				execSQLFromForum($sql);

                $sql =
                    "INSERT INTO friend ( " .
                        "user_id" .
                        ", friend_user_id" .
                        ", created_on" .
                        
                    ") VALUES (" .
                    "".$newUserId.
                        ", ".$fromUserId.
                        ", now() " .
                    ")";
				execSQLFromForum($sql);

			}
		}
    
    }
    
    function updateUser(
        $userId, $email, $username, $password, $firstName, $lastName, $companyName, $address1, $address2, $city, $state, $zip, $knownZip, $phone, $mobile, $fax, $public, $receiveNews, $receivePromotions, $userLevel, $createdOn, $updatedOn, $lastLoginOn, $createdFromIp, $updatedFromIp, 
        $lastLoginFromIp, $metrics) {
        $sql =
            "UPDATE userinfo SET " .
                "email = " .
                    "'".str_replace("'", "''", $email)."'".
/*
                ", user_name = " .
                    "'".str_replace("'", "''", $username)."'".
                ", password = " .
                    "'".str_replace("'", "''", $password)."'".
*/
                ", first_name = " .
                    "'".str_replace("'", "''", $firstName)."'".
                ", last_name = " .
                    "'".str_replace("'", "''", $lastName)."'".
                ", company_name = " .
                    "'".str_replace("'", "''", $companyName)."'".
                ", address1 = " .
                    "'".str_replace("'", "''", $address1)."'".
                ", address2 = " .
                    "'".str_replace("'", "''", $address2)."'".
                ", city = " .
                    "'".str_replace("'", "''", $city)."'".
                ", state = " .
                    "'".str_replace("'", "''", $state)."'".
                ", zip = " .
                    "'".str_replace("'", "''", $zip)."'".
                ", known_zip = " .
                    $knownZip.
                ", phone = " .
                    "'".str_replace("'", "''", $phone)."'".
                ", mobile = " .
                    "'".str_replace("'", "''", $mobile)."'".
                ", fax = " .
                    "'".str_replace("'", "''", $fax)."'".
                ", public = " .
                    $public.
                ", receive_news = " .
                    $receiveNews.
                ", receive_promotions = " .
                    $receivePromotions.
                ", user_level = " .
                    $userLevel.
                ", updated_on = " .
                    "now()".
                ", updated_from_ip = " .
                    "'".str_replace("'", "''", $updatedFromIp)."'".
                ", metrics = " . $metrics .
                " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);
    }

    function updateUserPassword(
        $userId, $password, $updatedFromIp) {

        $password = md5($password);

        $sql =
            "UPDATE userinfo SET " .
                " password = " .
                    "'".str_replace("'", "''", $password)."'".
                ", updated_on = " .
                    "now()".
                ", updated_from_ip = " .
                    "'".str_replace("'", "''", $updatedFromIp)."'".
                " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);
    }

    function deleteUserImage($image) {
        unlink('carImages/t_' . $image);
        unlink('carImages/' . $image);
    }
    
	function getUserImage($image) {
		return '<img src="carImages/t_' . $image. '"/>';
	}

    function updateUserImage(
        $userId, $image) {

        $row = getUserDetails($userId);
        $oldImage = $row['image'];
        if ($oldImage != 'noUserImg.gif') {
        	deleteUserImage($oldImage);
        }

        $sql =
            "UPDATE userinfo SET " .
                " image = " .
                    "'".str_replace("'", "''", $image)."'".
                ", updated_on = " .
                    "now()".
                ", updated_from_ip = " .
                    "'".str_replace("'", "''", getUserIp())."'".
                " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);
    }

    
    function deleteUser($userId) {
            
        $sql =
            "DELETE FROM userinfo " .
            " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);
    }

    function findUser($email, $username, $firstName, $lastName, $companyName, $address1, $address2, $city, $state, $zip, $phone, $mobile, $fax, $userLevel) {

        $sql = 
                "SELECT " .
                "user_id " .
                ",email " .
                ",user_name " .
                ",password " .
                ",first_name " .
                ",last_name " .
                ",company_name " .
                ",address1 " .
                ",address2 " .
                ",city " .
                ",state " .
                ",zip " .
                ",known_zip " .
                ",phone " .
                ",mobile " .
                ",fax " .
                ",public " .
                ",receive_news " .
                ",receive_promotions " .
                ",user_level " .
                ",created_on " .
                ",updated_on " .
                ",last_login_on " .
                ",created_from_ip " .
                ",updated_from_ip " .
                ",last_login_from_ip " .
                

                "FROM userinfo ";

        $andStatement = "";
        $and = "";
        
        if ("" != $email) {
            $andStatement .= $and . "email like " . "'".str_replace("'", "''", $email)."'";
            $and = " AND ";
        }
        
        if ("" != $username) {
            $andStatement .= $and . "user_name like " . "'".str_replace("'", "''", $username)."'";
            $and = " AND ";
        }
        
        if ("" != $firstName) {
            $andStatement .= $and . "first_name like " . "'".str_replace("'", "''", $firstName)."%'";
            $and = " AND ";
        }
        
        if ("" != $lastName) {
            $andStatement .= $and . "last_name like " . "'".str_replace("'", "''", $lastName)."%'";
            $and = " AND ";
        }
        
        if ("" != $companyName) {
            $andStatement .= $and . "company_name like " . "'".str_replace("'", "''", $companyName)."%'";
            $and = " AND ";
        }
        
        if ("" != $address1) {
            $andStatement .= $and . "address1 like " . "'".str_replace("'", "''", $address1)."%'";
            $and = " AND ";
        }
        
        if ("" != $address2) {
            $andStatement .= $and . "address2 like " . "'".str_replace("'", "''", $address2)."%'";
            $and = " AND ";
        }
        
        if ("" != $city) {
            $andStatement .= $and . "city like " . "'".str_replace("'", "''", $city)."%'";
            $and = " AND ";
        }
        
        if ("" != $state) {
            $andStatement .= $and . "state like " . "'".str_replace("'", "''", $state)."%'";
            $and = " AND ";
        }
        
        if ("" != $zip) {
            $andStatement .= $and . "zip like " . "'".str_replace("'", "''", $zip)."%'";
            $and = " AND ";
        }
        
        if ("" != $phone) {
            $andStatement .= $and . "phone like " . "'".str_replace("'", "''", $phone)."%'";
            $and = " AND ";
        }
        
        if ("" != $mobile) {
            $andStatement .= $and . "mobile like " . "'".str_replace("'", "''", $mobile)."%'";
            $and = " AND ";
        }
        
        if ("" != $fax) {
            $andStatement .= $and . "fax like " . "'".str_replace("'", "''", $fax)."%'";
            $and = " AND ";
        }
        
        if (0 != $userLevel) {
            $andStatement .= $and . "user_level like " . $userLevel;
            $and = " AND ";
        }
        

        if ($andStatement != "") {
            if (strstr(strtolower($sql), 'where') == 0) {
                $sql .= " WHERE ";
            } else {
                $sql .= " AND ";
            }
            $sql .= $andStatement;
        }

            
        $sql .= " ORDER BY ".
                "user_id";
//die($sql);            
        return execSQL($sql);
    }


    function getUserListForNews($test, $offset) {
        $sql =
            "SELECT " .
                "user_id " .
                ", email " .
                ", user_name " .
                ", password " .
                ", first_name " .
                ", last_name " .
                ", company_name " .
                ", address1 " .
                ", address2 " .
                ", city " .
                ", state " .
                ", zip " .
                ", known_zip " .
                ", phone " .
                ", mobile " .
                ", fax " .
                ", public " .
                ", receive_news " .
                ", receive_promotions " .
                ", user_level " .
                ", created_on " .
                ", updated_on " .
                ", last_login_on " .
                ", created_from_ip " .
                ", updated_from_ip " .
                ", last_login_from_ip " .
                
                "FROM userinfo WHERE receive_news = 1 ". 
                ($test ? " AND user_id IN (63) " : " AND email NOT LIKE '%mpgtune.com' ") .
                "ORDER BY ".
                "user_id LIMIT 450 OFFSET 845";// . ($offset * 200 + 20);
//                AND email NOT LIKE '%mpgtune.com' AND user_id IN (63, 805) ORDER BY ".
//die($sql);            
        return execSQL($sql);
    }

    function getUserListForQuest($test, $offset) {
        $sql =
            "SELECT " .
                "user_id " .
                ", email " .
                ", user_name " .
                ", password " .
                ", first_name " .
                ", last_name " .
                ", company_name " .
                ", address1 " .
                ", address2 " .
                ", city " .
                ", state " .
                ", zip " .
                ", known_zip " .
                ", phone " .
                ", mobile " .
                ", fax " .
                ", public " .
                ", receive_news " .
                ", receive_promotions " .
                ", user_level " .
                ", created_on " .
                ", updated_on " .
                ", last_login_on " .
                ", created_from_ip " .
                ", updated_from_ip " .
                ", last_login_from_ip " .
                
                "FROM userinfo WHERE receive_news = 1 ". 
                ($test ? " AND user_id IN (63) " : " AND email NOT LIKE '%mpgtune.com' ") .
//                ($test ? " AND user_id IN (3527) " : " AND email NOT LIKE '%mpgtune.com' ") .
//                " AND last_login_on >= DATE_SUB(now(), INTERVAL 60 DAY) " . // active
                " AND last_login_on <= DATE_SUB('2010-10-12', INTERVAL 60 DAY) " .
                "ORDER BY ".
                "user_id LIMIT 300 OFFSET " . ($offset * 300);
//                AND email NOT LIKE '%mpgtune.com' AND user_id IN (63, 805) ORDER BY ".
//die($sql);            
        return execSQL($sql);
    }


    function updateUserDisableNews(
        $userId, $updatedFromIp) {
        $sql =
            "UPDATE userinfo SET " .
                " receive_news = 0" .
                ", updated_on = " .
                    "now()".
                ", updated_from_ip = " .
                    "'".str_replace("'", "''", $updatedFromIp)."'".
                " WHERE " .
                "user_id = $userId";
                
        execSQL($sql);
    }

?>