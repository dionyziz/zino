<?php
	// Google Ads
    function ElementAdLeaderboard() {
        global $user;
        
		?>
        <div style="width:100%;text-align:center">
            <object data="ads.php?type=leaderboard" type="text/html" style="border:1px solid #cccccc;width:745px;height:105px;overflow:hidden"><?php
            Element( 'ad/plaintext' );
            ?></object>
        </div>
        <?php
    }
?>
