<?php
	set_include_path( '../../:./' );
	
	require '../../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
    if ( isset( $_GET[ 'step' ] ) ) {
        $step = ( int )$_GET[ 'step' ];
    }
    else {
        $step = 0;
    }
    if ( isset( $_GET[ 'offset' ] ) ) {
        $offset = ( int )$_GET[ 'offset' ];
    }
    else {
        $offset = 0;
    }

    if ( isset( $_GET[ 'testoffset' ] ) ) {
        $test = true;
        $offset = ( int )$_GET[ 'testoffset' ];
    }
    else {
        $test = false;
    }

    define( 'ST_CONTINUE', "CONTINUE\n" );
    define( 'ST_TERMINATE', "TERMINATE\n" );

    set_time_limit( 60 );

	$water->Enable(); // on for all

    global $user;

    function IsTrusted( $ip ) {
        // Check if upload comes from a legitimate source (validate by IP address)
        switch ( $ip ) {
            case "87.230.77.29": // Zeus
            case "87.230.10.105": // Orion
                return true;
            default:
                return false;
        }
    }

    if ( !$user->IsSysOp() && !IsTrusted( $_SERVER[ 'REMOTE_ADDR' ] ) ) {
        die( 'Permission denied' );
    }

    function MigrateAsIs( $from, $to, $fields = false, $offset = 0, $limit = 0, $test = false ) {
        global $db;

        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM $from;" );
            $row = $db->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                return true;
            }
            return false;
        }
        if ( $fields === false ) {
            $selectfields = '*';
        }
        else {
            $out = array();
            foreach ( $fields as $fromfield => $tofield ) {
                if ( is_numeric( $fromfield ) ) {
                    $out[ $tofield ] = $tofield;
                }
                else {
                    $out[ $fromfield ] = $tofield;
                }
            }
            $selectfields = implode( ',', array_keys( $out ) );
        }
        $query = "SELECT
                    $selectfields
                FROM
                    $from";
        if ( $limit > 0 ) { // in this case, $offset is the page number
            $query .= " LIMIT " . $offset * $limit . ", $limit";
        }
        $query .= ';';

        $res = $db->Query( $query );

        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `<?php
            echo $to;
            ?>`;<?php
        }
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
                    $targetfield = $out[ $field ];
                }
                $values[] = "`$targetfield`='$value'";
            }
            echo implode( ',', $values );
            ?>;<?php
        }
    }

    function MigrateUsers() {
        global $db, $universities, $users;

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
        ?>TRUNCATE TABLE `users`;
        TRUNCATE TABLE `userprofiles`;
        TRUNCATE TABLE `usersettings`;
        TRUNCATE TABLE `usercounts`;
        TRUNCATE TABLE `lastactive`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `users` SET
                `user_id`=<?php
                echo $row[ 'user_id' ];
                ?>, `user_name`='<?php
                echo addslashes( $row[ 'user_name' ] );
                ?>', `user_password`='<?php
                echo $row[ 'user_password' ];
                ?>', `user_authtoken`='', `user_registerhost`='<?php
                echo ip2long( $row[ 'user_registerhost' ] );
                ?>', `user_created`='<?php
                echo $row[ 'user_created' ];
                ?>', `user_rights`='<?php
                echo $row[ 'user_rights' ];
                ?>', `user_icon`=0, `user_emailverified`='no', `user_subdomain`='<?php
                echo addslashes( $row[ 'user_subdomain' ] );
                ?>', `user_gender`='<?php
                echo $row[ 'user_gender' ];
                ?>', `user_lastlogin`='<?php
                echo $row[ 'user_lastlogon' ];
                ?>', `user_egoalbumid`=0;<?php
            ?>INSERT INTO `userprofiles` SET
                `profile_userid`=<?php
                echo $row[ 'user_id' ];
                ?>, `profile_email`='<?php
                echo addslashes( $row[ 'user_email' ] );
                ?>', `profile_placeid`='<?php
                echo $row[ 'user_place' ];
                ?>', `profile_dob`='<?php
                echo $row[ 'user_dob' ];
                ?>', `profile_slogan`='<?php
                echo $row[ 'user_subtitle' ];
                ?>', `profile_uniid`='<?php
                echo $row[ 'user_uniid' ];
                ?>', `profile_education`='<?php
                if ( empty( $row[ 'user_uniid' ] ) ) { // no uni set
                    ?>-<?php
                }
                else {
                    if ( isset( $unitypes[ $row[ 'user_uniid' ] ] ) && $unitypes[ $row[ 'user_uniid' ] ] != 0 ) {
                        echo ST_TERMINATE;
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
                `count_userid`=<?php
                echo $row[ 'user_id' ];
                ?>, `count_images`='<?php
                echo $row[ 'user_numimages' ];
                ?>', `count_polls`=0, `count_journals`=0, `count_albums`=0, `count_comments`=<?php
                echo $row[ 'user_contribs' ];
                ?>, `count_shouts`=<?php
                echo $row[ 'user_numsmallnews' ];
                ?>;<?php
            ?>INSERT INTO `usersettings` SET
                `setting_userid`=<?php
                echo $row[ 'user_id' ];
                ?>;
            INSERT INTO `lastactive` SET
                `lastactive_userid`='<?php
                echo $row[ 'user_id' ];
                ?>', `lastactive_updated`=<?php
                echo $row[ 'user_lastactive' ];
                ?>;<?php
        }
    }

    function MigrateBulk( $offset = 0, $test = false ) {
        global $bulk;
        
        ob_start();
        $ret = MigrateAsIs( $bulk, 'bulk', false, $offset, 250, $test );
        $res = ob_get_clean();

        if ( $test ) {
            if ( $ret ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }
        echo $res;
    }

    function MigrateAlbums() {
        global $db, $albums;

        // migrate albums
        $res = $db->Query(
            "SELECT
                `album_id`, `album_userid`, `album_created`, `album_submithost`, `album_name`, `album_mainimage`, 
                `album_description`, `album_delid`, `album_pageviews`, `album_numcomments`
            FROM
                `$albums`;"
        );

        $albumsbyuser = array();
        ?>TRUNCATE TABLE `albums`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `albums` SET
                `album_id`=<?php
                echo $row[ 'album_id' ];
                ?>, `album_userid`=<?php
                echo $row[ 'album_userid' ];
                ?>, `album_created`='<?php
                echo $row[ 'album_created' ];
                ?>', `album_userip`=<?php
                echo ip2long( $row[ 'album_submithost' ] );
                ?>, `album_name`='<?php
                echo addslashes( $row[ 'album_name' ] );
                ?>', `album_mainimage`=<?php
                echo $row[ 'album_mainimage' ];
                ?>, `album_description`='<?php
                echo addslashes( $row[ 'album_description' ] );
                ?>', `album_delid`=<?php
                echo $row[ 'album_delid' ];
                ?>, `album_numcomments`=<?php
                echo $row[ 'album_numcomments' ];
                ?>, `album_numphotos`=0;<?php
            if ( !isset( $albumsbyuser[ $row[ 'album_userid' ] ] ) ) {
                $albumsbyuser[ $row[ 'album_userid' ] ] = array();
            }
            $albumsbyuser[ $row[ 'album_userid' ] ][] = $row;
        }
    }

    function MigrateEgoalbums() {
        global $db, $albums, $users;

        $res = $db->Query(
            "SELECT
                `album_id`, `album_name`, `user_id`, `user_name`, `user_subdomain`
            FROM
                `$albums` CROSS JOIN `$users`
                    ON `album_userid`=`user_id`
            WHERE
                `album_delid`=0
            ORDER BY
                `user_id`, `album_id`;"
        );
        $albumsbyuser = array();
        while ( $row = $res->FetchArray() ) {
            if ( !isset( $albumsbyuser[ $row[ 'user_id' ] ] ) ) {
                $albumsbyuser[ $row[ 'user_id' ] ] = array();
            }
            $albumsbyuser[ $row[ 'user_id' ] ][] = $row;
        }

        $j = 0;
        foreach ( $albumsbyuser as $userid => $albums ) {
            foreach ( $albums as $album ) {
                $nickname = preg_quote( $album[ 'user_name' ], '#' );
                $subdomain = preg_quote( $album[ 'user_subdomain' ], '#' );
                
                if ( preg_match( '#([εΕ][Γγ][Ωώω]+|(\b|^)(me+|egw+|ego+|my|' . $nickname . '|' . $subdomain . ')(\b|$))#', $album[ 'album_name' ] ) ) {
                    // looks like an ego album
                    ?>UPDATE `users` SET `user_egoalbumid`=<?php
                    echo $album[ 'album_id' ];
                    ?> WHERE `user_id`=<?php
                    echo $album[ 'user_id' ];
                    ?> LIMIT 1;<?php
                    ++$j;
                    break; // don't look further
                }
            }
        }
        ?> -- <?php
        echo $j;
        ?> ego albums detected; the rest will default to 0 --

        <?php

        $localhost = ip2long( '127.0.0.1' );

        // create ego albums for users who don't have one
        ?>INSERT INTO `albums` 
        (`album_userid`, `album_created`, `album_userip`, `album_name`, `album_mainimage`, `album_description`,
         `album_delid`, `album_numcomments`, `album_numphotos`)
            SELECT 
                `user_id` AS album_userid, NOW() AS album_created, <?php
                echo $localhost;
                ?> AS album_userip, '' AS album_name, 0 AS album_mainimage, '' AS album_description,
                0 AS album_delid, 0 AS album_numcomments, 0 AS album_numphotos
            FROM 
                `users`
            WHERE `user_egoalbumid`=0;
        UPDATE
            `users` CROSS JOIN (
                SELECT
                    `album_userid` AS userid, MAX( `album_id` ) AS albumid
                FROM
                    `albums`
                GROUP BY
                    `album_userid`
            ) ON `user_id`=tmp.userid
        SET
            `user_egoalbumid`=albumid
        WHERE
            `user_egoalbumid`=0;<?php

        // set avatars to the mainimages of the egoalbums (cross join ensures only users WITH egoalbums are updated)
        ?>UPDATE
            `users` CROSS JOIN `albums`
                ON `users`.`user_egoalbumid`=`albums`.`album_id`
        SET
            `users`.`user_icon`=`album_mainimage`;
            
        TRUNCATE TABLE `imagesfrontpage`;
        
        INSERT INTO `imagesfrontpage` SELECT
                MAX( `image_id` ) AS frontpage_imageid, `image_userid` AS frontpage_userid
            FROM
                `images` CROSS JOIN `users` 
                    ON `images`.`image_albumid` = `albums`.`user_egoalbumid`
            GROUP BY
                frontpage_userid;<?php
    }

    function MigrateImages( $offset, $test = false ) {
        global $db, $images;

        $limit = 20000;
        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$images`" );
            $row = $res->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }
        $res = $db->Query(
            "SELECT
                `image_id`, `image_userid`, `image_created`, `image_userip`, `image_name`, `image_mime`,
                `image_width`, `image_height`, `image_size`, `image_delid`, `image_albumid`, `image_description`,
                `image_numcomments`
            FROM
                `$images`
            LIMIT
                " . $offset * $limit . ", " . ( $limit + 1 ) . ";"
        );
        
        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `images`;<?php
        }

        $i = 0;
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `images` SET
                `image_id`=<?php
                echo $row[ 'image_id' ];
                ?>, `image_userid`=<?php
                echo $row[ 'image_userid' ];
                ?>, `image_created`='<?php
                echo $row[ 'image_created' ];
                ?>', `image_userip`=<?php
                echo ip2long( $row[ 'image_userip' ] );
                ?>, `image_name`='<?php
                echo addslashes( $row[ 'image_name' ] );
                ?>', `image_mime`='<?php
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
            ++$i;
            if ( $i >= $limit ) {
                break;
            }
        }

        if ( $res->NumRows() < $limit + 1 ) {
            // fill in numphotos in albums (CROSS JOIN ensures albums with no photos stay at the default = 0)
            ?>
            UPDATE
                `albums` CROSS JOIN (
                    SELECT
                        COUNT(*) AS numimages, image_albumid AS albumid
                    FROM
                        `images`
                    GROUP BY
                        albumid
                ) AS tmp ON `albums`.`album_id`=tmp.albumid
            SET
                `albums`.`album_numphotos`=tmp.numimages;<?php
        }
    }

    function MigratePolls( $offset, $test = false ) {
        global $polls, $votes, $polloptions, $db;

        if ( $test ) {
            if ( $offset <= 2 ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }

        // migrate polls
        switch ( $offset ) {
            case 0:
                $res = $db->Query( 
                    "SELECT
                        *
                    FROM
                        `$polls`;"
                );
                while ( $row = $res->FetchArray() ) {
                    ?>INSERT INTO `polls` SET
                        `poll_id`=<?php
                        echo $row[ 'poll_id' ];
                        ?>, `poll_question`='<?php
                        echo addslashes( $row[ 'poll_question' ] );
                        ?>', `poll_userid`=<?php
                        echo $row[ 'poll_userid' ];
                        ?>, `poll_created`='<?php
                        echo $row[ 'poll_created' ];
                        ?>', `poll_delid`=<?php
                        echo $row[ 'poll_delid' ];
                        ?>, `poll_numvotes`=<?php
                        echo $row[ 'poll_numvotes' ];
                        ?>, `poll_numcomments`=<?php
                        echo $row[ 'poll_numcomments' ];
                        ?>;<?php
                }
                break;
            case 1:
                MigrateAsIs( $votes, 'votes', array( 'vote_userid', 'vote_date' => 'vote_created', 'vote_optionid', 'vote_pollid' ) );
                break;
            case 2:
                MigrateAsIs( $polloptions, 'polloptions' );
                break;
        }
    }

    function MigrateShouts( $offset, $test = false ) {
        global $db, $shoutbox;

        $limit = 20000;
        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$shoutbox`;" );
            $row = $res->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }

        // migrate shouts
        $res = $db->Query(
            "SELECT 
                `shout_id`, `shout_userid`, `shout_created`, `shout_delid`, `shout_textformatted`
            FROM 
                $shoutbox
            LIMIT
                " . $offset * $limit . ",$limit;"
        );

        ?>TRUNCATE TABLE `shoutbox`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `bulk` (`bulk_text`) VALUES ('<?php
            echo addslashes( $row[ 'shout_textformatted' ] );
            ?>');INSERT INTO `shoutbox` (`shout_id`, `shout_userid`, `shout_created`, `shout_delid`, `shout_bulkid`) VALUES ('<?php
            echo $row[ 'shout_id' ];
            ?>', '<?php
            echo $row[ 'shout_userid' ];
            ?>', '<?php
            echo $row[ 'shout_created' ];
            ?>', '<?php
            echo $delid;
            ?>', LAST_INSERT_ID());<?php
        }
    }

    function MigrateCounts() {
        // count polls, albums, and journals
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
            LEFT JOIN (
                SELECT
                    `journal_userid` AS userid, COUNT(*) AS countjournals
                FROM
                    `journals`
                GROUP BY
                    `journal_userid`
            ) AS tmp2 ON `count_userid` = tmp2.userid
        SET
            `usercounts`.`count_polls`=tmp.countpolls,
            `usercounts`.`count_albums`=tmp1.countalbums,
            `usercounts`.`count_journals`=tmp2.countjournals;
        <?php
    }

    function MigrateComments( $offset = 0, $test = false ) {
        global $db, $comments;

        $commenttypes = array(
            0 => 4,
            1 => 3,
            2 => 2,
            3 => 1
        );
        
        $limit = 20000;
        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$comments` WHERE `comment_delid`=0;" );
            $row = $res->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }

        $res = $db->Query(
            "SELECT
                `comment_id`, `comment_userid`, `comment_created`, `comment_userip`,
                `comment_text`, `comment_typeid`, `comment_storyid`, `comment_parentid`
            FROM
                `$comments`
            WHERE
                `comment_delid`=0
            LIMIT
                " . $offset * $limit . "," . $limit . ";"
        );
        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `comments`;<?php
        }

        ?>START TRANSACTION;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO bulk SET bulk_text='<?php
            echo addslashes( $row[ 'comment_text' ] );
            ?>';INSERT INTO comments VALUES (<?php
            echo $row[ 'comment_id' ];
            ?>,<?php
            echo $row[ 'comment_userid' ];
            ?>,'<?php
            echo $row[ 'comment_created' ];
            ?>',<?php
            echo ip2long( $row[ 'comment_userip' ] );
            ?>,LAST_INSERT_ID(),<?php
            echo $row[ 'comment_storyid' ];
            ?>,<?php
            echo $row[ 'comment_parentid' ];
            ?>,0,<?php
            echo $commenttypes[ $row[ 'comment_typeid' ] ];
            ?>);<?php
        }
        ?>COMMIT;<?php
    }

    function MigrateJournals() {
        global $db, $articles, $revisions;

        $res = $db->Query(
            "SELECT
                latest.`revision_title`, latest.`revision_textid`, first.`revision_creatorid`,
                `article_created`, `article_numcomments`, `article_id`
            FROM
                $articles CROSS JOIN $revisions AS latest
                    ON `article_id` = latest.`revision_articleid` 
                    AND `article_headrevision` = latest.`revision_id`
                CROSS JOIN $revisions AS first
                    ON `article_id` = first.`revision_articleid`
                LEFT JOIN $revisions AS comparison
                    ON `article_id` = comparison.`revision_articleid`
                    AND comparison.revision_id < first.revision_id
            WHERE
                comparison.revision_id IS NULL
                AND `article_typeid`=0
            GROUP BY
                `article_id`;"
        );

        ?>TRUNCATE TABLE `journals`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `journals` SET
                `journal_created`='<?php
                echo $row[ 'article_created' ];
                ?>', `journal_numcomments`=<?php
                echo $row[ 'article_numcomments' ];
                ?>, `journal_title`='<?php
                echo addslashes( $row[ 'revision_title' ] );
                ?>', `journal_bulkid`=<?php
                echo $row[ 'revision_textid' ];
                ?>, `journal_userid`=<?php
                echo $row[ 'revision_creatorid' ];
                ?>;<?php
        }
    }

    function MigrateSpaces() {
        global $db, $articles, $revisions, $users;

        $res = $db->Query(
            "SELECT
                `user_id`, `revision_textid`, `revision_updated`
            FROM
                $users CROSS JOIN $articles
                    ON `user_blogid`=`article_id`
                CROSS JOIN $revisions
                    ON `article_id`=`revision_articleid`
                    AND `revision_id`=`article_headrevision`
            WHERE
                `article_typeid`=2
                AND `article_delid`=0;"
        );

        ?>TRUNCATE TABLE `userspaces`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `userspaces` SET
                `space_userid`=<?php
                echo $row[ 'user_id' ];
                ?>, `space_bulkid`=<?php
                echo $row[ 'revision_textid' ];
                ?>, `revision_updated`='<?php
                echo $row[ 'revision_updated' ];
            ?>';<?php
        }
    }

    function MigrateTags() {
        global $db, $interesttags;

        $res = $db->Query(
            "SELECT
                `interesttag_id`, `interesttag_userid`, `interesttag_text`, `interesttag_next`
            FROM
                `$interesttags`;"
        );

        ?>TRUNCATE TABLE `tags`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `tags` SET `tag_id`=<?php
            echo $row[ 'interesttag_id' ];
            ?>, `tag_userid`=<?php
            echo $row[ 'interesttag_userid' ];
            ?>, `tag_text`='<?php
            echo addslashes( $row[ 'interesttag_text' ] );
            ?>', `tag_typeid`=1, `tag_nextid`=<?php
            echo $row[ 'interesttag_next' ];
            ?>;<?php
        }
    }

    function MigrateRelations() {
        global $db, $friendrel, $relations;

        $res = $db->Query(
            "SELECT
                `relation_id`, `relation_userid`, `relation_friendid`, `relation_type`
            FROM
                `$relations`;"
        );
        ?>TRUNCATE TABLE `relations`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `relations` SET
                `relation_userid`='<?php
                echo $row[ 'relation_userid' ];
                ?>', `relation_friendid`=<?php
                echo $row[ 'relation_friendid' ];
                ?>, `relation_typeid`=<?php
                echo $row[ 'relation_type' ];
                ?>, `relation_created`=NOW();<?php
        }

        $res = $db->Query(
            "SELECT
                `frel_id`, `frel_type`, `frel_created`, `frel_creatorid`, `frel_creatorip`
            FROM
                `$friendrel`;"
        );
        ?>TRUNCATE TABLE `relationtypes`;<?php
        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `relationtypes` SET
			`relationtype_id`=<?php
			echo $row[ 'frel_id' ];
			?>, `relationtype_text`='<?php
			echo addslashes( $row[ 'frel_type' ] );
			?>', `relationtype_created`='<?php
			echo $row[ 'frel_created' ];
			?>', `relationtype_userid`=<?php
			echo $row[ 'frel_creatorid' ];
			?>, `relationtype_userip`=<?php
			echo ip2long( $row[ 'frel_creatorip' ] );
			?>;<?php
        }
    }

    function MigrateQuestions() {
        // $questions here is the old question table merlin_profileq :P
		global $db, $questions;

		$res = $db->Query(
            "SELECT
                `profileq_id`, `profileq_userid`, `profileq_created`, `profileq_question`, `profileq_userip`, `profileq_delid`
            FROM
                `$questions`;"
        );

		?>TRUNCATE TABLE `questions`;<?php
		while ( $row = $res->FetchArray() ) {
			?>INSERT INTO `bulk` (`bulk_text`) VALUES ('<?php
		   	echo addslashes( $row[ 'profileq_question' ] );
			?>' );INSERT INTO `questions` (`question_id`, `question_userid`, `question_created`, `question_bulkid`, `question_delid`) VALUES ('<?php
			echo $row[ 'profileq_id' ];
			?>', '<?php
			echo $row[ 'profileq_userid' ];
			?>', '<?php
			echo $row[ 'profileq_created' ];
			?>', LAST_INSERT_ID(),'<?php
			echo $row[ 'profileq_delid' ];
			?>'); '<?php
		}
	}

	function MigrateAnswers() {
		global $db, $profileanswers;

		$res = $db->Query(
            "SELECT
                `profile_userid`, `profile_answer`, `profile_questionid`, `profile_date`
            FROM
                `$profileanswers`;"
        );

		?>TRUNCATE TABLE `answers`;<?php
		while ( $row = $res->FetchArray() ) {
			?>INSERT INTO `bulk` (`bulk_text`) VALUES ( '<?php echo addslashes( $row[ 'profile_answer' ] ); ?>' );
			INSERT INTO `answers` (`answer_userid`, `answer_bulkid`, `answer_questionid`, `answer_created`) 
			VALUES ( '<?php
            echo $row[ 'profile_userid' ];
            ?>', LAST_INSERT_ID(),'<?php
            echo $row[ 'profile_questionid' ];
            ?>', '<?php
            echo $row[ 'profile_date' ];
            ?>' ); <?php
		}		
	}

    function MigratePMFolders() {
        global $db, $pmfolders;
        
        $res = $db->Query(
            "SELECT
                `pmfolder_id`, `pmfolder_userid`, `pmfolder_name`, `pmfolder_delid`
            FROM
                `$pmfolders`;"
        );

        ?>TRUNCATE TABLE `pmfolders`; 
        
        INSERT 
            INTO `pmfolders` ( `pmfolder_id`, `pmfolder_userid`, `pmfolder_name`, `pmfolder_delid`, `pmfolder_typeid` )
        SELECT 
            '', `user_id`, 'inbox', '0', 'inbox' 
        FROM 
            `users`; 
        
        INSERT
            INTO `pmfolders` ( `pmfolder_id`, `pmfolder_userid`, `pmfolder_name`, `pmfolder_delid`, `pmfolder_typeid` )
        SELECT
            '', `user_id`, 'outbox', '0', 'outbox'
        FROM
            `users`;<?php

        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `pmfolders` SET `pmfolder_id`=<?php
            echo $row[ 'pmfolder_id' ];
            ?>, `pmfolder_userid`=<?php
            echo $row[ 'pmfolder_userid' ];
            ?>, `pmfolder_name`='<?php
            echo addslashes( $row[ 'pmfolder_name' ] );
            ?>', `pmfolder_delid`=<?php
            echo $row[ 'pmfolder_delid' ];
            ?>, `pmfolder_typeid`='user';<?php
        }
    }

    function MigratePMMessages( $offset = 0, $test = false ) {
        global $db, $pmmessages;

        $limit = 20000;
        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$pmmessages`;" );
            $row = $res->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }

        $res = $db->Query(
            "SELECT
                `pm_id`, `pm_senderid`, `pm_text`, `pm_textformatted`, `pm_date`
            FROM
                `$pmmessages`
            LIMIT
                " . $offset * $limit . "," . $limit . ";"
        );
        
        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `pmmessages`;<?php
        }

        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `bulk` SET `bulk_text`='<?php
            echo addslashes( $row[ 'pm_textformatted' ] );
            ?>';INSERT INTO `pmmessages` SET `pm_id`=<?php
            echo $row[ 'pm_id' ];
            ?>, `pm_senderid`=<?php
            echo $row[ 'pm_senderid' ];
            ?>, `pm_bulkid`=LAST_INSERT_ID(), `pm_created`='<?php
            echo $row[ 'pm_date' ];
            ?>';<?php
        }
    }

    function MigratePMMessageInFolder( $offset, $test = 'false' ) {
        global $db, $pmmessageinfolder;

        $limit = 20000;
        if ( $test ) {
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$pmmessageinfolder`;" );
            $row = $res->FetchArray();
            if ( $offset * $limit < $row[ 'numrows' ] ) {
                echo ST_CONTINUE;
            }
            else {
                echo ST_TERMINATE;
            }
            exit();
        }

        $res = $db->Query( "
            SELECT
                `pmif_id`, `pmif_userid`, `pmif_folderid`, `pmif_delid`
            FROM
                `$pmmessageinfolder`
            LIMIT
                " . $offset * $limit . "," . $limit . ";"
        );

        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `pmmessageinfolder`;<?php
        }

        while ( $row = $res->FetchArray() ) {
            ?>INSERT INTO `pmmessageinfolder` SET `pmif_pmid`=<?php
            echo $row[ 'pmif_id' ];
            ?>, `pmif_folderid`=<?php
            echo $row[ 'pmif_folderid' ];
            ?>, `pmif_delid`=<?php
            echo $row[ 'pmif_delid' ];
            ?>;<?php
        }
    }
    
    function MigrateNotifications() {
        // No notifications migration
    }

    header( 'Content-type: text/html; charset=utf8' );
    ob_start();

    switch ( $step ) {
        case 0:
            MigrateUsers();
            break;
        case 1:
            MigrateAlbums();
            break;
        case 2:
            MigrateImages( $offset, $test );
            break;
        case 3:
            MigratePolls( $offset, $test );
            break;
        case 4:
            MigrateBulk( $offset, $test );
            break;
        case 5:
            MigrateShouts( $offset, $test );
            break;
        case 6:
            MigrateCounts();
            break;
        case 7:
            MigrateComments( $offset, $test );
            break;
        case 8:
            MigrateJournals();
            break;
        case 9:
            MigrateSpaces();
            break;
        case 10:
            MigrateTags();
            break;
        case 11:
            MigrateRelations();
            break;
        case 12:
            MigrateQuestions();
            break;
        case 13:
			MigrateAnswers();
            break;
        case 14:
            MigratePMFolders();
            break;
		case 15:
            MigratePMMessages( $offset, $test );
            break;
        case 16:
            MigratePMMessageInFolder( $offset, $test );
            break;
        case 17:
            MigrateEgoAlbums();
            break;
    }

    $sql = ob_get_clean();

    ob_start();
    ?> -- Step <?php
    echo $step;
    ?> migration of Excalibur Reloaded to Phoenix --
    <?php
    echo $sql;
    $data = gzencode( ob_get_clean(), 9 );

    header( 'Content-disposition: attachment; filename=reloaded2phoenix-' . $step . '-' .$offset . '.sql.gz' );
    header( 'Content-length: ' . strlen( $data ) );
    echo $data;
?>
