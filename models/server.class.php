<?php

class server 
{
    public $eventTypeRecurringName = 'recurring'; // ds, bc, cc
    
    public $eventTypeSpecificName = 'specific'; // CryWolf, Castle Siege
    
    public $eventDates = [
        'Arca Battle' => ['type' => 'specific', 'details' => [['day' => 'Sunday', 'time' => '22:00'], ['day' => 'Wednesday', 'time' => '22:00'], ['day' => 'Friday', 'time' => '22:00']]],
        'Acheron Guardian' => ['type' => 'specific', 'details' => [['day' => 'Tuesday', 'time' => '21:00'], ['day' => 'Thursday', 'time' => '21:00'], ['day' => 'Saturday', 'time' => '21:00']]],
        'Castle Siege' => ['type' => 'specific', 'details' => [['day' => 'Saturday', 'time' => '20:00']]],
        'CryWolf' => ['type' => 'specific', 'details' => [['day' => 'Wednesday', 'time' => '20:00'], ['day' => 'Friday', 'time' => '20:00'], ['day' => 'Sunday', 'time' => '20:00']]],
        'Blood Castle' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['01:30', '03:30', '05:30', '07:30', '09:30', '11:30', '13:30', '15:30', '17:30', '19:30', '21:30', '23:30']]],
        'Devil Square' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00']]],
        'Chaos Castle' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['01:00', '05:00', '09:00', '13:00', '17:00', '21:00']]],
        'White Wizard Invasion' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00']]],
        'Tormented Square' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['00:20', '03:20', '06:20', '09:20', '12:20', '15:20', '18:20', '21:20']]],
        'Illusion Temple' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['03:40', '07:40', '11:40', '15:40', '19:40', '23:40']]],
        'Chaos Castle Survival' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['15:00', '19:00', '23:00']]],
        'Last Man Standing' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['08:00', '14:00', '20:00', '23:30']]],
        'Loren Deep' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['09:00', '21:00']]],
        'Santa Claus' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['01:20', '07:20', '13:20', '19:20']]],
        'Golden Invasion' => ['type' => 'specific', 'details' => ['day' => 'everyday', 'times' => ['02:30', '10:30', '18:30', '22:30']]],
		//'EXAMPLE RECURRING EVENT' => ['type' => 'recurring', 'details' => ['time_unit' => 'hour', 'value' => 4]],
    ];

    public static $serverLevelRankingTopPlayersLimit = 100;

    public static $serverScoreRankingTopPlayersLimit = 100;
    
    public static function getCharacterRanking($top = 100) {
        $characterRanking = [];
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfBasicRankingIsCurrent()) {
                $characterRanking = $cacheDb->getCurrentBasicRanking($top);
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
        if (!empty($characterRanking)) {
            $characterRanking = array_slice($characterRanking, 0, $top);
        }
        return $characterRanking;
    }
    
    public function getUpcomingEvents($daysNumber = '2') {
        $events = [];
        foreach ($this->eventDates  as $eventName => $eventDate) {
            if ($this->eventTypeRecurringName === $eventDate['type']) {
                // THIS IF IS NOT USED - INACTIVE
                $startingDate = common::currentDate('Y-m-d') . ' 00:00:00';
                $endingDate = common::addTimeToDate($startingDate, 'Y-m-d', $daysNumber, 'D') . ' 00:00:00';
                $eventDateTime = common::addTimeToDate($startingDate, 'Y-m-d H:i:s', $eventDate['details']['value'], common::translateDateWordToDateTimeFormat($eventDate['details']['time_unit']));
                $loopControl = 0;
                while ($eventDateTime <= $endingDate && $loopControl < 100) {
                    if ($eventDateTime > common::currentDate('Y-m-d H:i:s')) {
                        $events[$eventName][] = $eventDateTime;
                    }
                    $eventDateTime = common::addTimeToDate($eventDateTime, 'Y-m-d H:i:s', $eventDate['details']['value'], common::translateDateWordToDateTimeFormat($eventDate['details']['time_unit']));
                    $loopControl++;
                }
            } elseif ($this->eventTypeSpecificName === $eventDate['type']) {
                $startingDate = common::currentDate('Y-m-d');
                $endingDate = common::addTimeToDate($startingDate, 'Y-m-d', $daysNumber, 'D');
                
                $eventDateTime = $startingDate;
                for ($x = 0; $x < $daysNumber; $x++) {
                    $eventDateTime = common::addTimeToDate($eventDateTime, 'Y-m-d', $x, 'D');
                    if (isset($eventDate['details']['day']) && 'everyday' === $eventDate['details']['day']) {
                        foreach ($eventDate['details']['times'] as $time) {
                            if ($eventDateTime . ' ' . $time . ':00' > common::currentDate('Y-m-d H:i:s')) {
                                $events[$eventName][] = $eventDateTime . ' ' . $time . ':00';
                            }
                        }
                    } else {
                        foreach ($eventDate['details'] as $eventDateTimeDetails) {
                            if ($eventDateTimeDetails['day'] === date('l', strtotime($eventDateTime)) && $eventDateTime . ' ' . $eventDateTimeDetails['time'] . ':00' > common::currentDate('Y-m-d H:i:s')) {
                                $events[$eventName][] = $eventDateTime . ' ' . $eventDateTimeDetails['time'] . ':00';
                            }
                        }
                    }
                }
            }
        }
        return $events;
    }
    
    public function prepareEvents(array $events) {
        $parsedEvents = [];
        if (!empty($events)) {
            foreach ($events as $eventName => $eventTimes) {
                if (!empty($eventTimes)) {
                    foreach ($eventTimes as $eventTime) {
                        $parsedEvents[] = ['event_name' => $eventName, 'event_time' => $eventTime, 'event_in' => common::calculateTimeDifference(common::currentDate(), $eventTime)];
                    }
                }
            }
            usort($parsedEvents, function($a1, $a2) {
               return strtotime($a1['event_time']) - strtotime($a2['event_time']);
            });
            if (!empty($parsedEvents)) {
                foreach ($parsedEvents as $parsedEventDetailsKey => $parsedEventDetails) {
                    $eventDateTime = explode(' ', $parsedEventDetails['event_time']);
                    if (strtotime(common::currentDate('Y-m-d')) == strtotime($eventDateTime[0])) {
                        $parsedEvents[$parsedEventDetailsKey]['event_time'] = 'Today at ' . $eventDateTime[1];
                    } elseif (strtotime(common::addTimeToDate(common::currentDate('Y-m-d'), 'Y-m-d', 1, 'D')) == strtotime($eventDateTime[0])) {
                        $parsedEvents[$parsedEventDetailsKey]['event_time'] = 'Tomorrow at ' . $eventDateTime[1];
                    }
                }
            }
        }
        return $parsedEvents;
    }
    
    public static function getActiveAccountsRecentlyCount() {
        $onlineCount = 0;
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfServerInformationIsCurrent('active_recently_count')) {
                $activeAccounts = $cacheDb->getCurrentServerInformation('active_recently_count');
                if (!empty($serverInformation)) {
                    $onlineCount = $serverInformation['value'];
                } else {
                    $onlineCount = db::getActiveAccountsRecentlyCount();
                }
            } else {
                $onlineCount = db::getActiveAccountsRecentlyCount();
                $cacheDb->saveCurrentServerInformation('active_recently_count', cacheDb::CACHE_PLAYER_ONLINE_COUNT_TIME, $onlineCount);
            }
        } else {
            $onlineCount = db::getActiveAccountsRecentlyCount();
        }
        return $onlineCount;
    }

    public static function getCharacterScoreRanking($top = 100) {
        $characterRanking = [];
        $character = new character();
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfScoreRankingIsCurrent()) {
                $characterRanking = $cacheDb->getCurrentScoreRanking();
                if (empty($characterRanking)) {
                    $characterRanking = $character->getMainCharacterRanking();
                }
            } else {
                $characterRanking = $character->getMainCharacterRanking();
                $cacheDb->saveScoreRankingStandings($characterRanking);
            }
        } else {
            $characterRanking = $character->getMainCharacterRanking();
        }
        if (!empty($characterRanking)) {
            $characterRanking = array_slice($characterRanking, 0, $top);
        }
        return $characterRanking;
    }

    public static function getScoreRankingTimeSinceLastUpdate() {
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            $currentRankingDetails = $cacheDb->getCurrentScoreRankingDetails();
            
            return common::calculateTimeDifference($currentRankingDetails['created_on'], $currentRankingDetails['valid_till']);
        }
    }
    
    public function getPlayerOfTheDay() {
        $playerOfTheDay = server::getCharacterScoreRanking(1);
        if (!empty($playerOfTheDay)) {
            return current($playerOfTheDay)['Name'];
        }
        return '';
    }
	
	public function drawGuildMark($code,$xy=3) {
		//$code = urlencode(bin2hex($code));
		$color[0] = ''; $color[1]='#000000'; $color[2]='#8c8a8d'; $color[3]='#ffffff'; $color[4]='#fe0000'; $color[5]='#ff8a00'; $color[6]='#ffff00'; $color[7]='#8cff01'; $color[8]='#00ff00'; $color[9]='#01ff8d'; $color['a']='#00ffff'; $color['b']='#008aff'; $color['c']='#0000fe'; $color['d']='#8c00ff'; $color['e']='#ff00fe'; $color['f']='#ff008c'; 
		// Set the default zero position.
		$i = 0; 
		$ii = 0;
		// Create the table
		$it = '<table style="width: '.(8*$xy).'px;height:'.(8*$xy).'px;" border=0 cellpadding=0 cellspacing=0><tr>';
		// Start the logo drawing cycle for each color slot
		while ($i<64) {
			// Get the slot color number
			$place    = $code{$i};
			// Increase the slot
			$i++;$ii++;
			// Get the color of the slot
			$add    = $color[$place];
			// Create the slot with its color
			$it .= '<td class=\'guildlogo\' style=\'background-color: '.$add.';\' width=\''.$xy.'\' height=\''.$xy.'\'></td>';
			// In case we have a new line
			if ($ii==8) { 
				$it .=  '</tr>'; 
				if ($ii != 64) $it .='<tr>';
				$ii =0; 
			}
		}
		// Finish the table off
		$it .= '</table>';
		// What do we output
		return $it;
    }
	
	public static function getServerLastOfflineDate() {
		$lastOnlineDate = '';
		if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
			$cacheServerStatusInformationNames = ['active_game_status', 'passive_game_status'];
			$cacheDb = new cacheDb();
			$serverStatuses = $cacheDb->getServerAvailabilityStatuses($cacheServerStatusInformationNames);
			if (!empty($serverStatuses)) {
				foreach ($serverStatuses as $serverStatus) {
					if ('offline' === $serverStatus['value']) {
						return $serverStatus['created_on'];
					} else {
						$lastOnlineDate = $serverStatus['created_on'];
					}
				}
			}
		}
		return $lastOnlineDate;
	}
	
	public static function getServerOnlineDuration() {
		$lastOfflineDate = server::getServerLastOfflineDate();
		$serverOnlineDuration = '';
		if (!empty($lastOfflineDate)) {
			$common = new common();
			$serverOnlineDuration = $common->calculateTimeDifference($lastOfflineDate, common::currentDate(), 'days');
			if ('2' > $serverOnlineDuration) {
				$serverOnlineDuration = $common->calculateTimeDifference($lastOfflineDate, common::currentDate());
			}
		}
		return $serverOnlineDuration;
	}
	
	public static function getGuildRanking($top = 100) {
        $guildRanking = [];
        $character = new character();
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfScoreRankingIsCurrent()) {
                $guildRanking = $cacheDb->getCurrentScoreRanking();
                if (empty($guildRanking)) {
                    $guildRanking = $character->getMainCharacterRanking();
                }
            } else {
                $guildRanking = $character->getMainCharacterRanking();
                $cacheDb->saveScoreRankingStandings($guildRanking);
            }
        } else {
            $guildRanking = $character->getMainCharacterRanking();
        }
        if (!empty($guildRanking)) {
            $guildRanking = array_slice($guildRanking, 0, $top);
        }
        return $guildRanking;
    }
	
	public function getPlayerOfTheWeek() {
		$cacheDb = new cacheDb();
        $playerOfTheWeek = $cacheDb->getPlayerOfTheWeek();
        if (!empty($playerOfTheWeek)) {
            return $playerOfTheWeek['name'];
        }
        return '';
    }
}