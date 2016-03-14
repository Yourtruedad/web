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

    public static $characterMasterSkillList = [300,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,348,349,350,351,352,353,354,355,356,357,358,359,360,361,362,363,364,366,367,368,369,370,371,372,373,374,375,377,378,379,380,381,382,383,384,385,386,387,388,389,390,391,392,393,394,395,397,398,399,400,401,402,403,404,405,406,407,409,410,411,412,413,414,415,416,417,418,419,420,421,422,423,424,425,426,427,428,429,430,431,432,433,434,435,436,437,438,439,440,441,442,443,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,465,466,467,468,469,470,471,472,473,475,476,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,504,505,506,507,508,509,510,511,512,513,514,515,516,517,518,519,520,521,522,523,524,526,527,528,529,530,531,532,533,534,535,536,538,539,548,549,550,551,552,554,555,556,557,558,559,560,561,562,563,564,565,566,567,568,569,571,572,573,574,578,579,580,581,582,583,584,585,586,587,588,589,590,591,592,593,594,595,596,597,598,599,600,601,602,603,604,605,606,607,608,609,610,611,612,613,614,615,616,617];

    public $hideRankingCharacterDetails = false;
    
    public static $defaultCharacterCountryCode = 'PL';

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

    public static function generateAccountPersonalNumber() {
        $accountNumber = common::generateRandomNumber(PERSONAL_CODE_LENGTH);
        $db = new db();
        $loopControl = false;
        $loopRepeat = 0;
        while ($loopControl === false && 50 > $loopRepeat) {
            if (!empty($accountNumber) && true === $db->checkIfPersonalNumberIsAvailable($accountNumber)) {
                $loopControl = true;
            } else {
                $accountNumber = common::generateRandomNumber(PERSONAL_CODE_LENGTH);
            }
            $loopRepeat++;
        }
        return $accountNumber;
    }
    
    public static function returnDefaultCharacterCountryCode($code) {
        if (empty($code)) {
            return self::$defaultCharacterCountryCode;
        }
        return $code;
    }
} 