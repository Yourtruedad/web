<h2>Your Account</h2>

<?php

$character = new character();

switch ($action) {
    case 'logout':
        if (isset($action) && 'logout' === $action) {
            session_destroy();
            session_unset();
            unset($_SESSION['AccountID']);
            $common->pageRedirect(WEBSITE_LINK);
        }
    break;
    case 'wcoins':
        if (!empty($accountId)) {
            $db = new db();
            if (true === db::getDbConnectionStatus()) {
                $accountEmail = $db->getAccountEmail($accountId);
                if (!empty($accountEmail)) {
                    $payments = new payment();
                    $paymentWallPaymentUrl = $payments->getPaymentWallWidget($accountId, $accountEmail, common::generateRandomNumber());
					echo '<p class="lead">You can buy WCoins through PaymentWall (multiple payment methods supported).</p>
					Click here to proceed to the payment system.<p class="text-center"><a href="' . $paymentWallPaymentUrl . '" title="Paymentwall" target="_blank"><img src="views/img/paymentwall.jpg" class="img-responsive" alt="PaymentWall"></a></p>';
					
                } else {
                    echo '<div class="bg-danger info-box box-border">Internal error (CODE W01).</div>';
                }
            } else {
                echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
            }
        } else {
            $common->pageRedirect(WEBSITE_LINK);
        }
    break;
    case 'change_password':
        if (!empty($accountId)) {
            echo '<p class="lead">Here you can change your account password</p>';
            if ($_POST) {
                $oldpassword = $common->trimString($common->secureStringVariable($_POST['oldpassword']), MAX_PASSWORD_LENGTH);
                $password = $common->trimString($common->secureStringVariable($_POST['password']), MAX_PASSWORD_LENGTH);
                $repassword = $common->trimString($common->secureStringVariable($_POST['repassword']), MAX_PASSWORD_LENGTH);

                if (!empty($oldpassword) && !empty($password) && !empty($repassword)) {
                    if (true === $common->checkIfPasswordsAreTheSame($password, $repassword)) {
                        if (false === $common->checkIfPasswordsAreTheSame($oldpassword, $password)) {
                            if (true === $common->validatePasswordLength($password)) {
                                if (true === db::getDbConnectionStatus()) {
                                    $db = new db();
                                    if (true === $db->checkIfCorrectCredentials($accountId, $oldpassword)) {
                                        if (true === $db->changeAccountPassword($accountId, $password)) {
                                            echo '<div class="bg-success info-box box-border">Your password has been changed successfully.</div>';
                                        } else {
                                            echo '<div class="bg-danger info-box box-border">Unable to change your password. Please try again later.</div>';
                                        }
                                    } else {
                                        echo '<div class="bg-danger info-box box-border">Your current password is not correct.</div>';
                                    }
                                } else {
                                    echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
                                }
                            } else {
                                echo '<div class="bg-danger info-box box-border">Invalid password length (at least 6 chaacters are required).</div>';
                            }
                        } else {
                            echo '<div class="bg-danger info-box box-border">The new password cannot be the same as the old one.</div>';
                        }
                    } else {
                        echo '<div class="bg-danger info-box box-border">Passwords are not the same.</div>';
                    }
                } else {
                    echo '<div class="bg-danger info-box box-border">Please fill all fields.</div>';
                }
            }

            echo '<form class="form-signin" action="" method="post">
                <label for="inputOldPassword" class="sr-only">Current Password</label>
                <input type="password" name="oldpassword" id="inputOldPassword" class="form-control" placeholder="Current Password" required autofocus><br>
                <label for="inputPassword" class="sr-only">New Password</label>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="New Password" required>
                <label for="inputRePassword" class="sr-only">Confirm Your New Password</label>
                <input type="password" name="repassword" id="inputRePassword" class="form-control" placeholder="Confirm Your New Password" required>
                <small id="passwordHelpBlock" class="help-block text-right">At least 6 characters. Case-sensitive.</small>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Change Password</button>
                </form>';
        } else {
            $common->pageRedirect(WEBSITE_LINK);
        }
    break;
    case 'reset':
        if (!empty($accountId)) {
            $name = $common->trimString($common->secureStringVariable($_GET['name']), MAX_CHARACTER_NAME_LENGTH);
            if (true === db::getDbConnectionStatus()) {
                $db = new db();
                $characterDetails = $db->getCharacterDetails($name);
                if (!empty($characterDetails)) {
                    if (true === $character->checkIfCharacterReadyToReset($characterDetails['cLevel'])) {
                        $moneySource  = $character->checkWhereCharacterHasEnoughMoneyToReset($characterDetails['Name']);
                        if (false !== $moneySource) {
                            $subtractMoneyResult = false;
                            if ('inventory' === $moneySource['source']) {
                                $subtractMoneyResult = $db->subtractMoneyFromInventory($characterDetails['Name'], CHARACTER_RESET_COST);
                            } elseif ('warehouse' === $moneySource['source']) {
                                $subtractMoneyResult = $db->subtractMoneyFromWarehouse($accountId, CHARACTER_RESET_COST);
                            }
                            // If money taken, reset stats
                            if (true === $subtractMoneyResult) {
                                if (true === $db->resetCharacter($characterDetails['Name'], $characterDetails['Class'])) {
                                    echo '<div class="bg-success info-box box-border">Your character has been reset successfully.</div>';
                                } else {
                                    echo '<div class="bg-danger info-box box-border">Unable to reset your character. Please try again later (CODE01).</div>';
                                }
                            } else {
                                echo '<div class="bg-danger info-box box-border">Unable to reset your character. Please try again later (CODE02).</div>';
                            }
                        } else {
                            echo '<div class="bg-danger info-box box-border">You do not have enough money in your inventory or warehouse to reset your character.</div>';
                        }
                    } else {
                        echo '<div class="bg-danger info-box box-border">This character has not reached the required level for the level reset.</div>';
                    }
                } else {
                    echo '<div class="bg-danger info-box box-border">Invalid character.</div>';
                }
            } else {
                echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
            }
        } else {
            $common->pageRedirect(WEBSITE_LINK);
        }
    break;
    default:
        if ($_POST) {
            $username = $common->trimString($common->secureStringVariable($_POST['user']), MAX_USER_NAME_LENGTH);
            $password = $common->trimString($common->secureStringVariable($_POST['password']), MAX_PASSWORD_LENGTH);

            if (!empty($username) && !empty($password)) {
                if (true === $common->validateUserNameLength($username)) {
                    if (true === $common->validatePasswordLength($password)) {
                        if (true === db::getDbConnectionStatus()) {
                            $db = new db();
                            if (true === $db->checkIfCorrectCredentials($username, $password)) {
                                // Add user ID to the session
                                $_SESSION['AccountID'] = $username;
                                //$accountId = $_SESSION['AccountID'];
                                $common->pageRedirect(WEBSITE_LINK . WEBSITE_ACCOUNT_LINK);
                                //$loginResult = true;
                                //echo '<div class="bg-success info-box box-border">Success!</div>';
                            } else {
                                echo '<div class="bg-danger info-box box-border">Invalid account credentials.</div>';
                            }
                        } else {
                            echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
                        }
                    } else {
                        echo '<div class="bg-danger info-box box-border">Invalid password length.</div>';
                    }
                } else {
                    echo '<div class="bg-danger info-box box-border">Invalid user name length.</div>';
                }
            } else {
                echo '<div class="bg-danger info-box box-border">Please fill all fields.</div>';
            }
        }

        if (!empty($accountId)) {
            $content = '';
            if (true === db::getDbConnectionStatus()) {
                $db = new db();
                $characters = $db->getAccountCharacters($accountId);
                if (!empty($characters)) {
                    $warehouseMoney = $db->getAccountWarehouseMoney($accountId);
                    echo '<div class="pull-right"><a href="?module=account&action=change_password">Change Your Account Password</a></div><br><hr><div class="pull-right">Warehouse money: ' , (false !== $warehouseMoney) ? number_format($warehouseMoney[character::$characterMoneySystemName]) : '0' , '</div>';
                    foreach ($characters as $character) {
                        $content .= '<div class="width100percent clear-both">
                            <h3>' . $character['Name'] . ' <small>' . character::getCharacterClassName($character[character::$characterClassSystemName]) . '</small></h3>
                            <div class="width20percent pull-left">
                                <img src="views/img/' . character::getCharacterClassShortThumbnailName($character[character::$characterClassSystemName]) . '.jpg" alt="Class image" class="img-thumbnail">
                            </div>
                            <div class="width70percent left-margin pull-left table-responsive">
                                <table class="table table-hover">
                                <tr><td class="width20percent"><strong>Level</strong></td><td class="width60percent">' . $character[character::$characterLevelSystemName] . ((character::$characterMaxLevel == $character[character::$characterLevelSystemName]) ? '&nbsp;&nbsp;&nbsp;<a href="?module=account&action=reset&name=' . $character[character::$characterNameSystemName] . '" title="Reset" onclick="return confirm(\'Are you sure?\')">Click here to reset this character</a><div class="bg-primary info-box box-border top-margin">You need to have at least ' . number_format(CHARACTER_RESET_COST) . ' zen in either your inventory or warehouse to be able to reset your character.</div>' : '') . '</td></tr>
                                    <tr><td><strong>Master Level</strong></td><td>' . $character[character::$characterMasterLevelSystemName] . '</td></tr>
                                    <tr><td><strong>Reset</strong></td><td>' . $character[character::$characterResetSystemName] . '</td></tr>
                                    <tr><td><strong>Strength</strong></td><td>' . $character[character::$characterStrengthSystemName] . '</td></tr>
                                    <tr><td><strong>Agility</strong></td><td>' . $character[character::$characterDexteritySystemName] . '</td></tr>
                                    <tr><td><strong>Vitality</strong></td><td>' . $character[character::$characterVitalitySystemName] . '</td></tr>
                                    <tr><td><strong>Energy</strong></td><td>' . $character[character::$characterEnergySystemName] . '</td></tr>';
                        if (0 < $character[character::$characterLeadershipSystemName]) {
                            $content .= '<tr><td><strong>Command</strong></td><td>' . $character[character::$characterLeadershipSystemName] . '</td></tr>';
                        }
                        $content .= '<tr><td><strong>Level Up Points</strong></td><td>' . $character[character::$characterLevelUpPointSystemName] . '' . (($character[character::$characterLevelUpPointSystemName] > 0) ? '<div class="bg-primary info-box box-border top-margin">You can use in-game commands (<em>/addstr</em>, <em>/addagi</em>, <em>/addvit</em>, <em>/addene</em>, <em>/addcmd</em>) to add available Level Up Points to the attributes.</div>' : '') . '</td></tr>
                                    <tr><td><strong>Inventory Money</strong></td><td>' . number_format($character[character::$characterMoneySystemName]) . '</td></tr>
                                </table>
                            </div>
                        </div>';
                    }
                    echo $content;
                } else {
                    echo '<div class="bg-primary info-box box-border">You do not have any characters.</div>';
                }
            } else {
                echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
            }
        } else {

        ?>
        
        <form class="form-signin" action="" method="post">
            <label for="inputUsername" class="sr-only">User Name</label>
            <input type="username" name="user" id="inputUsername" class="form-control" placeholder="User Name" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>

        <?php 

        }
    break;
}

?>