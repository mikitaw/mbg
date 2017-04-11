<?

 /* 
 * gamePlayerClass.php
 * Manager for table gamePlayer
 * Revised
 *
 */


class gamePlayer {

        var $gamePlayerID = 0; // '' // ?
        var $gameID = 0; // '' // ?
        var $playerID = 0; // '' // ?
        var $playerOrder = 0; // '' // ?
        var $scoreMultiplier = 0.0; // '' // ?
        var $created_on = null; // '' // ?
        var $updated_on = null; // '' // ?
        
    function getList() {
        $sql =
            "SELECT " .
				"gp.gameID " .
                ", p.playerID " .
                ", p.playerName " .
                //", playerPhoto " .
                ", p.created_on " .
                ", p.updated_on " .
                ", gp.playerOrder " .                
                " FROM player p ". 				
				" INNER JOIN gameplayer gp ON gp.playerID = p.playerID " .				
				//" AND gp.gameID = $this->gameID ". 				
				" ORDER BY ".
                "p.updated_on";
				
		return execSQL($sql);
    }

    function getListCurGame() {
        $sql =
            "SELECT " .
                "p.playerID " .
                ", playerName " .
                ", playerPhoto " .
                ", p.created_on " .
                ", p.updated_on " .
               // ", gp.playerOrder " .                
                " FROM player p ". 				
				" INNER JOIN gameplayer gp ON gp.playerID = p.playerID " .				
				" AND gp.gameID = $this->gameID ". 				
				" ORDER BY ".
                "gp.playerOrder";
				
		return execSQL($sql);
    }

    /*function getList2() {
        $sql =
            "SELECT " .
                "gamePlayerID " .
                ", gameID " .
                ", playerID " .
                ", playerOrder " .
                ", scoreMultiplier " .
                ", created_on " .
                ", updated_on " .
                
                " FROM gameplayer ORDER BY ".
                "gamePlayerID";
            
        return execSQL($sql);
    }
*/
    function getObjectList() {
        $result = array();
        $rs = $this->getList();
        if ($rs == null)
        {
            echo "Error occured! <!--RS is null-->";
            return $result;
        }

        while ($row = mysql_fetch_array($rs)) {
            $result[] = $this->loadFromDataRow($row);
    	}
    	return $result;
    }

    function findObjectList() {
        $result = array();
        $rs = $this->find();
        if ($rs == null)
        {
            return $result;
        }

        while ($row = mysql_fetch_array($rs)) {
            $gamePlayer = new gamePlayer();
            $gamePlayer->loadFromDataRow($row);
            $result[] = $gamePlayer;
    	}
    	return $result;
    }

    function loadValues($id = null) {

        $sql = 
                "SELECT " .
                "gamePlayerID " .
                ",gameID " .
                ",playerID " .
                ",playerOrder " .
                ",scoreMultiplier " .
                ",created_on " .
                ",updated_on " .
                

                " FROM gameplayer ";

        if (strstr(strtolower($sql), 'where') == 0) {
            $sql .= " WHERE ";
        } else {
            $sql .= " AND ";
        }
            
        $sql .= "gamePlayerID = " . ($id != null ? $id : $this->gamePlayerID);
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);

        $this->loadFromDataRow($row);

        return $row;
    }


    function loadFromDataRow($row) {

        if ($row == null) return null;

        
        $this->gamePlayerID = getIntValue($row['gamePlayerID']);
        $this->gameID = getIntValue($row['gameID']);
        $this->playerID = getIntValue($row['playerID']);
        $this->playerOrder = getIntValue($row['playerOrder']);
        $this->scoreMultiplier = getDoubleValue($row['scoreMultiplier']);
        $this->created_on = displayDate($row['created_on']);
        $this->updated_on = displayDate($row['updated_on']);
    }


    function setValues($fromArray) {

        $this->gamePlayerID = getIntValue($fromArray['gamePlayerID']);
        $this->gameID = getIntValue($fromArray['gameID']);
        $this->playerID = getIntValue($fromArray['playerID']);
        $this->playerOrder = getIntValue($fromArray['playerOrder']);
        $this->scoreMultiplier = getDoubleValue($fromArray['scoreMultiplier']);
        $this->created_on = getDateValue($fromArray['created_on']);
        $this->updated_on = getDateValue($fromArray['updated_on']);
        
    }

    function create() {
        $sql =
            "INSERT INTO gameplayer ( " .
                "gameID" .
                ", playerID" .
                ", playerOrder" .
                //", scoreMultiplier" .
                //", created_on" .
                //", updated_on" .
                
            ") VALUES (" .
            "".$this->gameID.
                ", ".$this->playerID.
                ", ".$this->playerOrder.
                //", ".$this->scoreMultiplier.
                //", ".getDateValue4DB($this->created_on) .
                //", ".getDateValue4DB($this->updated_on) .
            ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        $this->gamePlayerID = $id;
                            
        return $id;
    }

    function update() {
        $sql =
            "UPDATE gameplayer SET " .
                "gameID = " .
                    $this->gameID.
                ", playerID = " .
                    $this->playerID.
                ", playerOrder = " .
                    $this->playerOrder.
                ", scoreMultiplier = " .
                    $this->scoreMultiplier.
                ", created_on = " .
                    getDateValue4DB($this->created_on) .
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "gamePlayerID = " . $this->gamePlayerID;
                
        execSQL($sql);
    }

    function delete() {
        $sql =
            "DELETE FROM gameplayer " .
            " WHERE " .
                "gamePlayerID = " . $this->gamePlayerID;
                
        execSQL($sql);
    }

    function getFirstPlayerID()
    {
        $sql = 
                "SELECT " .
                "gamePlayerID " .
                " FROM gameplayer WHERE gameID = $this->gameID ORDER BY playerOrder  LIMIT 0, 1";
//die($sql);                
        $dt = execSQL($sql);
//die($dt);
//        if (($dt == null) || ($dt[0] == null)) die('Please setup game players first!');
        return $dt[0];
    }

    function find() {

        $sql = 
                "SELECT " .
                "gamePlayerID " .
                ",gameID " .
                ",gp.playerID " .
                ",playerOrder " .
                ",scoreMultiplier " .
                " ,p.playerName " .
                

                " FROM gameplayer gp INNER JOIN player p ON gp.playerID = p.playerID";

        $andStatement = "";
        $and = "";
        
        if (0 != $this->gameID) {
            $andStatement .= $and . "gameID like " . $this->gameID;
            $and = " AND ";
        }
        
        if (0 != $this->playerID) {
            $andStatement .= $and . "playerID like " . $this->playerID;
            $and = " AND ";
        }
        
        if (0 != $this->playerOrder) {
            $andStatement .= $and . "playerOrder like " . $this->playerOrder;
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
                "gamePlayerID";
            
        return execSQL($sql);
    }

}

?>