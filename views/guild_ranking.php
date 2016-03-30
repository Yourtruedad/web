<h1>Guild Ranking</h1>
<p class="lead"></p>

<?php

$server = new server();
$character = new character();
$character->hideRankingguildDetails = true;

$guildRanking = db::getGuildRanking();//server::getCharacterScoreRanking(server::$serverScoreRankingTopPlayersLimit);

if (!empty($guildRanking)) {
?>
<p class="text-right"><small>The ranking refreshes every 2 hours.</small></p>
<div class="table-responsive">
    <table class="table table-striped">
        <tr><th>#</th><th>Guild Name</th><th>Guild Mark</th><th>Guild Master</th><th>Members</th></tr>
        <?php
        foreach ($guildRanking as $rank => $guildDetails) {
            echo '<tr>
                    <td>' . ($rank + 1) . '</td>
                    <td>' . $guildDetails[character::$guildNameSystemName] . '</td>
                    <td>' . $server->drawGuildMark($guildDetails[character::$guildMarkSystemName]) . '</td>
                    <td>' . $guildDetails[character::$guildMasterSystemName] . '</td>
					
                </tr>';
        }
        ?>
    </table>
</div>

<?php

} elseif (false === db::getDbConnectionStatus()) {
    echo '<div class="bg-danger info-box box-border">This module is not available at the moment. Please try again later.</div>';
} else {
    echo '<div class="bg-primary info-box box-border">No guilds in the ranking so far.</div>';
}

?>