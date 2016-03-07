<?php

class server 
{
    public static function getTop5CharacterRanking() {
        $characterRanking = '';
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfBasicRankingIsCurrent()) {
                $characterRanking = $cacheDb->getCurrentBasicRanking(5);
                if (empty($characterRanking)) {
                    $characterRanking = db::getCharacterRanking();
                }
            } else {
                $characterRanking = db::getCharacterRanking();
                $cacheDb->saveBasicRankingStandings($characterRanking);
            }
        } else {
            $characterRanking = db::getCharacterRanking();
        }

        return array_slice($characterRanking, 0, 5);
    }
}