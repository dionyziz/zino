<?php
    clude( 'models/mc.php' );
    clude( 'models/types.php' );
    global $settings;
    define( 'COMMENT_PAGE_LIMIT', 50 );

    clude( 'models/notification.php' );
    clude( 'models/activity.php' );

    class Comment {
        public static function ListByPage( $typeid, $itemid, $page ) {
            if ( $page <= 0 ) {
                $page = 1;
            }

            --$page; // start from 0

            $paged = Comment::GetMemcached( $typeid, $itemid );

            if ( !isset( $paged[ $page ] ) ) {
                return false;
            }
            $commentids = $paged[ $page ];
            
            $comments = Comment::Populate( $commentids );

            return array( count( $paged ), $comments );
        }
        public static function ListLatest( $offset, $limit = 30 ) {
            clude( 'models/db.php' );
                        
            clude( 'models/types.php' );
            clude( 'models/bulk.php' );
            clude( 'models/photo.php' );
            clude( 'models/journal.php' );
            clude( 'models/poll.php' );
            clude( 'models/user.php' );
            
            $res = db(
                'SELECT
                    `comment_id` as id,
                    `comment_userid` as userid,
                    `comment_bulkid` as bulkid,
                    `comment_itemid` as itemid,
                    `comment_typeid` as itemtype,
                    `comment_created` as created,
                    
                    `user_name` AS username,
                    `user_gender` AS gender,
                    `user_id` AS userid,
                    `user_subdomain` AS subdomain,
                    
                    `image_id` AS avatarid
                FROM
                    `comments`
                    LEFT JOIN
                        `users` on `comment_userid` = `user_id`
                    LEFT JOIN
                        `images` on `user_avatarid` = `image_id`
                    
                WHERE
                    `comment_delid` = 0
                ORDER BY
                    `comment_id` DESC
                LIMIT
                    :offset, :limit;',
                compact( 'offset', 'limit' )
            );

            $comments = array();
            
            $bulkids = array();
            
            $journalids = array();
            $photoids = array();
            $pollids = array();
            $profileids = array();
            
            $journals = array();
            $photos = array();
            $polls = array();
            $profiles = array();
            
            while ( $row = mysql_fetch_array( $res ) ) {
                $row[ 'id' ] = (int) $row[ 'id' ];
                $row[ 'userid' ] = (int) $row[ 'userid' ];
                
                $refid = array_push( $comments, array(
                    'user' => array(
                        'id' => $row[ 'userid' ],
                        'name' => $row[ 'username' ],
                        'gender' => $row[ 'gender' ],
                        'subdomain' => $row[ 'subdomain' ],
                        'avatarid' => $row[ 'avatarid' ]
                    ),
                    'id' => $row[ 'id' ],
                    'bulkid' => $row[ 'bulkid' ],
                    'itemid' => $row[ 'itemid' ]
                ) ) - 1;
                
                switch ( $row[ 'itemtype' ] ) {
                    case TYPE_PHOTO:
                        $type = 'photo';
                        $photoids[] = $row[ 'itemid' ];
                        break;
                    case TYPE_USERPROFILE:
                        $type = 'profile';
                        $profileids[] = $row[ 'itemid' ];
                        break;
                    case TYPE_JOURNAL:
                        $type = 'journal';
                        $journalids[] = $row[ 'itemid' ];
                        break;
                    case TYPE_POLL:
                        $type = 'poll';
                        $pollids[] = $row[ 'itemid' ];
                        break;
                    default:
                        $type = 'unknown';
                }
                
                $comments[ $refid ][ 'type' ] = $type;
                $bulkids[] = $row[ 'bulkid' ];
            }
            
            $bulks = Bulk::FindById( $bulkids );
            
            foreach ( $comments as $id => $comment ) {
                $comments[ $id ][ 'text' ] = $bulks[ $comment[ 'bulkid' ] ];
                unset( $comments[ $id ][ 'bulkid' ] );
            }
            
            $photos = Photo::ListByIds( $photoids );
            $journals = Journal::ListByIds( $journalids );
            $polls = Poll::ListByIds( $pollids );
            $profiles = User::ListByIds( $profileids );
            
            return compact( 'comments', 'photos', 'journals', 'polls' );
        }
        public function ListNear( $typeid, $itemid, $commentid, $offset = 0, $limit = 100000 ) {
            global $mc;

            $paged = Comment::GetMemcached( $typeid, $itemid );
            $cur_page = -1;
			
            foreach ( $paged as $page => $commentids ) { /* slow? at least not if the comment is on the first pages */
                foreach ( $commentids as $needlecommentid ) {
                    if ( $needlecommentid == $commentid ) {
                        $cur_page = $page;
                        break;
                    }
                }
                if ( $cur_page >= 0 ) {
                    break;
                }
            }

            if ( $cur_page === -1 ) {
                return false;
            }

            $commentids = $paged[ $cur_page ];
            $comments = self::Populate( $commentids );

            return array( count( $paged ), $cur_page + 1, $comments ); 
        }
        public static function Populate( $commentids ) {
            clude( 'models/bulk.php' );

            if ( empty( $commentids ) ) {
                return array();
            }

            $res = db(
                "SELECT
                    `comment_id` AS id, `comment_created` AS created,
                    `user_name` AS name, `user_gender` AS gender,
                    `user_id` AS userid,
                    `comment_bulkid` AS bulkid, `image_id` AS avatarid,
                    `comment_parentid` AS parentid
                FROM
                    `comments`
                    LEFT JOIN `users` ON `comment_userid` = `user_id`
                    LEFT JOIN `images` ON `user_avatarid` = `image_id`
                WHERE
                    `comment_id` IN :commentids;",
                array( 'commentids' => $commentids )
            );

            $comments = array();
            $bulkids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                 $comments[ $row[ 'id' ] ] = $row;
                 $bulkids[] = $row[ 'bulkid' ];
            }

            $bulks = Bulk::FindById( $bulkids );

            $ret = array();
            foreach ( $commentids as $commentid ) {
                if ( isset( $comments[ $commentid ] ) ) {
                    $comments[ $commentid ][ 'text' ] = $bulks[ $comments[ $commentid ][ 'bulkid' ] ];
                    $ret[] = $comments[ $commentid ];
                }
            }

            return $ret;
        }
        function RegenerateMemcache( $typeid, $itemid ) {
            global $mc;
            
            $children = Comment::GetFromDb( $typeid, $itemid );

            $paged = array();
            $paged[ 0 ] = array();
            $cur_page = 0;
            $stack = array( 0 );
            while ( !empty( $stack ) ) {
                $parent = array_pop( $stack );
                if ( !is_array( $parent ) ) {
                    $parentid = 0;
                }
                else {
                    $parentid = $parent[ 'comment_id' ];

                    if ( $parent[ 'comment_parentid' ] == 0 ) { // top parent found!
                        if ( count( $paged[ $cur_page ] ) >= COMMENT_PAGE_LIMIT ) {
                            ++$cur_page;
                            $paged[ $cur_page ] = array();
                        }
                    }

                    $paged[ $cur_page ][] = (int)$parent[ 'comment_id' ];
                }

                if ( !isset( $children[ $parentid ] ) ) {
                    continue;
                }
                foreach ( $children[ $parentid ] as $comment ) {
                        $stack[] = $comment;
                }
            }

            $mc->set( 'comtree_' . $itemid . '_' . $typeid, $paged );

            return $paged;
        }
        public static function GetMemcached( $typeid, $itemid ) {
            global $mc;

            $paged = $mc->get( 'comtree_' . $itemid . '_' . $typeid );
            if ( $paged === false ) {
                return Comment::RegenerateMemcache( $typeid, $itemid );
            }
            return $paged;
        }
        public static function GetFromDb( $typeid, $itemid, $offset = 0, $limit = 100000 ) {
            $res = db(
                "SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    `comments`
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid AND
                    `comment_delid` = :delid
                ORDER BY
                    `comment_id` ASC
                LIMIT
                    :offset, :limit;",
                array(
                    'typeid' => $typeid,
                    'itemid' => $itemid,
                    'delid' => 0,
                    'offset' => $offset,
                    'limit' => $limit
                )
            );
            
            $children = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $children[ $row[ 'comment_parentid' ] ][] = $row;
            }

            return $children;
        }
        public static function Create( $userid, $text, $typeid, $itemid, $parentid ) {
            clude( 'models/bulk.php' );
            clude( 'models/wysiwyg.php' );
            clude( 'models/notification.php' );
            clude( 'models/agent.php' );

			if ( !is_numeric( $parentid ) ) {
				throw New Exception( "invalid parentid" );
				return;
			}

			$parentid = ( int ) $parentid;
			$itemid = ( int ) $itemid;

            switch ( $typeid ) {
                case TYPE_POLL:
                    clude( 'models/poll.php' );
                    $item = Poll::Item( $itemid );
                    break;
                case TYPE_PHOTO:
                    clude( 'models/photo.php' );
                    $item = Photo::Item( $itemid );
                    break;
                case TYPE_USERPROFILE:
                    clude( 'models/user.php' );
                    $item = User::Item( $itemid );
                    if ( $item !== false ) {
                        $item[ 'userid' ] = $itemid;
                    }
                    break;
                case TYPE_JOURNAL:
                    clude( 'models/journal.php' );
                    $item = Journal::Item( $itemid );
                    break;
                default:
                    // no such comment type
                    return false;
            }
            if ( $item === false ) {
                // no such target item
                return false;
            }
            if ( $parentid ) {      
                // TODO: Check the parent of the parent and remove relevant notifications if owner of comment-to-be-created match
                $comment = Comment::Item( $parentid ); // TODO: Optimize; do not need bulk
                if ( $comment === false || $comment[ 'typeid' ] != $typeid || $comment[ 'itemid' ] != $itemid ) {
                    // no such parent comment
                    throw New Exception( "invalid parentid, the parent comment doesn't exist" );
                }
                if ( $comment[ 'parentid' ] ) { //has grandparent
                    $grandparentComment = Comment::Item( $comment[ 'parentid' ] );
                    if ( $grandparentComment[ 'userid' ] == $userid ) {
                        Notification::DeleteByInfo( 4, $comment[ 'id' ], $userid );
                    }
                }
                else { //hasn't grandparent                     
                    if ( $item[ 'userid' ] == $userid ) {
                        Notification::DeleteByInfo( 4, $comment[ 'id' ], $userid );
                    }
                }
                $owner = $comment[ 'user' ][ 'id' ];
            }
            else {
                $owner = $item[ 'userid' ];
            }
            $text = nl2br( htmlspecialchars( $text ) );
            $text = WYSIWYG_PostProcess( $text );

            $bulkid = Bulk::Store( $text ); 
            $userip = ( string )UserIp();

            db( "INSERT INTO `comments`
                    (`comment_userid`, `comment_bulkid`, `comment_typeid`, `comment_itemid`, `comment_parentid`, `comment_created`, `comment_userip` )
                VALUES
                    (:userid, :bulkid, :typeid, :itemid, :parentid, NOW(), :userip )", compact( 'userid', 'bulkid', 'typeid', 'itemid', 'parentid', 'userip' )
            );
            $id = mysql_insert_id();
            
            Comment::RegenerateMemcache( $typeid, $itemid );
            if ( $owner != $userid ) {
                Notification::Create( $userid, $owner, EVENT_COMMENT_CREATED, $id );
            }
            // TODO: comet
            return array(
                'id' => $id,
                'text' => $text,
                'created' => date( 'Y-m-d G:i:s' )
            );
        }
        public function Item( $commentid ){
            $ret = self::ItemMulti( array( $commentid ) );
            if ( !empty( $ret ) ) {
                return array_shift( $ret );
            }
            return false;
        }
        public static function ItemMulti( $ids, $userdetails = false ) {
            clude( 'models/bulk.php' );
            $sql = 'SELECT
                        a.`comment_id` AS id, a.`comment_typeid` AS typeid, a.`comment_itemid` AS itemid,
                        a.`comment_bulkid` AS bulkid,
                        a.`comment_created` AS created,
                        a.`comment_parentid` AS parentid,
                        parent.`comment_bulkid` AS parentbulkid,
                        uparent.`user_id` AS puserid, uparent.`user_name` AS pusername,
                        uparent.`user_gender` AS pgender, uparent.`user_subdomain` AS psubdomain,
                        uparent.`user_avatarid` AS pavatarid,
                        u.`user_id` AS userid, u.`user_name` AS username, u.`user_gender` AS gender,
                        u.`user_subdomain` AS subdomain, u.`user_avatarid` AS avatarid';
            if( $userdetails ) {
                $sql .= ', (
                            ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                            - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                        ) AS profile_age, `place_name` AS location';
            }
            $sql .= ' FROM
                        `comments` AS a CROSS JOIN `users` AS u
                            ON `comment_userid` = `user_id`
                            LEFT JOIN `comments` AS parent
                                ON a.`comment_parentid` = parent.`comment_id`
                            LEFT JOIN `users` AS uparent
                                ON parent.`comment_userid` = uparent.`user_id`';
            if ( $userdetails ) {
                $sql .= ' LEFT JOIN `places`
                            ON `profile_placeid`=`place_id`';
            }
            $sql .= ' WHERE
                        a.`comment_id` IN :ids';
            $res = db( $sql, compact( 'ids' ) );

            $commentinfo = array();
            $bulkids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $commentinfo[ $row[ 'id' ] ] = $row;
                $bulkids[] = $row[ 'bulkid' ];
                if ( !empty( $row[ 'parentbulkid' ] ) ) {
                    $bulkids[] = $row[ 'parentbulkid' ];
                }
            }
            $bulk = Bulk::FindById( $bulkids );
            foreach ( $commentinfo as $id => $comment ) {
                $commentinfo[ $id ][ 'text' ] = $bulk[ $comment[ 'bulkid' ] ];
                if ( isset( $bulk[ $comment[ 'parentbulkid' ] ] ) ) {
                    $commentinfo[ $id ][ 'parent' ] = array(
                        'id' => $comment[ 'parentid' ],
                        'text' => $bulk[ $comment[ 'parentbulkid' ] ],
                        'user' => array(
                            'id' => $comment[ 'puserid' ],
                            'name' => $comment[ 'pusername' ],
                            'avatarid' => $comment[ 'pavatarid' ],
                            'gender' => $comment[ 'pgender' ],
                            'subdomain' => $comment[ 'psubdomain' ],
                        )
                    );
                }
                $commentinfo[ $id ][ 'user' ] = array(
                    'id' => $comment[ 'userid' ],
                    'name' => $comment[ 'username' ],
                    'avatarid' => $comment[ 'avatarid' ],
                    'gender' => $comment[ 'gender' ],
                    'subdomain' => $comment[ 'subdomain' ],
                );
                if ( $userdetails ) {
                    $commentinfo[ $id ][ 'user' ][ 'place' ] = array(
                        'name' => $comment[ 'location' ]
                    );
                    $commentinfo[ $id ][ 'user' ][ 'age' ] = $comment[ 'age' ];
                }
            }
            return $commentinfo;
        }
        public static function Delete( $id ) {
            $stack = array();

            array_push( $stack, trim( $id ) );
            while ( !empty( $stack ) ) {
                $id = array_pop( $stack );

                db(
                    'UPDATE
                        `comments`
                    SET
                        `comment_delid` = 1
                    WHERE
                        `comment_id` = :id
                    LIMIT 1',
                    compact( 'id' )
                );

                $comment = Comment::Item( $id );

                Notification::DeleteByItem( $id );
                Activity::DeleteByBulk( trim( $comment[ 'bulkid' ] ) );

                $results = db_array(
                    'SELECT
                        `comment_id`
                    FROM
                        `comments`
                    WHERE
                        `comment_parentid` = :id
                        AND `comment_delid` = 0',
                    compact( 'id' )
                );

                foreach ( $results as $result ) {
                    array_push( $stack, trim( $result[ 'comment_id' ] ) );
                }
            }
        }
    }
?>
