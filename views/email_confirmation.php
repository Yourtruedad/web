<h1>Email Activation</h1>
<?php

$code = $common->trimString($common->secureStringVariable($_GET['code']), PERSONAL_CODE_LENGTH);

if (!empty($code)) {
	if (true === $common->validatePersonalCode($code)) {
		$db = new db();
		$username = $db->getAccountByPersonalNumber($code);
		if (!empty($username)) {
			// set bloc_code = 0
			if (true === $db->changeAccountBlocCode($username, 0)) {
				if (true === $db->changeEmailCheckCode($username, 1)) {
					echo '<div class="bg-success info-box box-border">Your account has been activated successfully.</div>';
				} else {
					echo '<div class="bg-danger info-box box-border">Something went wrong (code EC001). Please contact us on our <a href="' . FORUM_LINK . '" target="_blank" title="Forum">message board</a>.</div>';
				}
			} else {
				echo '<div class="bg-danger info-box box-border">Invalid activation code. Please contact us on our <a href="' . FORUM_LINK . '" target="_blank" title="Forum">message board</a>.</div>';
			}
		} else {
			echo '<div class="bg-danger info-box box-border">Invalid activation code. Please contact us on our <a href="' . FORUM_LINK . '" target="_blank" title="Forum">message board</a>.</div>';
		}
	} else {
		echo '<div class="bg-danger info-box box-border">Invalid activation code. Please contact us on our <a href="' . FORUM_LINK . '" target="_blank" title="Forum">message board</a>.</div>';
	}
} else {
	$common->pageRedirect(WEBSITE_LINK);
}