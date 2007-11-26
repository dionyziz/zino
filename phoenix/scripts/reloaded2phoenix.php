<?php
    $sql = array(
        // comments
        "ALTER TABLE 
            `merlin_comments` 
        CHANGE 
            `comment_storyid` `comment_itemid` INT( 11 ) NOT NULL DEFAULT '0';", 
        "ALTER TABLE `merlin_comments` 
            DROP `comment_stars`,
            DROP `comment_votes`;",
        "ALTER TABLE `merlin_comments` 
            DROP `comment_textraw`",
        "ALTER TABLE `merlin_comments` 
        CHANGE 
            `comment_text` `comment_bulkid` INT NOT NULL"
    );
?>
