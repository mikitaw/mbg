<?

 /* 
 * gameRoundClass.php
 * Manager for table gameRound
 * Revised
 *
 */


class gameRound {

        //var $gameRoundID = 0; // '' // ?
        var $gameID = 0; // '' // ?
        var $roundNumber = 0; // '' // ?
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
                "gameID";
            
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
            $gameRound = new gameRound();
            $gameRound->loadFromDataRow($row);
            $result[] = $gameRound;
    	}
    	return $result;
    }

    function loadValues($id = null) {

        $sql = 
                "SELECT " .
                "gameID " .
                ",gameName " .
                ",roundNumber " .
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

        
        //$this->gameRoundID = getIntValue($row['gameRoundID']);
        $this->gameID = getIntValue($row['gameID']);
        $this->roundNumber = getIntValue($row['roundNumber']);
        $this->created_on = displayDate($row['created_on']);
        $this->updated_on = displayDate($row['updated_on']);
    }


    function setValues($fromArray) {

        //$this->gameRoundID = getIntValue($fromArray['gameRoundID']);
        $this->gameID = getIntValue($fromArray['gameID']);
        $this->roundNumber = getIntValue($fromArray['roundNumber']);
        $this->created_on = getDateValue($fromArray['created_on']);
        $this->updated_on = getDateValue($fromArray['updated_on']);
        
    }

    /*function create() {
        $sql =
            "INSERT INTO gameround ( " .
                "gameID" .
                ", roundNumber" .
                ", created_on" .
                ", updated_on" .
                
            ") VALUES (" .
            "".$this->gameID.
                ", ".$this->roundNumber.
                ", ".getDateValue4DB($this->created_on) .
                ", ".getDateValue4DB($this->updated_on) .
            ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        $this->gameRoundID = $id;
                            
        return $id;
    }*/

    function update() {
        $sql =
            "UPDATE game SET " .
                "roundNumber = " .
                    $this->roundNumber.
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "gameID = " . $this->gameID;
                
        execSQL($sql);
		
		$this->roundNumber = $id;
        
		return $id;
    }

    function delete() {
        $sql =
            "UPDATE game SET " .
                "roundNumber = 0" .
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "gameID = " . $this->gameID;
                
        execSQL($sql);
    }

	function getNextRoundNumber() {
        $sql = 
                "SELECT " .
                "roundNumber " .
                " FROM game ".
				" WHERE " .
                "gameID = " . $this->gameID;

        /*$andStatement = "";
        $and = "";
        
        if (0 != $this->gameID) {
            $andStatement .= $and . "gameID like " . $this->gameID;
            $and = " AND ";
        }
        

        if ($andStatement != "") {
            if (strstr(strtolower($sql), 'where') == 0) {
                $sql .= " WHERE ";
            } else {
                $sql .= " AND ";
            }
            $sql .= $andStatement;
        }*/
            
        $dt = execSQL($sql);
		$array = mysql_fetch_array($dt);
		//return $array[0];
        if ($array == null) {
			return 0;
		} elseif ($array[0] == 0) {
			return 1;
		} else {
			return $array[0] + 1;
		}
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
        
        if (0 != $this->gameID) {
            $andStatement .= $and . "gameID like " . $this->gameID;
            $and = " AND ";
        }
        
        if (0 != $this->roundNumber) {
            $andStatement .= $and . "roundNumber like " . $this->roundNumber;
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