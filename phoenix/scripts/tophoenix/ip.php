<?php

   
    function ToPhoenix_Ip() {
        global $db;

        $queries = array( 
            "albums" => "ALTER TABLE `merlin_albums` CHANGE `album_submithost` `album_submithost` INT NOT NULL;",
            "comments" => "ALTER TABLE `merlin_comments` CHANGE `comment_userip` `comment_userip` INT NOT NULL;",
            "friend relations" => "ALTER TABLE `merlin_friendrel` CHANGE `frel_creatorip` `frel_creatorip` INT NOT NULL;",
            "images" => "ALTER TABLE `merlin_images` CHANGE `image_userip` `image_userip` INT NOT NULL;",
            "ip bans" => "ALTER TABLE `merlin_ipban` CHANGE `ipban_ip` `ipban_ip` INT NOT NULL;",
            "logs" => "ALTER TABLE `merlin_logs` CHANGE `log_host` `log_host` INT NOT NULL;",
            "places" => "ALTER TABLE `merlin_places` CHANGE `place_updateip` `place_updateip` INT NOT NULL;",
            "pms" => "ALTER TABLE `merlin_pms` CHANGE `pm_userip` `pm_userip` INT NOT NULL;",
            "profile questions" => "ALTER TABLE `merlin_profileq` CHANGE `profileq_userip` `profileq_userip` INT NOT NULL;",
            "revisions" => "ALTER TABLE `merlin_revisions` CHANGE `revision_creatorip` `revision_creatorip` INT NOT NULL;",
            "searches" => "ALTER TABLE `merlin_searches` CHANGE `search_userip` `search_userip` INT NOT NULL;",
            "shoutbox" => "ALTER TABLE `merlin_shoutbox` CHANGE `shout_userip` `shout_userip` INT NOT NULL;",
            "starring" => "ALTER TABLE `merlin_starring` CHANGE `starring_userip` `starring_userip` INT NOT NULL;",
            "users" => "ALTER TABLE `merlin_users` CHANGE `user_registerhost` `user_registerhost` INT NOT NULL;",
        );

        foreach ( $queries as $table => $sql ) {
            ?>Altering <?php
            echo $table;
            ?> table.... <?php
            $db->Query( $sql );
            ?> OK.<?php
        }
    }

?>
