<?php

$sql = "ALTER TABLE `merlin_albums` CHANGE `album_submithost` `album_submithost` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_comments` CHANGE `comment_userip` `comment_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_faqcategories` CHANGE `faqcategory_creatorip` `faqcategory_creatorip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_faqquestions` CHANGE `faqquestion_creatorip` `faqquestion_creatorip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_friendrel` CHANGE `frel_creatorip` `frel_creatorip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_images` CHANGE `image_userip` `image_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_ipban` CHANGE `ipban_ip` `ipban_ip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_logs` CHANGE `log_host` `log_host` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_places` CHANGE `place_updateip` `place_updateip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_pms` CHANGE `pm_userip` `pm_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_profileq` CHANGE `profileq_userip` `profileq_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_revisions` CHANGE `revision_creatorip` `revision_creatorip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_searches` CHANGE `search_userip` `search_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_shoutbox` CHANGE `shout_userip` `shout_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_starring` CHANGE `starring_userip` `starring_userip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_templates` CHANGE `template_updateip` `template_updateip` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_users` CHANGE `user_registerhost` `user_registerhost` INT NOT NULL;";
$sql = "ALTER TABLE `merlin_usershout` CHANGE `usershout_userip` `usershout_userip` INT NOT NULL;";

?>
