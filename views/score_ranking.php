<h1>Score Ranking</h1>
<p class="lead">See below our unique character ranking</p>
<p>We measure several aspects of the game separately. In order to be number one, you need to make your character stronger but also do events, win duels and more. We measure your level, reset, amount of Zen, duels, Gens rank, Devil Square points<span style="display:none;">, Blood Castle points</span> and Chaos Castle wins.</p>

<?php

$character = new character();
$character->hideRankingCharacterDetails = true;

$characterRanking = server::getCharacterScoreRanking(server::$serverScoreRankingTopPlayersLimit);

if (!empty($characterRanking)) {
?>
<p class="text-right"><small>The ranking refreshes once a day.</small></p>
<div class="table-responsive">
    <table class="table table-striped">
        <tr><th>#</th><th>Nick</th><th>Country</th><th>Class</th><th>EWM Score</th><th>Reset</th><th>Level</th></tr>
        <?php
        foreach ($characterRanking as $rank => $characterDetails) {
            echo '<tr>
                    <td>' . ($rank + 1) . '</td>
                    <td>' . $characterDetails[character::$characterNameSystemName] . '</td>
                    <td><span class="bfh-countries" data-country="' . character::returnDefaultCharacterCountryCode($characterDetails[character::$characterCountrySystemName]) . '" data-flags="true"></span></td>
                    <td>' . character::getCharacterClassName($characterDetails[character::$characterClassSystemName]) . '</td>
                    <td>' . $characterDetails[character::$characterMainRankingScoreSystemName] . '</td>
                    <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterResetSystemName]) . '</td>
                    <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterLevelSystemName]) . '</td>
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