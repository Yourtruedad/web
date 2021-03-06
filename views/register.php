<h1>Sign Up</h1>
<p class="lead">Provide all infromation in the form to create your account</p>
<?php

if ($_POST) {

    $username = $common->trimString($common->secureStringVariable($_POST['user']), MAX_USER_NAME_LENGTH);
    $email = $common->trimString($common->secureEmailVariable($_POST['email']), MAX_EMAIL_LENGTH);
    $password = $common->trimString($common->secureStringVariable($_POST['password']), MAX_PASSWORD_LENGTH);
    $repassword = $common->trimString($common->secureStringVariable($_POST['repassword']), MAX_PASSWORD_LENGTH);
    $country = $common->secureStringVariable($_POST['country']);
    $terms = $common->secureStringVariable($_POST['terms']);
    $captcha = $_POST['g-recaptcha-response'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($repassword) && !empty($country) && !empty($terms) && !empty($captcha)) {
        if ($_POST['user'] <= MAX_USER_NAME_LENGTH) {
            $recaptcha = new \ReCaptcha\ReCaptcha(CONFIG_RECAPTCHA_SECRET_KEY);
            $recaptchaResponse = $recaptcha->verify($captcha, $_SERVER['REMOTE_ADDR']);
            if (true === $recaptchaResponse->isSuccess()) {
                if (true === $common->checkIfPasswordsAreTheSame($password, $repassword)) {
                    if (true === $common->validateEmail($email)) {
                        if (true === $common->validateUserNameLength($username)) {
                            if (true === $common->validatePasswordLength($password)) {
                                if (true === $common->validateUserName($username)) {
                                    if (true === $common->validateCountryName($country)) {
                                        if (true === db::getDbConnectionStatus()) {
                                            $db = new db();
                                            if (true === $db->checkIfUserNameIsAvailable($username)) {
                                                if (true === $db->checkIfEmailIsAvailable($email)) {
                                                    if (true === $db->createUserAccount($username, $email, $password, $country)) {
                                                        echo '<div class="bg-success info-box box-border">Account created successfully (user name: <b>' . $username . '</b>).</div>';
                                                        if (true === SEND_EMAIL_CONFIRMATION_MESSAGE) {
                                                            $accountNumber = $db->getAccountPersonalNumber($username);
                                                            if (!empty($accountNumber)) {
                                                                $mailer = new mailer();
                                                                if (true === $mailer->sendMail($email, 'EverWinter MU Account Activation', 'Hello,<br><br>In order to activate your account (user name: ' . $username . '), please use the link below:<br><br><a href="http://everwintermu.com/?module=email_confirmation&code=' . $accountNumber . '">http://everwintermu.com/?module=email_confirmation&code=' . $accountNumber . '</a>')) {
                                                                    if (true === $db->changeAccountBlocCode($username, '0')) {
                                                                        echo '<div class="bg-info info-box box-border top-margin">It is necessary to confirm your email address before you can log in. A message has just been sent to you (email: <b>' . $email . '</b>). Please follow the instruction inside. It might that the message gets marked as spam so please remember to check the spam/junk folder as well.</div>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        unset($username);
                                                        unset($email);
                                                    } else {
                                                        echo '<div class="bg-danger info-box box-border">Unable to create the account. Please try again later.</div>';
                                                    }
                                                } else {
                                                    echo '<div class="bg-danger info-box box-border">Email address is not available.</div>';
                                                }
                                            } else {
                                                echo '<div class="bg-danger info-box box-border">User name is not available.</div>';
                                            }
                                        } else {
                                            echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
                                        }
                                    } else {
                                        echo '<div class="bg-danger info-box box-border">Invalid country name format.</div>';
                                    }
                                } else {
                                    echo '<div class="bg-danger info-box box-border">Invalid username format.</div>';
                                }
                            } else {
                                echo '<div class="bg-danger info-box box-border">Invalid password length.</div>';
                            }
                        } else {
                            echo '<div class="bg-danger info-box box-border">Invalid user name length (between 5 and 10 characters).</div>';
                        }
                    } else {
                        echo '<div class="bg-danger info-box box-border">Provide a valid email address.</div>';
                    }
                } else {
                    echo '<div class="bg-danger info-box box-border">Passwords are not the same.</div>';
                }
            } else {
                echo '<div class="bg-danger info-box box-border">Captcha validation failed.</div>';
            }
        } else {
            echo '<div class="bg-danger info-box box-border">The maximum length of the username is 10 characters.</div>';
        }
    } else {
        if (true === $common->checkIfAnyFieldsWereRemoved('post')) {
            echo '<div class="bg-danger info-box box-border">Some information you provided were not accepted due to security reasons. Please follow the suggestions under each field (for login and password it is best to use random strings of letters, digits and special characters). Please try again.</div>';
        } else {
            echo '<div class="bg-danger info-box box-border">Please fill all fields.</div>';
        }
    }
}

?>

<form class="form-signin" action="" method="post">
    <label for="inputUser" class="sr-only">User Name</label>
    <input type="user" name="user" id="inputUser" class="form-control" placeholder="User Name" value="<?php echo isset($username) ? $common->getFormPreviouslySentValue($username) : ''; ?>" aria-describedby="userHelpBlock" maxlength="<?=MAX_USER_NAME_LENGTH?>" required autofocus>
    <small id="userHelpBlock" class="help-block text-right">Between 5-10 characters (letters, digits and _ allowed). Case-sensitive.</small>
    <label for="inputEmail" class="sr-only">Email Address</label>
    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email Address" value="<?php echo isset($email) ? $common->getFormPreviouslySentValue($email) : ''; ?>" maxlength="<?=MAX_EMAIL_LENGTH?>" required><br>

    
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" aria-describedby="passwordHelpBlock" maxlength="<?=MAX_PASSWORD_LENGTH?>" required>
    
    <label for="inputRePassword" class="sr-only">Confirm Your Password</label>
    <input type="password" name="repassword" id="inputRePassword" class="form-control" placeholder="Confirm Your Password" maxlength="<?=MAX_PASSWORD_LENGTH?>" required>
    <small id="passwordHelpBlock" class="help-block text-right">At least 6 characters (letters, digits and special chars ! @ # $ & * allowed). Case-sensitive.</small>
    <div id="countrySelect" class="bfh-selectbox bfh-countries align-left" data-country="PL" data-flags="true">
        <input type="hidden" value="">
        <!--<a class="bfh-selectbox-toggle" role="button" data-toggle="bfh-selectbox" href="#">
            <span class="bfh-selectbox-option input-medium" data-option=""></span>
            <b class="caret"></b>
        </a>-->
        <div class="bfh-selectbox-options">
            <input type="text" class="bfh-selectbox-filter">
            <div role="listbox">
                <ul role="option">
                </ul>
            </div>
        </div>
    </div>
    <div class="checkbox text-center">
        <label>
            <input type="hidden" name="terms" value="agree" required><!-- I agree to the <a href="?module=tos" target="_blank">terms of service</a>-->
        </label>
    </div>
    <div id="captcha"><div class="g-recaptcha" data-sitekey="<?php echo CONFIG_RECAPTCHA_SITE_KEY; ?>"></div></div>
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=en"></script>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
</form>