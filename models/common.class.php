<?php

class common 
{
    const SERVER_IP = '188.209.52.29';

    const GAMESERVER_PORT = 56900;

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

    public function checkifGameServerIsOnline() {
        /*$connection = fsockopen(self::SERVER_IP, self::GAMESERVER_PORT);//, $errno, $errstr, 5);
        if (false !== $connection) {
            return true;
        }
        return false;*/
        return true;
    }
}

?>