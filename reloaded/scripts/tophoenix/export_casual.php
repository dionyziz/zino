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

    function ProportionalSize( $sourcew, $sourceh, $targetw, $targeth ) {
        if ( $targetw == 0 || $targeth == 0 || ( $targetw >= $sourcew && $targeth >= $sourceh ) ) {
            return false;
        }

        $propw = 1;
        $proph = 1;
        
        // at least one of the following two must hold, as of the above if
        if ( $sourcew > $targetw ) {
            $propw = $sourcew / $targetw;
        }
        if ( $sourceh > $targeth ) {
            $proph = $sourceh / $targeth;
        }
        $prop = max( $propw, $proph );
        if ( $prop == 0 ) {
            throw New Exception( '"prop" was 0 while resizing to ' . $targetw . "x" . $targeth );
        }

        $targetw = round( $sourcew / $prop, 0 );
        $targeth = round( $sourceh / $prop, 0 );

        return array(
            $targetw, $targeth
        );
    }

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
            $row = $res->FetchArray();
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

    function LatinizeChar( $c )  {
        switch ( mb_strtolower( $c, 'UTF-8' ) ) {
            case 'α': case 'ά': return 'a';
            case 'β': return 'b';
            case 'γ': return 'g';
            case 'δ': return 'd';
            case 'ε': case 'έ': return 'e';
            case 'ζ': return 'z';
            case 'η': case 'ή': return 'i';
            case 'θ': return 'th';
            case 'ι': case 'ί': return 'i';
            case 'κ': return 'k';
            case 'λ': return 'l';
            case 'μ': return 'm';
            case 'ν': return 'n';
            case 'ξ': return 'ks';
            case 'ο': case 'ό': return 'o';
            case 'π': return 'p';
            case 'ρ': return 'r';
            case 'σ': return 's';
            case 'τ': return 't';
            case 'υ': case 'ύ': return 'i';
            case 'φ': return 'f';
            case 'χ': return 'x';
            case 'ψ': return 'ps';
            case 'ω': case 'ώ': return 'o';
            default: return $c;
        }
    }

    function Latinize( $name ) {
        $ret = '';

        for ( $i = 0; $i < mb_strlen( $name, 'UTF-8' ); ++$i ) {
            $ret .= LatinizeChar( mb_substr( $name, $i, 1, 'UTF-8' ) );
        }

        return $ret;
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
        ?>TRUNCATE TABLE `events`;
        TRUNCATE TABLE `notify`;
        TRUNCATE TABLE `users`;
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
                switch ( $row[ 'user_gender' ] ) {
                    case 'male':
                        ?>m<?php
                        break;
                    case 'female':
                        ?>f<?php
                        break;
                    default:
                        ?>-<?php
                        break;
                }
                ?>', `user_lastlogin`='<?php
                echo $row[ 'user_lastlogon' ];
                ?>', `user_egoalbumid`=<?php
                echo -$row[ 'user_id' ]; // indicates a "not yet set" value
                ?>;<?php
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
                echo addslashes( $row[ 'user_subtitle' ] );
                ?>', `profile_uniid`='<?php
                echo $row[ 'user_uniid' ];
                ?>', `profile_education`='<?php
                if ( empty( $row[ 'user_uniid' ] ) ) { // no uni set
                    ?>-<?php
                }
                else {
                    if ( isset( $unitypes[ $row[ 'user_uniid' ] ] ) && $unitypes[ $row[ 'user_uniid' ] ] != 0 ) { // TEI
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
                echo addslashes( $row[ 'user_skype' ] );
                ?>', `profile_msn`='<?php
                echo addslashes( $row[ 'user_msn' ] );
                ?>', `profile_yim`='<?php
                echo addslashes( $row[ 'user_yim' ] );
                ?>', `profile_gtalk`='<?php
                echo addslashes( $row[ 'user_gtalk' ] );
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
                ?>', `lastactive_updated`='<?php
                echo $row[ 'user_lastactive' ];
                ?>';<?php
        }
    }

    function MigrateBulk( $offset = 0, $test = false ) {
        global $bulk;
        
        ob_start();
        $ret = MigrateAsIs( $bulk, 'bulk', false, $offset, 1000, $test );
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
                `$albums`
            WHERE
                `album_delid`=0;"
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
                ?>', `album_mainimageid`=<?php
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

    function MigrateEgoAlbums() {
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
                $exp1 = '#((\b|^)(me+|egw+|ego+|my|' . $nickname . '|' . $subdomain . ')(\b|$))#ui';
                $exp2 = '#((\b|^)(egw+|ego+|' . $nickname . '|' . $subdomain . ')(\b|$))#ui';
                if (    preg_match( $exp1, $album[ 'album_name' ] )
                     || preg_match( $exp2, Latinize( $album[ 'album_name' ] ) ) ) {
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
        (`album_userid`, `album_created`, `album_userip`, `album_name`, `album_mainimageid`, `album_description`,
         `album_delid`, `album_numcomments`, `album_numphotos`)
            SELECT 
                `user_id` AS album_userid, NOW() AS album_created, <?php
                echo $localhost;
                ?> AS album_userip, '' AS album_name, 0 AS album_mainimageid, '' AS album_description,
                0 AS album_delid, 0 AS album_numcomments, 0 AS album_numphotos
            FROM 
                `users`
            WHERE `user_egoalbumid`<=0;
        UPDATE
            `users` CROSS JOIN (
                SELECT
                    `album_userid` AS userid, MAX( `album_id` ) AS albumid
                FROM
                    `albums`
                GROUP BY
                    `album_userid`
            ) AS tmp ON `user_id`=tmp.userid
        SET
            `user_egoalbumid`=albumid
        WHERE
            `user_egoalbumid`<=0;
        
        UPDATE
            `albums`
                LEFT JOIN `images` ON `album_id`=`image_albumid`
        SET
            `album_mainimageid`=`image_id`
        WHERE
            `album_mainimageid`=0;
            
        <?php
        // set avatars to the mainimages of the egoalbums (cross join ensures only users WITH egoalbums are updated)
        ?>
        
        UPDATE
            `users` CROSS JOIN `albums`
                ON `users`.`user_egoalbumid`=`albums`.`album_id`
        SET
            `users`.`user_icon`=`album_mainimageid`;
            
        TRUNCATE TABLE `imagesfrontpage`;
        
        INSERT INTO `imagesfrontpage` SELECT
                a.`image_id` AS frontpage_imageid, a.`image_userid` AS frontpage_userid
            FROM
                `images` AS a LEFT JOIN `images` AS b ON a.`image_id` < b.`image_id` AND a.`image_userid` = b.`image_userid` CROSS JOIN `users`
                    ON a.`image_albumid` = `users`.`user_egoalbumid`
            WHERE
                b.`image_userid` IS NULL;<?php
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
            ++$i;
            if ( $i > $limit ) {
                break;
            }
            if ( $row[ 'image_delid' ] != 0 || $row[ 'image_width' ] < 10 || $row[ 'image_height' ] < 10 ) {
                continue;
            }
            $size = ProportionalSize( $row[ 'image_width' ], $row[ 'image_height' ], 700, 600 );
            if ( $size !== false ) {
                $row[ 'image_width' ] = $size[ 0 ];
                $row[ 'image_height' ] = $size[ 1 ];
            }
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
                if ( isset( $row[ 'image_description' ] ) ) {
                    echo addslashes( $row[ 'image_description' ] );
                }
                else {
                    if ( !preg_match( '#(^dsc)|(^picture )|(^[0-9_-]*$)#', $row[ 'image_name' ] ) ) {
                        $row[ 'image_name' ] = preg_replace( '#\.(jpg|jpeg|png|gif|bmp)#', '', $row[ 'image_name' ] );
                        echo addslashes( $row[ 'image_name' ] );
                    }
                }
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
                        `$polls`
                    WHERE
                        `poll_delid`=0;"
                );
                ?>TRUNCATE TABLE `polls`;<?php
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

        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `shoutbox`;<?php
        }

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
                WHERE
                    `poll_delid`=0
                GROUP BY
                    `poll_userid`
            ) AS tmp ON `count_userid` = tmp.userid
            LEFT JOIN (
                SELECT
                    `album_userid` AS userid, COUNT(*) AS countalbums
                FROM
                    `albums`
                WHERE
                    `album_delid`=0
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
            LEFT JOIN (
                SELECT
                    `pmfolder_userid` AS userid, COUNT(*) AS countunreadpms
                FROM
                    `pmmessageinfolder` CROSS JOIN `pmfolders`
                        ON `pmif_folderid` = `pmfolder_id`
                WHERE
                    `pmif_delid` = '0'
                GROUP BY
                    `pmfolder_userid`
            ) AS tmp3 ON `count_userid` = tmp3.userid
            LEFT JOIN (
                SELECT
                    `relation_userid` AS userid, COUNT(*) AS countrelations
                FROM
                    `relations`
                GROUP BY
                    `relation_userid`
            ) AS tmp4 ON `count_userid` = tmp4.userid
            LEFT JOIN (
                SELECT
                    `answer_userid` AS userid, COUNT(*) AS countanswers
                FROM
                    `answers`
                GROUP BY
                    `answer_userid`
            ) AS tmp5 ON `count_userid` = tmp5.userid
        SET
            `usercounts`.`count_polls`=tmp.countpolls,
            `usercounts`.`count_albums`=tmp1.countalbums,
            `usercounts`.`count_journals`=tmp2.countjournals,
            `usercounts`.`count_unreadpms`=tmp3.countunreadpms,
            `usercounts`.`count_relations`=tmp4.countrelations,
            `usercounts`.`count_answers`=tmp5.countanswers;
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
            $res = $db->Query( "SELECT COUNT(*) AS numrows FROM `$comments`;" );
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
            ORDER BY
                `comment_id`
            LIMIT
                " . $offset * $limit . "," . $limit . ";"
        );
        if ( $offset == 0 ) {
            ?>TRUNCATE TABLE `comments`;<?php
        }

        while ( $row = $res->FetchArray() ) {
            if ( $row[ 'comment_delid' ] != 0 ) {
                continue;
            }
            ?>INSERT INTO bulk VALUES('','<?php
            echo addslashes( $row[ 'comment_text' ] );
            ?>');INSERT INTO comments VALUES(<?php
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
    }

    function MigrateJournals() {
        global $db, $articles, $revisions, $bulk, $libs;

        $libs->Load( 'magic_migrate' );

        $res = $db->Query(
            "SELECT
                `revision_title`,
                `article_created`, `article_numcomments`,
                `article_id`, `article_creatorid`, `bulk_text`
            FROM
                $articles CROSS JOIN $revisions
                    ON `article_id` = `revision_articleid` 
                    AND `article_headrevision` = `revision_id`
                CROSS JOIN $bulk
                    ON `revision_textid` = `bulk_id`
            WHERE
                `article_typeid`=0 AND
                `article_delid`=0;"
        );

        ?>TRUNCATE TABLE `journals`;<?php
        $rows = array();
        $i = 0;
        $total = $res->NumRows();
        while ( $row = $res->FetchArray() ) {
            $rows[] = $row;
            ++$i;
            if ( count( $rows ) % 200 == 0 || $i == $total ) {
                $texts = array();
                foreach ( $rows as $row ) {
                    $texts[ $row[ 'article_id' ] ] = $row[ 'bulk_text' ];
                }
                $formatted = mformatstories_( $texts );
                foreach ( $rows as $row ) {
                    ?>INSERT INTO `bulk` VALUES ('','<?php
                    echo addslashes( $formatted[ $row[ 'article_id' ] ] );
                    ?>');
                    INSERT INTO `journals` SET
                        `journal_created`='<?php
                        echo $row[ 'article_created' ];
                        ?>', `journal_numcomments`=<?php
                        echo $row[ 'article_numcomments' ];
                        ?>, `journal_title`='<?php
                        echo addslashes( $row[ 'revision_title' ] );
                        ?>', `journal_bulkid`=LAST_INSERT_ID(),
                        `journal_userid`=<?php
                        echo $row[ 'article_creatorid' ];
                        ?>;<?php
                }
                $rows = array();
            }
        }
        w_assert( empty( $rows ) );
    }

    function MigrateSpaces() {
        global $db, $articles, $revisions, $users, $bulk, $libs;

        $libs->Load( 'magic_migrate' );

        $res = $db->Query(
            "SELECT
                `revision_updated` AS updated, 
                `article_creatorid`, `bulk_text`
            FROM
                $articles CROSS JOIN $revisions
                    ON `article_id`=`revision_articleid`
                    AND `revision_id`=`article_headrevision`
                CROSS JOIN $bulk
                    ON `revision_textid` = `bulk_id`
            WHERE
                `article_typeid`=2
                AND `article_delid`=0
            ORDER BY
                `revision_updated`;"
        );

        $data = array();
        while ( $row = $res->FetchArray() ) {
            $data[ $row[ 'article_creatorid' ] ] = $row; // replace any previous duplicate entries
        }

        ?>TRUNCATE TABLE `userspaces`;<?php
        $rows = array();
        $i = 0;
        $total = count( $data );
        while ( $row = array_shift( $data ) ) {
            $rows[] = $row;
            ++$i;
            if ( count( $rows ) % 200 == 0 || $i == $total ) {
                $texts = array();
                foreach ( $rows as $row ) {
                    $texts[ $row[ 'article_creatorid' ] ] = $row[ 'bulk_text' ];
                }
                $formatted = mformatstories_( $texts );
                foreach ( $rows as $row ) {
                    ?>INSERT INTO `bulk` VALUES ('','<?php
                        echo addslashes( $formatted[ $row[ 'article_creatorid' ] ] );
                        ?>');INSERT INTO `userspaces` SET
                        `space_userid`=<?php
                        echo $row[ 'article_creatorid' ];
                        ?>, `space_bulkid`=LAST_INSERT_ID(), `space_updated`='<?php
                        echo $row[ 'updated' ];
                    ?>';<?php
                }
                $rows = array();
            }
        }
        w_assert( empty( $rows ) );
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
			?>INSERT INTO `questions` (`question_id`, `question_userid`, `question_created`, `question_text`, `question_delid`) VALUES ('<?php
			echo $row[ 'profileq_id' ];
			?>', '<?php
			echo $row[ 'profileq_userid' ];
			?>', '<?php
			echo $row[ 'profileq_created' ];
			?>', '<?php
            echo addslashes( $row[ 'profileq_question' ] );
            ?>','<?php
			echo $row[ 'profileq_delid' ];
			?>');<?php
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
			?>INSERT INTO `answers` (`answer_id`, `answer_userid`, `answer_text`, `answer_questionid`, `answer_created`) 
			VALUES ( '', '<?php
            echo $row[ 'profile_userid' ];
            ?>', '<?php
            echo addslashes( $row[ 'profile_answer' ] );
            ?>','<?php
            echo $row[ 'profile_questionid' ];
            ?>', '<?php
            echo $row[ 'profile_date' ];
            ?>' ); <?php
		}		
	}

    function MigratePMMessages() {
        global $db, $pmmessages;

        $res = $db->Query(
            "SELECT
                `pm_id`, `pm_senderid`, `pm_text`, `pm_textformatted`, `pm_date`
            FROM
                `$pmmessages`;"
        );
        
        ?>TRUNCATE TABLE `pmmessages`;<?php

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

    function MigratePMFolders() {
        global $db, $pmfolders;
        
        $res = $db->Query(
            "SELECT
                `pmfolder_id`, `pmfolder_userid`, `pmfolder_name`, `pmfolder_delid`
            FROM
                `$pmfolders`;"
        );

        ?>TRUNCATE TABLE `pmfolders`;<?php

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
    
    function MigratePMFolder( $type ) {
        global $db, $pmfolders, $users, $pmmessageinfolder;

        $userres = $db->Query(
            "SELECT
                `user_id`
            FROM
                `$users`;"
        );

        while ( $urow = $userres->FetchArray() ) {
            $userid = $urow[ "user_id" ];
    
            if ( $type < 0 ) {
                $typeid = ( $type == -1 ) ? 'inbox' : 'outbox';

                ?>INSERT INTO `pmfolders` SET `pmfolder_userid`=<?php
                echo $userid;
                ?>, `pmfolder_name`='folder', `pmfolder_delid`=0, `pmfolder_typeid`='<?php
                echo $typeid;
                ?>';<?php
            }
    
            $sql = "SELECT
                    *
                FROM
                    `$pmmessageinfolder`
                WHERE
                    `pmif_userid` = " . $userid . " AND
                    ";
            
            if ( $type < 0 ) {
                $sql .= "`pmif_folderid` = '" . $type . "'";
            }
            else {
                $sql .= "`pmif_folderid` > 0"; 
            }

            $sql .= ";";
            $res = $db->Query( $sql );

            $first = true;
            if ( $res->Results() ) {
                ?>INSERT INTO `pmmessageinfolder` ( `pmif_pmid`, `pmif_folderid`, `pmif_delid` ) VALUES <?php

                while ( $row = $res->FetchArray() ) {
                    if ( $type == -2 ) {
                        $delid = 1; // pms are read if they are on your outbox
                    }
                    else {
                        $delid = $row[ 'pmif_delid' ];
                    }

                    if ( $first ) {
                        $first = false;
                    }
                    else {
                        ?>, <?php
                    }

                    if ( $type < 0 ) {
                        $folderid = "LAST_INSERT_ID()";
                    }
                    else {
                        w_assert( $row[ 'pmif_folderid' ] > 0 );
                        $folderid = $row[ 'pmif_folderid' ];
                    }

                    ?>( <?php
                    w_assert( $row[ 'pmif_id' ] > 0 );
                    echo $row[ 'pmif_id' ];
                    ?>, <?php
                    echo $folderid;
                    ?>, <?php
                    echo $delid;
                    ?> ) <?php
                }

                ?>;<?php
            }
        }
    }


    function MigratePMInbox() {
        ?>TRUNCATE TABLE `pmmessageinfolder`;<?php

        MigratePMFolder( -1 );
    }

    function MigratePMOutbox() {
        MigratePMFolder( -2 );
    }

    function MigratePMOther() {
        MigratePMFolder( 1 );
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
            MigrateComments( $offset, $test );
            break;
        case 7:
            MigrateJournals();
            break;
        case 8:
            MigrateSpaces();
            break;
        case 9:
            MigrateTags();
            break;
        case 10:
            MigrateRelations();
            break;
        case 11:
            MigrateQuestions();
            break;
        case 12:
			MigrateAnswers();
            break;
        case 13:
            MigratePMFolders();
            break;
		case 14:
            MigratePMMessages();
            break;
        case 15:
            MigratePMInbox();
            break;
        case 16:
            MigratePMOutbox();
            break;
        case 17:
            MigratePMOther();
            break;
        case 18:
            MigrateEgoAlbums();
            break;
        case 19:
            MigrateCounts();
            break;
    }

    $sql = ob_get_clean();

    ob_start();
    ?> -- Step <?php
    echo $step;
    ?> migration of Excalibur Reloaded to Phoenix --
    SET NAMES 'utf8';
    START TRANSACTION;<?php
    echo $sql;
    ?>COMMIT;<?php
    $data = gzencode( ob_get_clean(), 9 );

    header( 'Content-disposition: attachment; filename=reloaded2phoenix-' . $step . '-' .$offset . '.sql.gz' );
    header( 'Content-length: ' . strlen( $data ) );
    echo $data;
?>
