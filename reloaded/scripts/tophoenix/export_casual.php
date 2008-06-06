<?php
	set_include_path( '../:./' );
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

    global $db, $shoutbox, $user;

    if ( !$user->IsSysOp() ) {
        die( 'Permission denied' );
    }


    header( 'Content-type: text/html; charset=utf8' );

    // migrate users
    $res = $db->Query(
        "SELECT
            `user_id`, `user_name`, `user_password`, `user_created`, `user_registerhost`, `user_lastlogin`,
            `user_rights`, `user_email`, `user_subdomain`, `user_signature`, `user_icon`, `user_gender`,
            `user_msn`, `user_yim`, `user_aim`, `user_icq`, `user_gtalk`, `user_skype`, `user_dob`,
            `user_hobbies`, `user_subtitle`, `user_blogid`, `user_place`, `user_uniid`,
            `user_lastprofedit`, `user_lastactive`,
            `user_contribs`, `user_respect`, `user_numcomments`,
            `user_authtoken`, `user_height`, `user_weight`, `user_eyecolor`, `user_haircolor`, `user_profilecolor`,
            `user_profviews`, `user_numsmallnews`, `user_numimages`
        FROM
            `users`;" );
    ?>TRUNCATE TABLE `users`; TRUNCATE TABLE `userprofiles`; TRUNCATE TABLE `usersettings`; TRUNCATE TABLE `usercounts`;<?php
    while ( $row = $res->FetchArray() ) {
        ?>INSERT INTO `users` SET
            `user_id` = <?php
            echo $row[ 'user_id' ];
            ?>, `user_name` = '<?php
            echo addslashes( $row[ 'user_name' ] );
            ?>', `user_password` = '<?php
            echo $row[ 'user_password' ];
            ?>', `user_authtoken` = '', `user_registerhost` = '<?php
            echo ip2long( $row[ 'user_registerhost' ] );
            ?>', `user_created` = '<?php
            echo $row[ 'user_created' ];
            ?>, `user_rights` = '<?php
            echo $row[ 'user_rights' ];
            ?>', `user_icon` = '0', `user_emailverified` = 'no', `user_subdomain` = '<?php
            echo addslashes( $row[ 'user_subdomain' ] );
            ?>', `user_gender` = '<?php
            echo $row[ 'user_gender' ];
            ?>', `user_lastlogin` = '<?php
            echo $row[ 'user_lastlogin' ];
            ?>', `user_egoalbumid` = 0;<?php
        // TODO: ego album heuristic
        ?>INSERT INTO `userprofiles` SET
            `profile_userid` = LAST_INSERT_ID(), `profile_email` = '<?php
            echo addslashes( $row[ 'user_email' ] );
            ?>', `profile_placeid` = '<?php
            echo $row[ 'user_place' ];
            ?>', `profile_dob` = '<?php
            echo $row[ 'user_dob' ];
            ?>', `profile_slogan` = '<?php
            echo $row[ 'user_subtitle' ];
            ?>', `profile_uniid` = '<?php
            echo $row[ 'user_uniid' ];
            ?>', `profile_education` = '<?php
            if ( empty( $row[ 'user_uniid' ] ) ) {
                ?>-<?php
            }
            else {
                ?>-<?php // TODO: find out if their uni is a TEI or a university
            }
            ?>', `profile_sexualorientation` = '-', `profile_religion` = '-', 
            `profile_politics` = '-', `profile_aboutme` = '', `profile_moodid` = '0', 
            `profile_eyecolor`='-', `profile_haircolor`='-', `profile_height`='0', 
            `profile_weight`='0', `profile_smoker`='-', `profile_drinker`='-',
            `profile_favquote`='', `profile_skype`='<?php
            // TODO: decide about default mood
            // TODO: haircolor/eyecolor/height/weight heuristic
            echo $row[ 'user_skype' ];
            ?>', `profile_msn`='<?php
            echo $row[ 'user_msn' ];
            ?>', `profile_yim`='<?php
            echo $row[ 'user_yim' ];
            ?>', `profile_gtalk`='<?php
            echo $row[ 'user_gtalk' ];
            ?>', `profile_homepage`='', `profile_firstname`='', `profile_lastname`='', `profile_numcomments`='<?php
            echo $row[ 'user_numcomments' ];
            ?>';<?php
        ?>INSERT INTO `usercounts` SET 
            `count_userid`=LAST_INSERT_ID(), `count_images`='<?php
            echo $row[ 'user_numimages' ];
            ?>', `count_polls`=0, `count_journals`=0, `count_albums`=0, `count_comments`=<?php
            echo $row[ 'user_contribs' ];
            ?>, `count_shouts`=<?php
            echo $row[ 'user_numsmallnews' ];
            // TODO: count polls, journals, albums
            ?>;<?php
        ?>INSERT INTO `usersettings` SET
            `setting_userid`=LAST_INSERT_ID(), `setting_emailprofile`='yes', `setting_emailphotos`='yes', `setting_emailjournals`='yes', `setting_emailpolls`='yes', `setting_emailreplies`='yes', `setting_emailfriends`='yes', `setting_notifyprofile`='yes', `setting_notifyphotos`='yes', `setting_notifyjournals`='yes', `setting_notifypolls`='yes', `setting_notifyreplies`='yes', `setting_notifyfriends`='yes';<?php
    }

    // migrate albums
    $res = $db->Query(
        "SELECT
            `album_id`, `album_userid`, `album_created`, `album_submithost`, `album_name`, `album_mainimage`, 
            `album_description`, `album_delid`, `album_pageviews`, `album_numcomments`
        FROM
            `$albums`;"
    );
    ?>TRUNCATE TABLE `albums`;<?php
    while ( $row = $res->FetchArray() ) {
        ?>INSERT INTO `albums` SET
            `album_id`=<?php
            echo $row[ 'album_id' ];
            ?>, `album_userid`=<?php
            echo $row[ 'album_userid' ];
            ?>, `album_created`=<?php
            echo $row[ 'album_created' ];
            ?>, `album_submithost`=<?php
            echo ip2long( $row[ 'album_submithost' ] );
            ?>, `album_name`='<?php
            echo addslashes( $row[ 'album_name' ] );
            ?>', `album_mainimage`=<?php
            echo $row[ 'album_mainimage' ];
            ?>, `album_description`='<?php
            echo $row[ 'album_description' ];
            ?>', `album_delid`=<?php
            echo $row[ 'album_delid' ];
            ?>, `album_pageviews`=0, `album_numcomments`=0, `album_numphotos`=0;<?php // TODO: fill-in those values
    }

    // migrate shouts
    $res = $db->Query(
        "SELECT 
            `shout_id`, `shout_userid`, `shout_created`, `shout_delid`, `shout_textformatted`
        FROM 
            $shoutbox;"
    );

    ?>TRUNCATE TABLE `shouts`;<?php
    while ( $row = $res->FetchArray() ) {
        ?>INSERT INTO `bulk` (`bulk_text`) VALUES ('<?php
        echo addslashes( $row[ 'shout_textformatted' ] );
        ?>' );INSERT INTO `shouts` (`shout_id`, `shout_userid`, `shout_created`, `shout_delid`, `shout_bulkid`) VALUES ('<?php
        echo $row[ 'shout_id' ];
        ?>', '<?php
        echo $row[ 'shout_userid' ];
        ?>', '<?php
        echo $row[ 'shout_created' ];
        ?>', '<?php
        echo $delid;
        ?>', LAST_INSERT_ID());<?php
        $fields = array();
        foreach ( $row as $field ) {
            $fields[] = "'" . addslashes( $field ) . "'";
        }
        $inserts[] = '(' . implode( ',', $fields ) . ')';
    }
    echo implode( ',', $inserts );
?>
