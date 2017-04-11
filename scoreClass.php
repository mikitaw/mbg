<?

 /* 
 * scoreClass.php
 * Manager for table score
 * Revised
 *
 */


class score {

        var $scoreID = 0; // '' // ?
        var $gameRoundID = 0; // '' // ?
        var $playerID = 0; // '' // ?
        var $score = 0; // '' // ?
        var $bonus = 0; // '' // ?
        var $deduction = 0; // '' // ?
        var $startTime = null; // '' // ?
        var $finishTime = null; // '' // ?
        var $created_on = null; // '' // ?
        var $updated_on = null; // '' // ?
        

    function getBestScores() {
        $sql =
            "SELECT " .
                "scoreID " .
                ", s.playerID " .
                ", p.playerName " .
                ", score " .
				", roundNumber " .
                /*", bonus " .
                ", deduction " .
                ", startTime " .
                ", finishTime " .*/
                
                " FROM score s INNER JOIN player p ON s.playerID = p.playerID ORDER BY ".
                "score ASC LIMIT 0, 50";
            
        return execSQL($sql);
    }



    function getList() {
        $sql =
            "SELECT " .
                "scoreID " .
                ", s.playerID " .
                ", p.playerName " .
                ", score " .
				", roundNumber " .
                /*", bonus " .
                ", deduction " .
                ", startTime " .
                ", finishTime " .*/
                
                " FROM score s INNER JOIN player p ON s.playerID = p.playerID ORDER BY ".
                "scoreID DESC LIMIT 0, 50";
            
        return execSQL($sql);
    }


    function getGameList() {
        $sql =
            "SELECT " .
                " s.playerID " .
                ", p.playerName " .
                ", SUM(score) as totalScore, AVG(score) as averageScore, COUNT(s.playerID) AS roundsPlayed " .
                
                " FROM score s INNER JOIN player p ON s.playerID = p.playerID 
                	INNER JOIN gameround gr on s.gameRoundID = gr.gameRoundID
                	WHERE gr.gameID = " . $this->gameID . "
                GROUP BY s.playerID, p.playerName
                ORDER BY ".
                "averageScore ASC";
//die($sql);            
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
            $score = new score();
            $score->loadFromDataRow($row);
            $result[] = $score;
    	}
    	return $result;
    }

    function loadValues($id = null) {

        $sql = 
                "SELECT " .
                "scoreID " .
                ",gameRoundID " .
                ",playerID " .
                ",score " .
                ",bonus " .
                ",deduction " .
                ",startTime " .
                ",finishTime " .
                ",created_on " .
                ",updated_on " .
                

                " FROM score ";

        if (strstr(strtolower($sql), 'where') == 0) {
            $sql .= " WHERE ";
        } else {
            $sql .= " AND ";
        }
            
        $sql .= "scoreID = " . ($id != null ? $id : $this->scoreID);
                            
            
        $rs = execSQL($sql);
        $row = getFirstRow($rs);

        $this->loadFromDataRow($row);

        return $row;
    }


    function loadFromDataRow($row) {

        if ($row == null) return null;

        
        $this->scoreID = getIntValue($row['scoreID']);
        $this->gameRoundID = getIntValue($row['gameRoundID']);
        $this->playerID = getIntValue($row['playerID']);
        $this->score = getIntValue($row['score']);
        $this->bonus = getIntValue($row['bonus']);
        $this->deduction = getIntValue($row['deduction']);
        $this->startTime = displayDate($row['startTime']);
        $this->finishTime = displayDate($row['finishTime']);
        $this->created_on = displayDate($row['created_on']);
        $this->updated_on = displayDate($row['updated_on']);
    }


    function setValues($fromArray) {

        $this->scoreID = getIntValue($fromArray['scoreID']);
        $this->gameID = getIntValue($fromArray['gameID']);
        $this->playerID = getIntValue($fromArray['playerID']);
		$this->roundNumber = getIntValue($fromArray['roundNumber']);
        $this->score = getIntValue($fromArray['score']);
        $this->bonus = getIntValue($fromArray['bonus']);
        $this->deduction = getIntValue($fromArray['deduction']);
        $this->startTime = getDateValue($fromArray['startTime']);
        $this->finishTime = getDateValue($fromArray['finishTime']);
        $this->created_on = getDateValue($fromArray['created_on']);
        $this->updated_on = getDateValue($fromArray['updated_on']);
        
    }

    function create() {
        $sql =
            "INSERT INTO score ( " .
                "playerID" .
				", gameID" .
				", roundNumber" .
                ", score" .
                ", bonus" .
                ", deduction" .
                ", startTime" .
                ", finishTime" .
                ", created_on" .
                ", updated_on" .
                
            ") VALUES (" .
            "".$this->playerID.
                ", ".$this->gameID.
				", ".$this->roundNumber.
                ", ".$this->score.
                ", ".$this->bonus.
                ", ".$this->deduction.
                ", ".getDateValue4DB($this->startTime) .
                ", ".getDateValue4DB($this->finishTime) .
                ", ".getDateValue4DB($this->created_on) .
                ", ".getDateValue4DB($this->updated_on) .
            ")";
            
        execSQL($sql);

        $sql = "SELECT LAST_INSERT_ID();";
        $rs = execSQL($sql);
        $row = getFirstRow($rs);
        $id = $row[0];

        $this->scoreID = $id;
                            
        return $id;
    }

    function update() {
        $sql =
            "UPDATE score SET " .
                "gameRoundID = " .
                    $this->gameRoundID.
                ", playerID = " .
                    $this->playerID.
                ", score = " .
                    $this->score.
                ", bonus = " .
                    $this->bonus.
                ", deduction = " .
                    $this->deduction.
                ", startTime = " .
                    getDateValue4DB($this->startTime) .
                ", finishTime = " .
                    getDateValue4DB($this->finishTime) .
                ", created_on = " .
                    getDateValue4DB($this->created_on) .
                ", updated_on = " .
                    getDateValue4DB($this->updated_on) .
                " WHERE " .
                "scoreID = " . $this->scoreID;
                
        execSQL($sql);
    }

    function delete() {
        $sql =
            "DELETE FROM score " .
            " WHERE " .
                "scoreID = " . $this->scoreID;
                
        execSQL($sql);
    }
    
    function deleteAll() {
        $sql =
            "DELETE FROM score ";
                
        execSQL($sql);
    }

    function find() {

        $sql = 
                "SELECT " .
                "scoreID " .
                ",gameRoundID " .
                ",playerID " .
                ",score " .
                ",bonus " .
                ",deduction " .
                ",startTime " .
                ",finishTime " .
                ",created_on " .
                ",updated_on " .
                

                " FROM score ";

        $andStatement = "";
        $and = "";
        
        if (0 != $this->gameRoundID) {
            $andStatement .= $and . "gameRoundID like " . $this->gameRoundID;
            $and = " AND ";
        }
        
        if (0 != $this->playerID) {
            $andStatement .= $and . "playerID like " . $this->playerID;
            $and = " AND ";
        }
        
        if (0 != $this->score) {
            $andStatement .= $and . "score like " . $this->score;
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
                "scoreID";
            
        return execSQL($sql);
    }

}

?>