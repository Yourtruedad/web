<?php

class cacheDb 
{
    private $pdo;

    public $cacheDbStatus = true;

    public $onlineCountServerInformationName = 'online_count';

    public $gameserverStatusInformationName = 'game_status';

    const CACHE_PLAYER_ONLINE_COUNT_TIME = 10; // in minutes

    const CACHE_GAMESERVER_STATUS_TIME = 3; // in minutes

    public function __construct() {
        $this->pdo = $this->dbConnect();
        if(NULL === $this->pdo) {
            $this->cacheDbStatus = false;
        }
    }

    private function dbConnect() {
        $pdo = NULL;
        try {
            $pdo = new PDO('mysql:host=' . CONFIG_MYSQL_DATABASE_HOST . ';dbname=' . CONFIG_MYSQL_DATABASE_NAME . ';charset=utf8', CONFIG_MYSQL_DATABASE_USER, CONFIG_MYSQ_DATABASE_PASSWORD);
        } catch (PDOException $exception) {
            //echo $exception;
        }
        return $pdo;
    }

    public static function getCacheDbConnectionStatus() {
        $cacheDb = new cacheDb();
        return $cacheDb->cacheDbStatus;
    }

    public function getCurrentBasicRankingDetails() {
        $sql = '
            SELECT
                `id`,
                `valid_till`,
                `created_on`
            FROM
                `basic_rankings`
            WHERE
                `valid_till` > NOW()
            ORDER BY 
                `valid_till` DESC
            LIMIT 1
        ';

        $query = $this->pdo->query($sql);
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function checkIfBasicRankingIsCurrent() {
        $currentBasicRanking = $this->getCurrentBasicRankingDetails();
        if (!empty($currentBasicRanking)) {
            return true;
        }
        return false;
    }

    public function saveNewBasicRanking() {
        $sql = '
            INSERT INTO
                `basic_rankings`
                (
                    `valid_till`
                )
            VALUES
                (
                    NOW() + INTERVAL ' . CACHE_BASIC_RANKING_TIME . ' MINUTE
                )
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $result = $query->execute();
        return $result;
    }

    public function saveBasicRankingStandings(array $ranking) {
        if (!empty($ranking)) {
            $currentBasicRankingDetails = $this->getCurrentBasicRankingDetails();
            if (empty($currentBasicRankingDetails)) {
            	$newBasicRanking = $this->saveNewBasicRanking();
            	if (true === $newBasicRanking) {
            		$currentBasicRankingDetails = $this->getCurrentBasicRankingDetails();
            	}
            }
            if (!empty($currentBasicRankingDetails)) {
                try {
                    $sql = '
                        INSERT INTO 
                            `basic_ranking_standings` 
                            (
                                `basic_rankings_id`, 
                                `standing`, 
                                `name`,
                                `status_online`,
                                `country`, 
                                `class`, 
                                `reset`, 
                                `level`, 
                                `master_level`,
                                `guild_name`
                            )
                        VALUES 
                            (
                                :basicRankingsId,
                                :standing,
                                :name,
                                :statusOnline,
                                :country,
                                :class,
                                :reset,
                                :level,
                                :masterLevel,
                                :guildName
                            )
                    ';

                    $query = $this->pdo->prepare($sql);
                    foreach ($ranking as $key => $character) {
                        $standing = $key + 1;
                        $onlineStatus = common::changeNullToZero($character['StatusOnline']);
                        $query->bindParam(':basicRankingsId', $currentBasicRankingDetails['id'], PDO::PARAM_INT);
                        $query->bindParam(':standing', $standing, PDO::PARAM_INT);
                        $query->bindParam(':name', $character['Name'], PDO::PARAM_STR);
                        $query->bindParam(':statusOnline', $onlineStatus, PDO::PARAM_INT);
                        $query->bindParam(':country', $character['Country'], PDO::PARAM_STR);
                        $query->bindParam(':class', $character['Class'], PDO::PARAM_INT);
                        $query->bindParam(':reset', $character['Reset'], PDO::PARAM_INT);
                        $query->bindParam(':level', $character['cLevel'], PDO::PARAM_INT);
                        $query->bindParam(':masterLevel', $character['mLevel'], PDO::PARAM_INT);
                        $query->bindParam(':guildName', $character['GuildName'], PDO::PARAM_STR);
                        $result = $query->execute();
                    }
                } catch (PDOException $e) {
                    return false;
                } 
                return true;
            }
        }
        return false;
    }

    public function getCurrentBasicRanking($limit = 100) {
    	$currentBasicRankingDetails = $this->getCurrentBasicRankingDetails();
    	if (!empty($currentBasicRankingDetails)) {
	        $sql = '
	            SELECT
	                `id`,
	                `basic_rankings_id`,
	                `standing`,
	                `name` AS `Name`,
                    `status_online` AS `StatusOnline`,
	                `country` AS `Country`, 
	                `class` AS `Class`, 
	                `reset` AS `Reset`, 
	                `level` AS `cLevel`, 
	                `master_level` AS `mLevel`,
                    `guild_name` AS `GuildName`
	            FROM 
	                `basic_ranking_standings`
	            WHERE
	                `basic_rankings_id` = :id
	            ORDER BY 
	                `standing` ASC
                LIMIT ' . $limit . '
	        ';
	        $query = $this->pdo->prepare($sql);
	        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $query->bindParam(':id', $currentBasicRankingDetails['id'], PDO::PARAM_INT);
	        $query->execute();
	        $results = $query->fetchAll(PDO::FETCH_ASSOC);
	        if (!empty($results)) {
	        	return $results;
	        }
        }
        return [];
    }

    public function saveCurrentServerInformation($name, $interval, $value = NULL) {
        $sql = '
            INSERT INTO 
                `server_information_details`
                (
                    `server_informations_id`,
                    `value`,
                    `valid_till`
                )
            VALUES
                (
                    (
                        SELECT
                            `id`
                        FROM
                            `server_information`
                        WHERE
                            `name` = :name
                        LIMIT 1
                    ),
                    :value,
                    NOW() + INTERVAL :interval MINUTE
                )
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':value', $value, PDO::PARAM_STR);
        $query->bindParam(':interval', $interval, PDO::PARAM_INT);
        $result = $query->execute();
        return $result;
    }

    public function checkIfServerInformationIsCurrent($name) {
        $serverInformation = $this->getCurrentServerInformation($name);
        if (!empty($serverInformation)) {
            return true;
        }
        return false;
    }

    public function getCurrentServerInformation($name) {
        $sql = '
            SELECT
                sid.`id`,
                `server_informations_id`,
                `name`,
                `value`,
                `valid_till`,
                `created_on`
            FROM
                `server_information_details` sid
            JOIN
                `server_information` si
            ON
                sid.`server_informations_id` = si.`id`
            WHERE
                `name` = :name
            AND
                `valid_till` > NOW()
            ORDER BY 
                `valid_till` DESC
            LIMIT 1
        ';

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $result = $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }
	
	public function createPaypalTransaction($username, $token) {
		$sql = '
            INSERT INTO 
                `paypal_transactions`
                (
                    `transaction_token`,
                    `username`
                )
            VALUES
                (
                    :token,
                    :username
                )
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
	}
	
	public function checkIfPaypalTransactionTokenIsAvailable($token) {
        $sql = '
            SELECT 
                transaction_token
            FROM 
                paypal_transactions
            WHERE 
                transaction_token = :token
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($results)) {
            // Token available
            return true;
        }
        // Token not available
        return false;
    }
	
	public function getPaypalTransactionDetailsByToken($token) {
        $sql = '
            SELECT
                `id`,
                `transaction_token`,
                `username`,
                `status`,
                `created_on`
            FROM
                `paypal_transactions`
			WHERE
			    `transaction_token` = :token
            LIMIT 1
        ';

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $result = $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }
}