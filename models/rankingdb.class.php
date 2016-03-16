<?php

class rankingdb 
{
    public $pdo;

    public $dbStatus = true;

    public function __construct() 
    {
        $this->pdo = $this->dbConnect();
        if(NULL === $this->pdo) {
            $this->dbStatus = false;
        }
    } 

    public function dbConnect() 
    {
        $pdo = NULL;
        try {
            $pdo = new PDO('sqlsrv:server=' . CONFIG_DATABASE_HOST . ';Database=' . CONFIG_DATABASE_RANKING_NAME . ';LoginTimeout=5', CONFIG_DATABASE_USER, CONFIG_DATABASE_PASSWORD);
        } catch (PDOException $exception) {
            //echo $exception;
        }
        return $pdo;
    }

    public static function getDbConnectionStatus() {
        $rankingdb = new rankingdb();
        return $rankingdb->dbStatus;
    }

    public function getCharacterDevilSquareRanking($limit = 100) {
        $sql = "
            SELECT TOP " . $limit . "
                CharacterName AS Name,
                SUM(Point) AS Score
            FROM 
                EVENT_INFO 
            GROUP BY 
                CharacterName
            ORDER BY
                SUM(Point) DESC, CharacterName ASC
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            if (true === db::getDbConnectionStatus()) {
                $db = new db();
                foreach ($results as $key => $character) {
                    $characterDetails = $db->getCharacterDetails($character['Name']);
                    if (!empty($characterDetails)) {
                        if ($characterDetails['CtlCode'] !== '0') {
                            unset($results[$key]);
                        }
                    }
                }
                ksort($results);
                return $results;
            }
        }
        return [];
    }

    public function getCharacterChaosCastleWinsRanking($limit = 100) {
        $sql = "
            SELECT TOP " . $limit . "
                Name,
                SUM(Wins) AS Score
            FROM 
                EVENT_INFO_CC
            GROUP BY 
                Name
            HAVING
                SUM(Wins) > 0
            ORDER BY
                SUM(Wins) DESC, Name ASC
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            if (true === db::getDbConnectionStatus()) {
                $db = new db();
                foreach ($results as $key => $character) {
                    $characterDetails = $db->getCharacterDetails($character['Name']);
                    if (!empty($characterDetails)) {
                        if ($characterDetails['CtlCode'] !== '0') {
                            unset($results[$key]);
                        }
                    }
                }
                ksort($results);
                return $results;
            }
        }
        return [];
    }
}