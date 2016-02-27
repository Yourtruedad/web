<?php

class character {

    public static $characterClassDetails = [
        0 => ['name' => 'Dark Wizard', 'short_name_thumbnail' => 'dw'],
        1 => ['name' => 'Soul Master', 'short_name_thumbnail' => 'dw'],
        2 => ['name' => 'Grand Master', 'short_name_thumbnail' => 'dw'],
        3 => ['name' => 'Grand Master', 'short_name_thumbnail' => 'dw'],
        16 => ['name' => 'Dark Knight', 'short_name_thumbnail' => 'dk'],
        17 => ['name' => 'Blade Knight', 'short_name_thumbnail' => 'dk'],
        18 => ['name' => 'Blade Master', 'short_name_thumbnail' => 'dk'],
        19 => ['name' => 'Blade Master', 'short_name_thumbnail' => 'dk'],
        32 => ['name' => 'Fairy Elf', 'short_name_thumbnail' => 'fe'],
        33 => ['name' => 'Muse Elf', 'short_name_thumbnail' => 'fe'],
        34 => ['name' => 'High Elf', 'short_name_thumbnail' => 'fe'],
        35 => ['name' => 'High Elf', 'short_name_thumbnail' => 'fe'],
        48 => ['name' => 'Magic Gladiator', 'short_name_thumbnail' => 'mg'],
        49 => ['name' => 'Duel Master', 'short_name_thumbnail' => 'mg'],
        50 => ['name' => 'Duel Master', 'short_name_thumbnail' => 'mg'],
        64 => ['name' => 'Dark Lord', 'short_name_thumbnail' => 'dl'],
        65 => ['name' => 'Lord Emperor', 'short_name_thumbnail' => 'dl'],
        66 => ['name' => 'Lord Emperor', 'short_name_thumbnail' => 'dl'],
        80 => ['name' => 'Summoner', 'short_name_thumbnail' => 'su'],
        81 => ['name' => 'Bloody Summoner', 'short_name_thumbnail' => 'su'],
        82 => ['name' => 'Dimention Master', 'short_name_thumbnail' => 'su'],
        83 => ['name' => 'Dimention Master', 'short_name_thumbnail' => 'su'],
        96 => ['name' => 'Rage Fighter', 'short_name_thumbnail' => 'rf'],
        97 => ['name' => 'Fist Master', 'short_name_thumbnail' => 'rf'],
        98 => ['name' => 'Fist Master', 'short_name_thumbnail' => 'rf']
    ];

    public static $characterLevelSystemName = 'cLevel';
    public static $characterResetSystemName = 'Reset';
    public static $characterMasterLevelSystemName = 'mLevel';
    public static $characterClassSystemName = 'Class';
    public static $characterNameSystemName = 'Name';
    public static $characterCountrySystemName = 'Country';
    public static $characterStatusOnlineSystemName = 'StatusOnline';
    public static $characterStrengthSystemName = 'Strength';
    public static $characterDexteritySystemName = 'Dexterity';
    public static $characterVitalitySystemName = 'Vitality';
    public static $characterEnergySystemName = 'Energy';
    public static $characterLeadershipSystemName = 'Leadership';
    public static $characterLevelUpPointSystemName = 'LevelUpPoint';
    public static $characterMoneySystemName = 'Money';

    public static $characterMaxLevel = 400;

    public static $characterBaseStats = [
        'Strength' => 25,
        'Dexterity' => 25,
        'Energy' => 25,
        'Vitality' => 25,
        'Leadership' => 25,
        'Life' => 60,
        'MaxLife' => 60,
        'Mana' => 60,
        'MaxMana' => 60
    ];

    public $hideRankingCharacterDetails = false;

    // Get sorted $characterClassDetails array
    public static function getCharacterClassDetails() {
        if (true === ksort(self::$characterClassDetails)) {
            return self::$characterClassDetails;
        }
        return false;
    }

    public static function getCharacterClassName($id) {
        if (in_array($id, array_keys(self::$characterClassDetails))) {
            return self::$characterClassDetails[$id]['name'];
        }
        return 'Unknown';
    }

    // Hide level or reset
    public function hideRankingCharacterDetail($string) {
        if (true === $this->hideRankingCharacterDetails) {
            if (is_numeric($string)) {
                if (0 == $string) {
                    return $string;
                } elseif (10 > $string) {
                    return 'x';
                } elseif (100 > $string) {
                    return $string[0] . 'X';
                } elseif (1000 > $string) {
                    return $string[0] . 'XX';
                }
            }
            return 'X';
        }
        return $string;
    }

    public static function getCharacterClassShortThumbnailName($id) {
    	if (in_array($id, array_keys(self::$characterClassDetails))) {
            return self::$characterClassDetails[$id]['short_name_thumbnail'];
        }
    }

    public function checkIfCharacterReadyToReset($level) {
        if (is_numeric($level)) {
            if ($level == self::$characterMaxLevel) {
                return true;
            }
        }
        return false;
    }

    public function checkIfCharacterIsDarkLord($classId) {
        if (true === in_array($classId, array_keys(self::$characterClassDetails))) {
            if ('dl' === self::$characterClassDetails[$classId]['short_name_thumbnail']) {
                return true;
            }
        }
        return false;
    }
    
    // Check if character has enough money fo
    public function checkWhereCharacterHasEnoughMoneyToReset($name) {
        if (!empty($name)) {
            $db = new db();
            $inventoryMoney = $db->getCharacterMoney($name);
            $characterAccountId = $db->getCharacterAccountId($name);
            if (!empty($characterAccountId)) {
                $warehouseMoney = $db->getAccountWarehouseMoney($characterAccountId['AccountID']);
            }
            if (!empty($inventoryMoney)) {
                if ($inventoryMoney['Money'] >= CHARACTER_RESET_COST) {
                    return ['source' => 'inventory'];
                }
            }
            if (!empty($warehouseMoney)) {
                if ($warehouseMoney['Money'] >= CHARACTER_RESET_COST) {
                    return ['source' => 'warehouse'];
                }
            }
        }
        return false;
    }

    public static function getCharacterDefaultClass($id) {
        if (in_array($id, array_keys(self::$characterClassDetails))) {
            $shortName = self::$characterClassDetails[$id]['short_name_thumbnail'];
            $characterClasses = self::getCharacterClassDetails();
            if (!empty($shortName) && false !== $characterClasses) {
                $common = new common();
                return $common->recursiveArraySearch($shortName, $characterClasses);
            }
        }
        return false;
    }

    public static function getCharacterDefaultStats($class) {
        $baseStats = self::$characterBaseStats;
        $character = new character();
        if (true === $character->checkIfCharacterIsDarkLord($class)) {
            return $baseStats;
        } else {
            $baseStats['Leadership'] = 0;
            return $baseStats;
        }
    }


} 