<?php
	set_include_path( '../../:./' );
	
	require '../../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

    global $db, $shoutbox, $user, $users, $images, $albums, $polls, $polloptions, $votes, $universities;

    if ( !$user->IsSysOp() ) {
        die( 'Permission denied' );
    }

    function MigrateAsIs( $from, $to, $fields = false ) {
        global $db;

        if ( $fields === false ) {
            $selectfields = '*';
        }
        else {
            $selectfields = implode( ',', array_keys( $fields ) );
            $out = array();
            foreach ( $fields as $fromfield => $tofield ) {
                if ( is_numeric( $fromfield ) ) {
                    $out[ $tofield ] = $tofield;
                }
                else {
                    $out[ $fromfield ] = $tofield;
                }
            }
            $fields = $out;
        }

        $res = $db->Query(
            "SELECT
                $selectfields
            FROM
                $from;"
        );
        ?>TRUNCATE TABLE `<?php
        echo $to;
        ?>`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `<?php
            echo $to;
            ?>` SET <?php
            $values = array();
            foreach ( $row as $field => $value ) {
                $value = addslashes( $value );
                if ( $fields === false ) {
                    $targetfield = $field;
                }
                else {
                    $targetfield = $fields[ $field ];
                }
                $values[] = "$targetfield = '$value'";
            }
            echo implode( ',', $values );
            ?>;<?php
        }
    }

    header( 'Content-type: text/html; charset=utf8' );

    $res = $db->Query(
        "SELECT
            `uni_id`, `uni_typeid`
        FROM
            `$universities`;"
    );
    $unitypes = array();
    while ( $row = $res->FetchArray() ) {
        $unitypes[ $row[ 'uni_id' ] ] = $row[ 'uni_typeid' ];
    }

    // migrate users
    $res = $db->Query(
        "SELECT
            `user_id`, `user_name`, `user_password`, `user_created`, `user_registerhost`, `user_lastlogon`,
            `user_rights`, `user_email`, `user_subdomain`, `user_signature`, `user_icon`, `user_gender`,
            `user_msn`, `user_yim`, `user_aim`, `user_icq`, `user_gtalk`, `user_skype`, `user_dob`,
            `user_hobbies`, `user_subtitle`, `user_blogid`, `user_place`, `user_uniid`,
            `user_lastprofedit`, `user_lastactive`,
            `user_contribs`, `user_respect`, `user_numcomments`,
            `user_authtoken`, `user_height`, `user_weight`, `user_eyecolor`, `user_haircolor`, `user_profilecolor`,
            `user_profviews`, `user_numsmallnews`, `user_numimages`
        FROM
            `$users`;" );
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
            echo $row[ 'user_lastlogon' ];
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
            if ( empty( $row[ 'user_uniid' ] ) ) { // no uni set
                ?>-<?php
            }
            else {
                if ( isset( $unitypes[ $row[ 'user_uniid' ] ] ) && $unitypes[ $row[ 'user_uniid' ] ] != 0 ) {
                    ?>TEI<?php
                }
                else {
                    ?>university<?php
                }
            }
            ?>', `profile_sexualorientation` = '-', `profile_religion` = '-', 
            `profile_politics` = '-', `profile_aboutme` = '', `profile_moodid` = '0', 
            `profile_eyecolor`='-', `profile_haircolor`='-', `profile_height`='0', 
            `profile_weight`='0', `profile_smoker`='-', `profile_drinker`='-',
            `profile_favquote`='', `profile_skype`='<?php
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
            // TODO: count journals
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
            ?>, `album_numcomments`=<?php
            echo $row[ 'album_numcomments' ];
            ?>, `album_numphotos`=0;<?php
    }
    
    // migrate images
    $res = $db->Query(
        "SELECT
            `image_id`, `image_userid`, `image_created`, `image_userip`, `image_name`, `image_mime`,
            `image_width`, `image_height`, `image_size`, `image_delid`, `image_albumid`, `image_description`,
            `image_numcomments`
        FROM
            `$images`"
    );
    ?>TRUNCATE TABLE `images`;<?php
    while ( $row = $res->FetchArray() ) {
        ?>INSERT INTO `images` SET
            `image_id`=<?php
            echo $row[ 'image_id' ];
            ?>, `image_userid`=<?php
            echo $row[ 'image_userid' ];
            ?>, `image_created`=<?php
            echo $row[ 'image_created' ];
            ?>, `image_userip`=<?php
            echo ip2long( $row[ 'image_userip' ] );
            ?>, `image_name`=<?php
            echo addslashes( $row[ 'image_name' ] );
            ?>, `image_mime`='<?php
            echo addslashes( $row[ 'image_mime' ] );
            ?>', `image_width`=<?php
            echo $row[ 'image_width' ];
            ?>, `image_height`=<?php
            echo $row[ 'image_height' ];
            ?>, `image_size`=<?php
            echo $row[ 'image_size' ];
            ?>, `image_delid`=<?php
            echo $row[ 'image_delid' ];
            ?>, `image_albumid`=<?php
            echo $row[ 'image_albumid' ];
            ?>, `image_numcomments`=<?php
            echo $row[ 'image_numcomments' ];
            ?>;<?php
            // TODO: migrate images to serverv2 and recalculate width/height/size
    }

    // fill in numphotos in albums
    ?>UPDATE
        `albums` LEFT JOIN (
            SELECT
                COUNT(*) AS numimages, image_albumid AS albumid
            FROM
                `images`
            GROUP BY
                `image_albumid`
        ) AS tmp
    SET
        `albums`.`album_numphotos`=tmp.numimages
    WHERE
        tmp.albumid=`albums`.`album_id`;<?php

    // migrate polls
    MigrateAsIs( $polls, 'polls' );
    MigrateAsIs( $votes, 'votes', array( 'vote_userid', 'vote_date' => 'vote_created', 'vote_optionid', 'vote_pollid' ) );
    MigrateAsIs( $polloptions, 'polloptions' );

    // count polls and albums
    ?>UPDATE
        `usercounts` LEFT JOIN (
            SELECT
                `poll_userid` AS userid, COUNT(*) AS countpolls
            FROM
                `polls`
            GROUP BY
                `poll_userid`
        ) AS tmp ON `count_userid` = tmp.userid
        LEFT JOIN (
            SELECT
                `album_userid` AS userid, COUNT(*) AS countalbums
            FROM
                `albums`
            GROUP BY
                `album_userid`
        ) AS tmp1 ON `count_userid` = tmp1.userid
    SET
        `usercounts`.`count_polls`=tmp.countpolls,
        `usercounts`.`count_albums`=tmp1.countalbums;
    <?php

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
