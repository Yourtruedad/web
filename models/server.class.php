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
        //'EXAMPLE RECURRING EVENT' => ['type' => 'recurring', 'details' => ['time_unit' => 'hour', 'value' => 4]],
    ];
    
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
    
    public function getUpcomingEvents($daysNumber = '2') {
        $events = [];
        foreach ($this->eventDates as $eventName => $eventDate) {
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
}