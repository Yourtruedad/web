<?php

class db
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
            $pdo = new PDO('sqlsrv:server=' . CONFIG_DATABASE_HOST . ';Database=' . CONFIG_DATABASE_NAME . ';LoginTimeout=5', CONFIG_DATABASE_USER, CONFIG_DATABASE_PASSWORD);
        } catch (PDOException $exception) {
            //echo $exception;
        }
        return $pdo;
    }

    public static function getDbConnectionStatus() {
        $db = new db();
        return $db->dbStatus;
    }

    public function checkIfUserNameIsAvailable($username) {
        $sql = '
            SELECT
                memb___id
            FROM
                MEMB_INFO
            WHERE 
                memb___id = :username
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($results)) {
            // Name available
            return true;
        }
        // Name not available
        return false;
    }

    public function checkIfEmailIsAvailable($email) {
        $sql = '
            SELECT
                mail_addr
            FROM
                MEMB_INFO
            WHERE 
                mail_addr = :email
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($results)) {
            // Email available
            return true;
        }
        // Email not available
        return false;
    }

    // Create user account
    public function createUserAccount($username, $email, $password, $country) {
        $accountNumber = character::generateAccountPersonalNumber();

        $sql = "
            INSERT INTO 
                MEMB_INFO
                (
                    memb___id,
                    memb__pwd,
                    memb_name,
                    sno__numb,
                    addr_info,
                    mail_addr,
                    bloc_code,
                    ctl1_code,
                    JoinDate
                ) 
            VALUES 
                (
                    :username,
                    [dbo].[fn_md5](:password,:username_password),
                    :name,
                    :number,
                    :country,
                    :email,
                    '0',
                    '0',
                    '" . date('Y-m-d h:i:s') . "'
                )
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':username_password', $username, PDO::PARAM_STR);
        $query->bindParam(':name', $username, PDO::PARAM_STR);
        $query->bindParam(':number', $accountNumber, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;

    }

    // Get characters for main ranking
    public static function getCharacterRanking($limit = 100) {
        if (false === db::getDbConnectionStatus()) {
            return [];
        }

        $sql = "
            SELECT TOP " . $limit . "
                Name,
                cLevel,
                Class,
                Strength,
                Dexterity,
                Vitality,
                Energy,
                Money,
                mLevel,
                (
                    SELECT 
                        addr_info 
                    FROM 
                        MEMB_INFO
                    WHERE
                        AccountID = memb___id
                ) as Country,
                RESETS as Reset,
                (
                    SELECT DISTINCT 
                        1
                    FROM 
                        MEMB_STAT 
                    JOIN 
                        AccountCharacter 
                    ON 
                        MEMB_STAT.memb___id = AccountCharacter.ID 
                        collate Latin1_general_CI_AS 
                    WHERE 
                        MEMB_STAT.connectstat = 1 AND GameIDC = Name
                ) as StatusOnline,
                (
                    SELECT TOP 1
                        G_Name
                    FROM 
                        GuildMember
                    WHERE 
                        Name = Name
                ) as GuildName
            FROM
                Character
            WHERE 
                CtlCode = 0
            ORDER BY
                Reset DESC, cLevel DESC, mLevel DESC, Name ASC
        ";
        $db = new db();
        $query = $db->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function checkIfCorrectCredentials($username, $password) {
        $sql = '
            SELECT
                1
            FROM
                MEMB_INFO
            WHERE 
                memb___id = :username
            AND
                memb__pwd = [dbo].[fn_md5](:password,:username_password)
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':username_password', $username, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (false !== $results) {
            // Correct credentials
            return true;
        }
        // Invalid credentials
        return false;
    }

    // Get account characters
    public function getAccountCharacters($account) {
        $sql = '
            SELECT TOP 5
                Name,
                cLevel,
                LevelUpPoint,
                Class,
                Strength,
                Dexterity,
                Vitality,
                Energy,
                Leadership,
                Money,
                mLevel,
                RESETS as Reset
            FROM
                Character
            WHERE
                AccountID = :account
            ORDER BY
                Reset DESC, cLevel DESC, mLevel DESC, Name ASC
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':account', $account, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function getCharacterDetails($name) {
        $sql = '
            SELECT
                Name,
                cLevel,
                LevelUpPoint,
                Class,
                Strength,
                Dexterity,
                Vitality,
                Energy,
                Leadership,
                Money,
                mLevel,
                RESETS as Reset,
                MagicList
            FROM
                Character
            WHERE
                Name = :name
            ORDER BY
                Reset DESC, cLevel DESC, mLevel DESC, Name ASC
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function resetCharacter($name, $classId) {
        $defaultStats = $this->getClassDefaultStats($classId);
        if (empty($defaultStats)) {
            $defaultStats = character::getCharacterDefaultStats($classId);
            if (empty($defaultStats)) {
                return false;
            }
        }
        $sql = "
            UPDATE 
                Character
            SET
                cLevel = '1',
                Experience = '0',
                Strength = :strength,
                Dexterity = :dexterity,
                Vitality = :vitality,
                Energy = :energy,
                Leadership = :leadership,
                Life = :life,
                MaxLife = :maxlife,
                Mana = :mana,
                MaxMana = :maxmana,
                MapNumber = '0',
                MapPosX = '125',
                MapPosY = '125',
                PkLevel = 3,
                RESETS = (RESETS + 1),
                LevelUpPoint = (" . CHARACTER_RESET_LEVEL_UP_POINTS . " + (" . CHARACTER_RESET_LEVEL_UP_POINTS . " * RESETS)),
                mLevel = '0',
                mlPoint = '0',
                mlExperience = '0',
                mlNextExp = '0'
            WHERE
                Name = :name
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':strength', $defaultStats['Strength'], PDO::PARAM_INT);
        $query->bindParam(':dexterity', $defaultStats['Dexterity'], PDO::PARAM_INT);
        $query->bindParam(':vitality', $defaultStats['Vitality'], PDO::PARAM_INT);
        $query->bindParam(':energy', $defaultStats['Energy'], PDO::PARAM_INT);
        $query->bindParam(':leadership', $defaultStats['Leadership'], PDO::PARAM_INT);
        $query->bindParam(':life', $defaultStats['Life'], PDO::PARAM_INT);
        $query->bindParam(':maxlife', $defaultStats['MaxLife'], PDO::PARAM_INT);
        $query->bindParam(':mana', $defaultStats['Mana'], PDO::PARAM_INT);
        $query->bindParam(':maxmana', $defaultStats['MaxMana'], PDO::PARAM_INT);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }

    public function getAccountWarehouseMoney($account) {
        $sql = '
            SELECT
                Money
            FROM
                warehouse
            WHERE
                AccountID = :account
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':account', $account, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function getCharacterMoney($name) {
        $sql = '
            SELECT
                Money
            FROM
                Character
            WHERE
                Name = :name
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function getCharacterAccountId($name) {
        $sql = '
            SELECT
                AccountID
            FROM
                Character
            WHERE
                Name = :name
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }
        return [];
    }

    public function subtractMoneyFromWarehouse($account, $amount) {
        $sql = "
            UPDATE 
                warehouse
            SET
                Money = (Money - :amount)
            WHERE
                AccountID = :account
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':amount', $amount, PDO::PARAM_INT);
        $query->bindParam(':account', $account, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }

    public function subtractMoneyFromInventory($name, $amount) {
        $sql = "
            UPDATE 
                Character
            SET
                Money = (Money - :amount)
            WHERE
                Name = :name
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':amount', $amount, PDO::PARAM_INT);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }

    public function getClassDefaultStats($class) {
        $defaultClassId = character::getCharacterDefaultClass($class);
        if (false !== $defaultClassId) {
            $sql = '
                SELECT
                    Class,
                    Strength,
                    Dexterity,
                    Vitality,
                    Energy,
                    Leadership,
                    Life,
                    MaxLife,
                    Mana,
                    MaxMana
                FROM
                    DefaultClassType
                WHERE
                    Class = :class
            ';
            $query = $this->pdo->prepare($sql);
            $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $query->bindParam(':class', $defaultClassId, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($results)) {
                return $results;
            }
        }
        return [];
    }

    public static function getOnlineAccountsCount() {
        if (true === db::getDbConnectionStatus()) {
            $sql = '
                SELECT 
                    COUNT(memb___id) as OnlineCount
                FROM 
                    MEMB_STAT
                WHERE 
                    ConnectStat = 1
            ';
            $db = new db();
            $query = $db->pdo->prepare($sql);
            $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                return $results['OnlineCount'];
            }
        }
        return 0;
    }

    public static function getOnlineAccountsCountPerServer($serverName) {
        if (true === db::getDbConnectionStatus()) {
            $sql = '
                SELECT 
                    COUNT(memb___id) as OnlineCount
                FROM 
                    MEMB_STAT
                WHERE 
                    ConnectStat = 1
                AND 
                    ServerName = :serverName
            ';
            $db = new db();
            $query = $db->pdo->prepare($sql);
            $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $query->bindParam(':serverName', $serverName, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                return $results['OnlineCount'];
            }
        }
        return 0;
    }

    public function checkIfPersonalNumberIsAvailable($number) {
        $sql = '
            SELECT 
                sno__numb
            FROM 
                MEMB_INFO
            WHERE 
                sno__numb = :number
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':number', $number, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($results)) {
            // Number available
            return true;
        }
        // Number not available
        return false;
    }

    public function getAccountPersonalNumber($username) {
        $sql = '
            SELECT TOP 1
                sno__numb
            FROM 
                MEMB_INFO
            WHERE 
                memb___id = :username
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            return $result['sno__numb'];
        }
        return '';
    }

    public function changeAccountBlocCode($username, $code) {
        $sql = "
            UPDATE 
                MEMB_INFO
            SET
                bloc_code = :code
            WHERE
                memb___id = :username
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':code', $code, PDO::PARAM_INT);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }

    public function getAccountByPersonalNumber($code) {
        $sql = '
            SELECT TOP 1
                memb___id
            FROM 
                MEMB_INFO
            WHERE 
                sno__numb = :code
        ';
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':code', $code, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            return $result['memb___id'];
        }
        return '';
    }

    public function changeEmailCheckCode($username, $code) {
        $sql = "
            UPDATE 
                MEMB_INFO
            SET
                mail_chek = :code
            WHERE
                memb___id = :username
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':code', $code, PDO::PARAM_INT);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }

    public function changeAccountPassword($username, $password) {
        $sql = "
            UPDATE 
                MEMB_INFO
            SET
                memb__pwd = [dbo].[fn_md5](:password,:username_password)
            WHERE
                memb___id = :username
        ";
        $query = $this->pdo->prepare($sql);
        $query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':username_password', $username, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $result = $query->execute();
        return $result;
    }
}