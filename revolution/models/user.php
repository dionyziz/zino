<?php
    class User {
        public static function Login( $username, $password ) {
            if ( !$username || !$password ) {
                return false;
            }
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name,
                    `user_authtoken` AS authtoken, `user_gender` AS gender
                FROM
                    `users`
                WHERE
                    `user_name` = :username
                    AND `user_password` = MD5( :password ) LIMIT 1',
                compact( 'username', 'password' )
            );
            if ( mysql_num_rows( $res ) ) {
                $row = mysql_fetch_array( $res );
                $row[ 'id' ] = ( int )$row[ 'id' ];
                return $row;
            }
            return false;
        }
        public static function Item( $id ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_id` = :id
                LIMIT 1;', array( 'id' => $id )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemByName( $name ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_name` = :name
                LIMIT 1;', array( 'name' => $name )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemDetailsByName( $name ) {
            return User::ItemDetailsByWhereClause( 'user_name', $name );
        }
        public static function ItemDetails( $id ) {
            return User::ItemDetailsByWhereClause( 'user_id', $id );
        }
        private static function ItemDetailsByWhereClause( $field, $value ) {
            $query = 
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid,
                    `place_name` AS location,
                    `profile_numcomments` AS numcomments,
                    `profile_height`,
                    `profile_weight`,
                    `profile_smoker`,
                    `profile_drinker`,
                    `profile_skype`,
                    `profile_msn`,
                    `profile_gtalk`,
                    `profile_yim`,
                    `profile_eyecolor`,
                    `profile_haircolor`,
                    `profile_sexualorientation`,
                    `profile_relationship`,
                    `profile_religion`,
                    `profile_politics`,
                    `profile_slogan`,
                    `profile_aboutme`,
                    `profile_dob`,
                    `mood_labelmale`, `mood_labelfemale`,
                    `mood_url`,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                        - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                    ) AS profile_age
                FROM
                    `users`
                    CROSS JOIN `userprofiles`
                        ON `user_id`=`profile_userid`
                    CROSS JOIN `places`
                        ON `profile_placeid`=`place_id`
                    CROSS JOIN `moods`
                        ON `profile_moodid`=`mood_id`
                WHERE 
                    `' . $field . '` = :' . $field .'
                LIMIT 1;';
            $res = db( $query, array( $field => $value ) );
			$row = mysql_fetch_array( $res );
            if ( $row === false ) {
                return false;
            }
            static $mooddetails = array( 'labelmale', 'labelfemale', 'url' );
            $row[ 'mood' ] = array();
            foreach ( $mooddetails as $detail ) {
                $row[ 'mood' ][ $detail ] = $row[ 'mood_' . $detail ];
                unset( $row[ 'mood_' . $detail ] );
            }
            static $profiledetails = array(
                'height', 'weight', 'smoker', 'drinker',
                'skype', 'msn', 'gtalk', 'yim',
                'eyecolor', 'haircolor',
                'sexualorientation', 'relationship',
                'religion', 'politics',
                'slogan', 'aboutme', 'dob', 'age'
            );
            foreach ( $profiledetails as $detail ) {
                $row[ 'profile' ][ $detail ] = $row[ 'profile_' . $detail ];
                unset( $row[ 'profile_' . $detail ] );
            }
            static $positivefields = array( 'height', 'weight' );
            foreach ( $positivefields as $field ) {
                if ( !( $row[ 'profile' ][ $field ] > 0 ) ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }
            static $enumfields = array( 'sexualorientation', 'politics', 'religion', 'eyecolor', 'haircolor', 'relationship' );
            foreach ( $enumfields as $field ) {
                if ( $row[ 'profile' ][ $field ] == '-' ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }
            static $textfields = array( 'msn', 'skype', 'yim', 'aboutme', 'slogan' );
            foreach ( $textfields as $field ) {
                if ( $row[ 'profile' ][ $field ] == '' ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }

            return $row;
        }
        public static function ListOnline() {
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name
                FROM
                    `users`
                    CROSS JOIN `lastactive` ON
                        `user_id` = `lastactive_userid`
                WHERE
                    `lastactive_updated` > NOW() - INTERVAL 5 MINUTE
                ORDER BY
                    `lastactive_updated` DESC'
            );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[ $row[ 'name' ] ] = $row;
            }
            ksort( $ret );
            $ret = array_values( $ret );
            return $ret;
        }
    } 
    class UserCount { 
        public function Item( $userid ) {
            return array_pop( db_array(
                'SELECT
                    `count_images` AS images, `count_polls` AS polls, `count_journals` AS journals,
                    `count_comments` AS comments, `count_shouts` AS shouts, `count_relations` AS friends,
                    `count_answers` AS answers, `count_favourites` AS favourites
                FROM
                    `usercounts`
                WHERE
                    `count_userid` = :userid', compact( 'userid' ) )
            );
        }
    }
?>
