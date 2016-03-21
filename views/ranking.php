<h1>Ranking</h1>
<p class="lead">Below a list of the most powerful characters in the game</p>

<?php

$character = new character();
$character->hideRankingCharacterDetails = true;

$characterRanking = server::getCharacterRanking(server::$serverLevelRankingTopPlayersLimit);

if (!empty($characterRanking)) {
?>
<p class="text-right"><small>The ranking refreshes every <?=CACHE_BASIC_RANKING_TIME?> minutes.</small></p>
<div class="table-responsive">
    <table class="table table-striped">
        <tr><th>#</th><th>Nick</th><th>Country</th><th>Class</th><th>Reset</th><th>Level</th><th>Master Level</th></tr>
        <?php
        foreach ($characterRanking as $rank => $characterDetails) {
            echo '<tr>
                    <td>' . ($rank + 1) . '</td>
                    <td>' . $characterDetails[character::$characterNameSystemName] . ' ' , (1 == $characterDetails[character::$characterStatusOnlineSystemName]) ? '<span class="glyphicon glyphicon-flash" title="Player Connected" alt="Player Connected"></span>' : '' , ' ' , (!empty($characterDetails['GuildName'])) ? '[' . $characterDetails['GuildName'] . ']' : '' , '</td>
                    <td><span class="bfh-countries" data-country="' . character::returnDefaultCharacterCountryCode($characterDetails[character::$characterCountrySystemName]) . '" data-flags="true"></span></td>
                    <td>' . character::getCharacterClassName($characterDetails[character::$characterClassSystemName]) . '</td>
                    <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterResetSystemName]) . '</td>
                    <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterLevelSystemName]) . '</td>
                    <td>' . $characterDetails[character::$characterMasterLevelSystemName] . '</td>
                </tr>';
        }
        ?>
    </table>
</div>

<?php

} elseif (false === db::getDbConnectionStatus()) {
    echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
} else {
    echo '<div class="bg-primary info-box box-border">No characters in the ranking so far.</div>';
}

?>