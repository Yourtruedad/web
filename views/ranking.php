<h1>Ranking</h1>
<p class="lead">Below a list of the most powerful characters in the game</p>

<?php

$character = new character();
$character->hideRankingCharacterDetails = true;

if (true === USE_MYSQL_CACHE && true === cacheDb::getCacheDbConnectionStatus()) {
    $cacheDb = new cacheDb();
    if (true === $cacheDb->checkIfBasicRankingIsCurrent()) {
        $characterRanking = $cacheDb->getCurrentBasicRanking();
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

if (!empty($characterRanking)) {
?>

<div class="table-responsive">
    <table class="table table-striped">
        <tr><th>#</th><th>Nick</th><th>Country</th><th>Class</th><th>Reset</th><th>Level</th><th>Master Level</th></tr>
        <?php
        foreach ($characterRanking as $rank => $characterDetails) {
            echo '<tr>
                    <td>' . ($rank + 1) . '</td>
                    <td>' . $characterDetails[character::$characterNameSystemName] . ' ' , (1 == $characterDetails[character::$characterStatusOnlineSystemName]) ? '<span class="glyphicon glyphicon-flash" title="Player Connected" alt="Player Connected"></span>' : '' , '</td>
                    <td><span class="bfh-countries" data-country="' . $characterDetails[character::$characterCountrySystemName] . '" data-flags="true"></span></td>
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

} else {
    echo '<div class="bg-primary info-box box-border">No characters in the ranking so far.</div>';
}

?>