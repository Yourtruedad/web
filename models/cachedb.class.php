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
    
    public function completePaypalTransaction($token, $product) {
        $sql = '
            UPDATE 
                `paypal_transactions`
            SET
                status = 1,
                product = :product
            WHERE
                transaction_token = :token
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':product', $product, PDO::PARAM_STR);
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $result = $query->execute();
        //return $query->errorInfo();
        return $result;
    }
    
    public function savePaypalIpn($token, $product) {
        $sql = '
            INSERT INTO 
                `paypal_ipn`
                (
                    `token`,
                    product
                )
            VALUES
                (
                    :token,
                    :product
                )
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $query->bindParam(':product', $product, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }
    
    public function getPaypalIpnsPerUser($username) {
        $sql = '
            SELECT
                pi.id,
                pi.token,
                pi.`product`,
                pt.username,
                pt.status
            FROM
                `paypal_ipn` pi
            JOIN 
                paypal_transactions pt
            ON 
                pi.token = pt.transaction_token
            WHERE 
                pt.username = :username
            AND
                pt.status = 0
        ';

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function getCurrentScoreRankingDetails() {
        $sql = '
            SELECT
                `id`,
                `valid_till`,
                `created_on`
            FROM
                `score_rankings`
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

    public function checkIfScoreRankingIsCurrent() {
        $currentScoreRanking = $this->getCurrentScoreRankingDetails();
        if (!empty($currentScoreRanking)) {
            return true;
        }
        return false;
    }

    public function saveNewScoreRanking() {
        $sql = '
            INSERT INTO
                `score_rankings`
                (
                    `valid_till`
                )
            VALUES
                (
                    NOW() + INTERVAL ' . CACHE_MAIN_RANING_TIME . ' MINUTE
                )
        ';
        $query = $this->pdo->prepare($sql);
        //$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $result = $query->execute();
        return $result;
    }

    public function saveScoreRankingStandings(array $ranking) {
        if (!empty($ranking)) {
            $currentScoreRankingDetails = $this->getCurrentScoreRankingDetails();
            if (empty($currentScoreRankingDetails)) {
                $newScoreRanking = $this->saveNewScoreRanking();
                if (true === $newScoreRanking) {
                    $currentScoreRankingDetails = $this->getCurrentScoreRankingDetails();
                }
            }
            if (!empty($currentScoreRankingDetails)) {
                try {
                    $sql = '
                        INSERT INTO 
                            `score_ranking_standings` 
                            (
                                `score_rankings_id`, 
                                `standing`, 
                                `name`,
                                `country`, 
                                `class`,
                                `score`,
                                `reset`, 
                                `level`, 
                                `master_level`
                            )
                        VALUES 
                            (
                                :scoreRankingsId,
                                :standing,
                                :name,
                                :country,
                                :class,
                                :score,
                                :reset,
                                :level,
                                :masterLevel
                            )
                    ';

                    $query = $this->pdo->prepare($sql);
                    foreach ($ranking as $key => $character) {
                        $standing = $key + 1;
                        $query->bindParam(':scoreRankingsId', $currentScoreRankingDetails['id'], PDO::PARAM_INT);
                        $query->bindParam(':standing', $standing, PDO::PARAM_INT);
                        $query->bindParam(':name', $character['Name'], PDO::PARAM_STR);
                        $query->bindParam(':country', $character['Country'], PDO::PARAM_STR);
                        $query->bindParam(':class', $character['Class'], PDO::PARAM_INT);
                        $query->bindParam(':score', $character['Score'], PDO::PARAM_INT);
                        $query->bindParam(':reset', $character['Reset'], PDO::PARAM_INT);
                        $query->bindParam(':level', $character['cLevel'], PDO::PARAM_INT);
                        $query->bindParam(':masterLevel', $character['mLevel'], PDO::PARAM_INT);
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

    public function getCurrentScoreRanking($limit = 100) {
        $currentScoreRankingDetails = $this->getCurrentScoreRankingDetails();
        if (!empty($currentScoreRankingDetails)) {
            $sql = '
                SELECT
                    `id`,
                    `score_rankings_id`,
                    `standing`,
                    `name` AS `Name`,
                    `country` AS `Country`, 
                    `class` AS `Class`, 
                    `score` AS `Score`,
                    `reset` AS `Reset`, 
                    `level` AS `cLevel`, 
                    `master_level` AS `mLevel`,
                    `guild_name` AS `GuildName`
                FROM 
                    `score_ranking_standings`
                WHERE
                    `score_rankings_id` = :id
                ORDER BY 
                    `standing` ASC
                LIMIT ' . $limit . '
            ';
            $query = $this->pdo->prepare($sql);
            $query->bindParam(':id', $currentScoreRankingDetails['id'], PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($results)) {
                return $results;
            }
        }
        return [];
    }
	
	public static function saveLog($content, $ip) {
		if (true === cacheDb::getCacheDbConnectionStatus()) {
			$cacheDb = new cacheDb();
			$sql = "
			    INSERT INTO
				    logs
				    (
					    content,
						ip
					) 
				VALUES
				    (
					    :content,
						:ip
					)
			";
			$query = $cacheDb->pdo->prepare($sql);
            $query->bindParam(':content', $content, PDO::PARAM_STR);
			$query->bindParam(':ip', $ip, PDO::PARAM_STR);
			return $query->execute();
		}
		return false;
	}
}