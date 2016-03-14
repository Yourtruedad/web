<?php

class common 
{
    const SERVER_IP = '188.209.52.29';

    public $gameserverInformation = [
        'passive' => 56900,
        'active' => 56901
    ];

    public function secureStringVariable($string) {
        if (!empty($string)) {
            return filter_var($string, FILTER_SANITIZE_STRING);
        }
    }

    public function secureEmailVariable($string) {
        if (!empty($string)) {
            return filter_var($string, FILTER_SANITIZE_EMAIL);
        }
    }

    public function validateEmail($string) {
        if (false !== filter_var($string, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function validateUserName($string) {
        if (preg_match('#^[a-zA-Z0-9\_]{5,10}$#i', $string)) {
            return true;
        }
        return false;
    }

    public function validateCountryName($string) {
        if (true === ctype_upper($string) && 2 === strlen($string)) {
            return true;
        }
        return false;
    }

    public function validateUserNameLength($string) {
        if (strlen($string) >= MIN_USER_NAME_LENGTH) {
            // Valid if long enough
            return true;
        }
        return false;
    }

    public function validatePasswordLength($string) {
        if (strlen($string) >= MIN_PASSWORD_LENGTH) {
            // Valid if long enough
            return true;
        }
        return false;
    }

    public function trimString($string, $maxLength) {
        if (!empty($string)) {
            return substr(trim($string), 0, $maxLength);
        }
    }

    public function checkIfPasswordsAreTheSame($password, $password2) {
        if ($password === $password2) {
            return true;
        }
        return false;
    }

    public function getFormPreviouslySentValue($value) {
        if (!empty($value)) {
            return $value;
        }
    }

    public function pageRedirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    public function recursiveArraySearch($needle, $haystack) {
        foreach($haystack as $key => $value) {
            $current_key = $key;
            if($needle === $value || (is_array($value) && $this->recursiveArraySearch($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    public static function changeNullToZero($value) {
        if (NULL === $value) {
            return 0;
        }
        return $value;
    }

    public function checkifServerIsOnline() {
        // Tu sprawdzamy czy chociaz jeden serwer jest online
        /*$connection = fsockopen(self::SERVER_IP, self::GAMESERVER_PORT);//, $errno, $errstr, 5);
        if (false !== $connection) {
            return true;
        }
        return false;*/
        return true;
    }

    public static function generateRandomNumber($length = 13) {
        $number = '';
        for($x = 0; $x < $length; $x++) {
            $number .= chr(rand(48, 57));
        }
        return $number;
    }

    public function checkIfGameServerIsOnline($name) {
        $gameserverStatus = 'offline';
        if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfServerInformationIsCurrent($name . '_' . $cacheDb->gameserverStatusInformationName)) {
                $serverInformation = $cacheDb->getCurrentServerInformation($name . '_' . $cacheDb->gameserverStatusInformationName);
                if (!empty($serverInformation)) {
                    $gameserverStatus = $serverInformation['value'];
                }
            } else {
                $connection = fsockopen(self::SERVER_IP, $this->gameserverInformation[$name], $errno, $errstr, 3);
                if (false !== $connection) {
                    $gameserverStatus = 'online';
                }
                $cacheDb->saveCurrentServerInformation($name . '_' . $cacheDb->gameserverStatusInformationName, cacheDb::CACHE_GAMESERVER_STATUS_TIME, $gameserverStatus);
            }
        } else {
            $connection = fsockopen(self::SERVER_IP, $this->gameserverInformation[$name], $errno, $errstr, 3);
            if (false !== $connection) {
                $gameserverStatus = 'online';
            }
            $gameserverStatus = 'offline';
        }
        return $gameserverStatus;
    }

    public function getServerOnlineCount($type) {
        $onlineCount = 0;
        if ('global' === $type) {
            if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
                $cacheDb = new cacheDb();
                if (true === $cacheDb->checkIfServerInformationIsCurrent($cacheDb->onlineCountServerInformationName)) {
                    $serverInformation = $cacheDb->getCurrentServerInformation($cacheDb->onlineCountServerInformationName);
                    if (!empty($serverInformation)) {
                        $onlineCount = $serverInformation['value'];
                    } else {
                        $onlineCount = db::getOnlineAccountsCount();
                    }
                } else {
                    $onlineCount = db::getOnlineAccountsCount();
                    $cacheDb->saveCurrentServerInformation($cacheDb->onlineCountServerInformationName, cacheDb::CACHE_PLAYER_ONLINE_COUNT_TIME, $onlineCount);
                }
            } else {
                $onlineCount = db::getOnlineAccountsCount();
            }
        } else {
            if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
                $cacheDb = new cacheDb();
                if (true === $cacheDb->checkIfServerInformationIsCurrent($type . '_' . $cacheDb->onlineCountServerInformationName)) {
                    $serverInformation = $cacheDb->getCurrentServerInformation($type . '_' . $cacheDb->onlineCountServerInformationName);
                    if (!empty($serverInformation)) {
                        $onlineCount = $serverInformation['value'];
                    } else {
                        $onlineCount = db::getOnlineAccountsCountPerServer($type);
                    }
                } else {
                    $onlineCount = db::getOnlineAccountsCountPerServer($type);
                    $cacheDb->saveCurrentServerInformation($type . '_' . $cacheDb->onlineCountServerInformationName, cacheDb::CACHE_PLAYER_ONLINE_COUNT_TIME, $onlineCount);
                }
            } else {
                $onlineCount = db::getOnlineAccountsCountPerServer($type);
            }
        }

        return $onlineCount;
    }

    public function validatePersonalCode($string) {
        if (preg_match('#^[0-9]{' . PERSONAL_CODE_LENGTH . '}$#i', $string)) {
            return true;
        }
        return false;
    }
    
    public function writeToFile($filename, $content) {
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
    }
    
    public static function generateRandomString($length = 32) {
        $string = '';
        for($x = 0; $x < $length; $x++) {
            $type = rand(0,1);
            if (0 === $type) {
                $string .= chr(rand(97, 122));
            } elseif (1 === $type) {
                $string .= chr(rand(48, 57));
            }
        }
        return $string;
    }
    
    public static function addTimeToDate($date, $format, $time, $unit) {
        $now = new DateTime($date); //current date/time
        if ('H' === $unit || 'M' === $unit) {
            $now->add(new DateInterval("PT{$time}{$unit}"));
        } elseif ('D' === $unit) {
            $now->add(new DateInterval("P{$time}{$unit}"));
        }
        return $now->format($format);
    }
    
    public static function translateDateWordToDateTimeFormat($word) {
        if ('hour' === $word) {
            return 'H';
        } elseif ('minute' === $word) {
            return 'M';
        }
    }
    
    public static function subTimeFromDate($date, $format, $time, $unit) {
        $now = new DateTime("now", new DateTimeZone(CONFIG_SYSTEM_TIMEZONE)); //current date/time
        $now->sub(new DateInterval("PT{$time}{$unit}"));
        return $now->format($format);
    }
    
    public static function currentDate($format = 'Y-m-d H:i:s') {
        return common::subTimeFromDate(date($format), $format, CONFIG_TIMEZONE_MANUAL_ADJUST, 'H');
    }
    
    public static function calculateTimeDifference($date, $date2) {
        $dateDiff = new DateTime($date);
        $dateDiff2 = new DateTime($date2);
        $interval = date_diff($dateDiff, $dateDiff2);
        
        $timeDifference = strtotime($date2) - strtotime($date);
        if ($timeDifference > 86400) {
            return $interval->format('%d day %h hour(s) and %i minute(s)');
        } elseif ($timeDifference > 3600) {
            return $interval->format('%h hour(s) and %i minute(s)');
        } else {
            return $interval->format('%i minute(s)');
        }
    }
}