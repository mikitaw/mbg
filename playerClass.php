<?

 /* 
 * playerClass.php
 * Manager for table player
 * Revised
 *
 */


class player {

        var $playerID = 0; // '' // ?
        var $playerName = ""; // '' // ?
        var $playerPhoto = ""; // '' // ?
        var $created_on = null; // '' // ?
        var $updated_on = null; // '' // ?
		
    function getList() {
        $sql =            
		"SELECT " .
		"playerID " .
		", playerName " .
		", playerPhoto " .
		", created_on " .
		", updated_on " .
		" FROM player ORDER BY ".
		"updated_on ".
		"DESC";
		return execSQL($sql);
	}

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
            $player = new player();
            $player->loadFromDataRow($row);
            $result[] = $player;
    	}
    	return $result;
    }

    function loadValues($id = null) {

        $sql = 
                "SELECT " .
                "playerID " .
                ",playerName " .
                ",playerPhoto " .
                ",created_on " .
                ",updated_on " .
                

                " FROM player ";

        if (strstr(strtolower($sql), 'where') == 0) {
            $sql .= " WHERE ";
        } else {
            $sql .= " AND ";
        }
            
        $sql .= "playerID = " . ($id != null ? $id : $this->playerID);
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);

        $this->loadFromDataRow($row);

        return $row;
    }


    function loadFromDataRow($row) {

        if ($row == null) return null;

        
        $this->playerID = getIntValue($row['playerID']);
        $this->playerName = getStringValue($row['playerName']);
        $this->playerPhoto = getStringValue($row['playerPhoto']);
        $this->created_on = displayDate($row['created_on']);
        $this->updated_on = displayDate($row['updated_on']);
    }


    function setValues($fromArray) {

        $this->playerID = getIntValue($fromArray['playerID']);
        $this->playerName = getStringValue($fromArray['playerName']);
        $this->playerPhoto = getStringValue($fromArray['playerPhoto']);
        $this->created_on = getDateValue($fromArray['created_on']);
        $this->updated_on = getDateValue($fromArray['updated_on']);
        
    }

    function create() {
        $sql =
            "INSERT INTO player ( " .
                "playerName" .
                ", playerPhoto" .
                ", created_on" .
                ", updated_on" .
                
            ") VALUES (" .
            ""."'".str_replace("'", "''", $this->playerName)."'".
                ", "."'".str_replace("'", "''", $this->playerPhoto)."'".
                ", ".getDateValue4DB($this->created_on) .
                ", ".getDateValue4DB($this->updated_on) .
            ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        $this->playerID = $id;
                            
        return $id;
    }

    function update() {
        $sql =
            "UPDATE player SET " .
                "playerName = " .
                    "'".str_replace("'", "''", $this->playerName)."'".
                ", playerPhoto = " .
                    "'".str_replace("'", "''", $this->playerPhoto)."'".
                ", created_on = " .
                    getDateValue4DB($this->created_on) .
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "playerID = " . $this->playerID;
                
        execSQL($sql);
    }

    function delete() {
        $sql =
            "DELETE FROM player " .
            " WHERE " .
                "playerID = " . $this->playerID;
                
        execSQL($sql);
    }

    function find() {

        $sql = 
                "SELECT " .
                "playerID " .
                ",playerName " .
                ",playerPhoto " .
                ",created_on " .
                ",updated_on " .
                

                " FROM player ";

        $andStatement = "";
        $and = "";
        
        if ("" != $this->playerName) {
            $andStatement .= $and . "playerName like " . "'".str_replace("'", "''", $this->playerName)."'";
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
                "playerID";
            
        return execSQL($sql);
    }

}

?>