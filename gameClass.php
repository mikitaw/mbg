<?

 /* 
 * gameClass.php
 * Manager for table game
 * Revised
 *
 */


class game {

        var $gameID = 0; // '' // ?
        var $gameName = ""; // '' // ?
		var $roundNumber = 0;
        var $created_on = null; // '' // ?
        var $updated_on = null; // '' // ?
        


    function getList() {
        $sql =
            "SELECT " .
                "gameID " .
                ", gameName " .
				", roundNumber " .
                ", created_on " .
                ", updated_on " .
                
                " FROM game ORDER BY ".
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
            $game = new game();
            $game->loadFromDataRow($row);
            $result[] = $game;
    	}
    	return $result;
    }

    function loadValues($id = null) {

        $sql = 
                "SELECT " .
                "gameID " .
                ",gameName " .
				", roundNumber " .
                ",created_on " .
                ",updated_on " .
                

                " FROM game ";

        if (strstr(strtolower($sql), 'where') == 0) {
            $sql .= " WHERE ";
        } else {
            $sql .= " AND ";
        }
            
        $sql .= "gameID = " . ($id != null ? $id : $this->gameID);
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);

        $this->loadFromDataRow($row);

        return $row;
    }


    function loadFromDataRow($row) {

        if ($row == null) return null;

        
        $this->gameID = getIntValue($row['gameID']);
        $this->gameName = getStringValue($row['gameName']);
		$this->roundNumber = getIntValue($row['roundNumber']);
        $this->created_on = displayDate($row['created_on']);
        $this->updated_on = displayDate($row['updated_on']);
    }


    function setValues($fromArray) {

        $this->gameID = getIntValue($fromArray['gameID']);
        $this->gameName = getStringValue($fromArray['gameName']);
		$this->roundNumber = getIntValue($fromArray['roundNumber']);
        $this->created_on = getDateValue($fromArray['created_on']);
        $this->updated_on = getDateValue($fromArray['updated_on']);
        
    }

    function create() {
        $sql =
            "INSERT INTO game ( " .
                "gameName" .
				", roundNumber" .
                ", created_on" .
                ", updated_on" .
                
            ") VALUES (" .
            ""."'".str_replace("'", "''", $this->gameName)."'".
				", ".$this->roundNumber .
                ", ".getDateValue4DB($this->created_on) .
                ", ".getDateValue4DB($this->updated_on) .
            ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        $this->gameID = $id;
                            
        return $id;
    }

    function update2() {
        $sql =
            "UPDATE game SET " .
                "gameName = " .
                    "'".str_replace("'", "''", $this->gameName)."'".
                ", roundNumber = " .
                    $this->roundNumber .
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "gameID = " . $this->gameID;
                
        execSQL($sql);
    }
	
	function update() {
        $sql = "UPDATE game SET ";
		$and = '';
		if ($this->gameName != "") {
			$sql .= "gameName = " . "'" . str_replace("'", "''", $this->gameName) . "'";
			$and = ', ';
		};
		if ($this->roundNumber != 0) {
			$sql .= $and . "roundNumber = " . $this->roundNumber;
			$and = ', ';
		};
		if ($this->updated_on != null) {
			$sql .= $and . "updated_on = " . getDateValue4DB($this->updated_on);
		};
        $sql .= " WHERE " . "gameID = " . $this->gameID;                
        execSQL($sql);
    }

    function delete() {
        $sql =
            "DELETE FROM game " .
            " WHERE " .
                "gameID = " . $this->gameID;
                
        execSQL($sql);
    }

    function find() {

        $sql = 
                "SELECT " .
                "gameID " .
                ",gameName " .
				",roundNumber " .
                ",created_on " .
                ",updated_on " .
                

                " FROM game ";

        $andStatement = "";
        $and = "";
        
        if ("" != $this->gameName) {
            $andStatement .= $and . "gameName like " . "'".str_replace("'", "''", $this->gameName)."'";
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
                "gameID";
            
        return execSQL($sql);
    }

}

?>