<?php
    class ItemDeletedException extends Exception {}

    class User {
        public static function SetPassword( $id, $oldpassword, $newpassword ) {
            clude( 'models/db.php' );
            $id = ( int )$id;

            $res = db( 
                'SELECT 
                    `user_password` AS pass
                FROM `users`
                WHERE `user_id` = :id
                LIMIT 1',
                compact( 'id' ) );
            if ( mysql_num_rows( $res  ) ) {
                $row = mysql_fetch_array( $res );
                if ( $row[ 'pass' ] == md5( $oldpassword ) ) {
                    $res = db( 
                        'UPDATE
                            `users`
                        SET
                            `user_password` = MD5( :newpassword )                            
                        WHERE `user_id` = :id;',
                        compact( 'id', 'newpassword' ) );
                    return true;
                }
                return false;
            }
            return false;
        }
		public static function SetGender( $id, $value ) {
			clude( 'models/db.php' );
            $id = ( int )$id;
			if ( $value != 'm' && $value != 'f' && $value != '-' ) {
				return false;
			}

            $res = db( 
                'UPDATE
                    `users`
                SET
                    `user_gender` = :value                         
                WHERE `user_id` = :id;',
                compact( 'id', 'value' ) );
			return true;
	
		}
        public static function GetCookieData() {
            global $settings;
            if ( !isset( $_COOKIE[ $settings[ 'cookiename' ] ] ) ) {
                return false;
            }
            $cookie = $_COOKIE[ $settings[ 'cookiename' ] ];
            $cookieparts = explode( ':' , $cookie );
            $userid = (int) $cookieparts[ 0 ];
            $authtoken = $cookieparts[ 1 ];
            if ( $userid <= 0 ) {
                return false;
            }
            if ( !preg_match( '#^[a-zA-Z0-9]{32}$#', $authtoken ) ) {
                die( 'invalid auth' );
                return false;
            }
            clude( 'models/db.php' );
            return User::AuthtokenValidation( $userid, $authtoken );
        }
        public function RenewAuthtoken( $userid ) {
            $userid = ( int )$userid;

            // generate authtoken
            // first generate 16 random bytes
            // generate 8 pseurandom 2-byte sequences 
            // (that's bad but generally conventional pseudorandom generation algorithms do not allow very high limits
            // unless they repeatedly generate random numbers, so we'll have to go this way)
            $bytes = array(); // the array of all our 16 bytes
            for ( $i = 0; $i < 8 ; ++$i ) {
                $bytesequence = rand( 0, 65535 ); // generate a 2-bytes sequence
                // split the two bytes
                // lower-order byte
                $a = $bytesequence & 255; // a will be 0...255
                // higher-order byte
                $b = $bytesequence >> 8; // b will also be 0...255
                // append the bytes
                $bytes[] = $a;
                $bytes[] = $b;
            }
            // now that we have 16 "random" bytes, create a string of 32 characters,
            // each of which will be a hex digit 0...f
            $authtoken = ''; // start with an empty string
            foreach ( $bytes as $byte ) {
                // each byte is two authtoken digits
                // split them up
                $first = $byte & 15; // this will be 0...15
                $second = $byte >> 4; // this will be 0...15 again
                // convert decimal to hex and append
                // order doesn't really matter, it's all random after all
                $authtoken .= dechex( $first ) . dechex( $second );
            }
            
            clude( 'models/db.php' );
            db(
                'UPDATE
                    `users`
                SET
                    `user_authtoken`=:authtoken
                WHERE
                    `user_id`=:userid
                LIMIT 1', compact( 'userid', 'authtoken' )
            );

            return $authtoken;
        }
        public static function ClearAuthtoken( $userid ) {
            $userid = ( int )$userid;
            
            clude( 'models/db.php' );
            $authtoken = '';
            
            db(
                'UPDATE
                    `users`
                SET
                    `user_authtoken`=:authtoken
                WHERE
                    `user_id`=:userid
                LIMIT 1', compact( 'userid', 'authtoken' )
            );
            
            return true;
        }
        public static function AuthtokenValidation( $userid, $authtoken )  {
            if ( !is_int( $userid ) || !$userid || !$authtoken ) {
                return false;
            }
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name,
                    `user_authtoken` AS authtoken, `user_gender` AS gender
                FROM
                    `users`
                WHERE
                    `user_id` = :userid AND
                    `user_authtoken` = :authtoken
                LIMIT 1;',
                compact( 'userid', 'authtoken' )
            );
            
            if ( mysql_num_rows( $res ) ) {
                $row = mysql_fetch_array( $res );
                $row[ 'id' ] = (int)$row[ 'id' ];
                return $row;
            }
            
            return false;
        }
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
        public static function GetEgoAlbumId( $userid ) {
            return ( int )array_shift( array_shift( db_array(
                'SELECT
                    `user_egoalbumid` AS egoalbumid
                FROM
                    `users`
                WHERE
                    `user_id` = :userid
                LIMIT 1;', compact( 'userid' )
            ) ) );
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
                LIMIT 1;', compact( 'id' )
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
                LIMIT 1;', compact( 'name' )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemBySubdomain( $subdomain ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_subdomain` = :subdomain
                LIMIT 1;', compact( 'subdomain' )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemDetailsBySubdomain( $subdomain ) {
            return User::ItemDetailsByWhereClause( 'user_subdomain', $subdomain );
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
                    `place_id` AS location_id,
                    `profile_email`,
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
                    `profile_moodid`,
                    `mood_labelmale`, `mood_labelfemale`,
                    `mood_url`,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                        - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                    ) AS profile_age,
                    `latest`.`statusbox_message` AS status
                FROM
                    `users`
                    LEFT JOIN `userprofiles`
                        ON `user_id`=`profile_userid`
                    LEFT JOIN `places`
                        ON `profile_placeid`=`place_id`
                    LEFT JOIN `moods`
                        ON `profile_moodid`=`mood_id`
                    LEFT JOIN `statusbox` AS latest
                        ON  `user_id` = `latest`.`statusbox_userid`
                    LEFT JOIN `statusbox` AS newer
                        ON `user_id` = `newer`.`statusbox_userid` AND `newer`.`statusbox_id` > `latest`.`statusbox_id`
                WHERE 
                    `' . $field . '` = :' . $field .' AND
                    `newer`.`statusbox_id` IS NULL
                LIMIT 1;';
            $res = db( $query, array( $field => $value ) );
			$row = mysql_fetch_array( $res );
            if ( $row === false ) {
                return false;
            }
            if ( $row[ 'userdeleted' ] == 1 ) {
                throw new ItemDeletedException( 'User deleted' );
            }
            static $mooddetails = array( 'labelmale', 'labelfemale', 'url' );
            if ( $row[ 'mood_url' ] != '' ) {
                $row[ 'mood' ] = array();
                foreach ( $mooddetails as $detail ) {
                    $row[ 'mood' ][ $detail ] = $row[ 'mood_' . $detail ];
                }
                $row[ 'mood' ][ 'id' ] = $row[ 'profile_moodid' ];
            }
            foreach ( $mooddetails as $detail ) {
                unset( $row[ 'mood_' . $detail ] );
            }
            static $profiledetails = array(
                'height', 'weight', 'smoker', 'drinker',
                'skype', 'msn', 'gtalk', 'yim', 'email',
                'eyecolor', 'haircolor',
                'sexualorientation', 'relationship',
                'religion', 'politics',
                'slogan', 'aboutme', 'dob', 'age'
            );
            foreach ( $profiledetails as $detail ) {
                $row[ 'profile' ][ $detail ] = $row[ 'profile_' . $detail ];
                unset( $row[ 'profile_' . $detail ] );
            }
            
            if ( isset( $row[ 'profile' ][ 'height' ] ) ) {
                $height = $row[ 'profile' ][ 'height' ];
                if ( !( ( $height >= -2 && $height <= -1 ) || ( $height >= 120 && $height <= 220 ) ) ) {
                    unset( $row[ 'profile' ][ 'height' ] );
                }
            }
            if ( isset( $row[ 'profile' ][ 'weight' ] ) ) {
                $weight = $row[ 'profile' ][ 'weight' ];
                if ( !( ( $weight >= -2 && $weight <= -1 ) || ( $weight >= 40 && $weight <= 120 ) ) ) {
                    unset( $row[ 'profile' ][ 'weight' ] );
                }
            }
            
            static $positivefields = array( /* 'height', 'weight' - commented out by Chorvus due to special negative values*/ );
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
            if ( empty( $row[ 'status' ] ) ) {
                unset( $row[ 'status' ] );
            }

            return $row;
        }
        public static function UpdateItemDetails( $details, $userid ) {
            $whitelist = array_flip( array( 'profile_email', 'profile_emailvalidated', 'profile_emailvalidationhash', 'profile_placeid' , 'profile_dob', 'profile_slogan', 'profile_schoolid', 'profile_sexualorientation', 'profile_relationship', 'profile_religion', 'profile_politics', 'profile_aboutme', 'profile_moodid', 'profile_eyecolor', 'profile_haircolor', 'profile_height', 'profile_weight', 'profile_smoker', 'profile_drinker', 'profile_favquote', 'profile_mobile', 'profile_skype', 'profile_msn', 'profile_gtalk', 'profile_yim', 'profile_homepage', 'profile_firstname', 'profile_lastname', 'profile_address', 'profile_addressnum', 'profile_postcode', 'profile_area', 'profile_numcomments', 'profile_education', 'profile_educationyear', 'profile_songid', 'profile_songwidgetid' ) );

            if ( !is_array( $details ) ) return false;
            foreach ( $details as $key => $val ) {
                if ( !isset( $whitelist[ $key ] ) ) {
                    return false;
                }
            }

			$details = User::CheckDataOnUserUpdate( $details );
            
			$first = true;
            $query = 
                'UPDATE `userprofiles`
                SET ';
            foreach ( $details as $key => $val ) {
				if ( $first == false ) {
					$query .= ", ";
				}		
				else {
					$first = false;
				}
                $query .= " $key = :$key ";
            }       
            $query = $query . 'WHERE 
                    `profile_userid` = :userid
                LIMIT 1;';

            $details[ 'userid' ] = $userid;
            $res = db( $query, $details );
			
            return true;
        }
		private static function CheckDataOnUserUpdate( $data ) {
/* TODO
'profile_email' 
'profile_dob'
*/
			$validints = array ( 'profile_moodid', 'profile_placeid' );
			$validarrays = array( 
				'profile_sexualorientation' => array( '-' , 'straight', 'bi', 'gay' ) ,
 				'profile_relationship' => array( '-', 'single', 'relationship', 'casual', 'engaged', 'married','complicated' ),
				'profile_religion' => array( '-', 'christian', 'muslim', 'atheist','agnostic', 'nothing', 'pastafarian', 'pagan', 'budhist', 'greekpolytheism', 'hindu' ),
				'profile_politics' => array( '-', 'right', 'left', 'center', 'radical left', 'center left', 'center right', 'nothing', 'anarchism', 'communism', 'socialism', 'liberalism', 'green' ),
				'profile_eyecolor' => array ( '-', 'black','brown', 'green', 'blue', 'grey' ),
				'profile_haircolor' => array( '-', 'black', 'brown', 'red', 'blond', 'highlights', 'dark', 'grey', 'skinhead' ),
				'profile_smoker' => array( '-', 'yes', 'no', 'socially' ),
				'profile_drinker' => array( '-', 'yes', 'no', 'socially' ),
				'user_gender' => array( '-', 'm', 'f' )
			);

			foreach ( $validints as $one ) {
				if ( isset( $data[ $one ] ) ) {
					$data[ $one ] = ( int ) $data[ $one ];
				}
			}
			foreach ( $validarrays as $key => $val ) {
				if ( isset( $data[ $key ] ) ) {
					if ( !in_array( $data[ $key ], $val ) ) {
						$data[ $key ] = $val[ 0 ];
					}
				}	
			}
	
			return $data;
		}
        public static function UpdateLastActive( $userid, $authtoken = false ) {
            if ( !is_bool( $authtoken ) && !is_string( $authtoken ) ) {
                die( 'invalid authtoken' );
            }

            $sql = 'SELECT
                        `user_id` AS id, `user_name` AS name, `user_avatarid` AS avatarid
                    FROM
                        `users`
                    WHERE
                        `user_id` = :userid';
            if ( $authtoken !== false ) {
                $sql .= ' AND `user_authtoken` = :authtoken ';
            }
            $sql .= ' LIMIT 1;';

            $user = mysql_fetch_array( db( $sql, compact( 'userid', 'authtoken' ) ) );
            if ( $user === false ) {
                return false;
            }

            $sql = 'UPDATE
                        `lastactive`
                    SET
                        `lastactive_updated` = NOW()
                    WHERE
                        `lastactive_userid` = :userid
                    LIMIT 1';

            if ( db( $sql, compact( 'userid' ) ) ) {
                return $user;
            }

            return false;
        }
        public static function ListOnline() {
            global $settings;

            $url = $settings[ 'presence' ][ 'url' ];

            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071030 Firefox/2.0.0.8" );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            
            if ( ( $data = curl_exec( $ch ) ) === false ) {
                die( 'failed connecting to presence server' );
            }

            curl_close( $ch );

            $userids = explode( "\n", $data ); 

            array_pop( $userids );

            if ( empty( $userids ) ) {
                // nobody here, return
                return array();
            }

            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name, `user_avatarid` AS avatarid,
                    `user_gender` AS gender, `place_name` AS location,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                        - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                    ) AS age
                FROM
                    `users` 
                    LEFT JOIN `userprofiles` ON
                        `profile_userid` = `user_id`
                    LEFT JOIN `places` ON
                        `place_id` = `profile_placeid`
                WHERE
                    `user_id` IN :userids;', compact( 'userids' )
            );

            $users = array();
            while ( $row = mysql_fetch_array( $res ) ){ 
                $row[ 'id' ] = (int)$row[ 'id' ];
                $row[ 'avatarid' ] = (int)$row[ 'avatarid' ];
                $users[ $row[ 'name' ] ] = $row;
            }

            uksort( $users, 'strnatcasecmp' ); // sort by name, case insensitive

            return $users;
        }
        public static function ListByNameStart( $query ) {
            $query .= '%';
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name, `user_avatarid` AS avatarid,
                    `user_gender` AS gender, `place_name` AS location,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                        - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                    ) AS age
                FROM
                    `users` 
                    LEFT JOIN `userprofiles` ON
                        `profile_userid` = `user_id`
                    LEFT JOIN `places` ON
                        `place_id` = `profile_placeid`
                WHERE
                    `user_name` LIKE :query', compact( 'query' )
            );

            $users = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $users[ $row[ 'name' ] ] = $row;
            }

            uksort( $users, 'strnatcasecmp' );

            return $users;
        }
        /* old code, switching to presence server
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
                $ret[ strtolower( $row[ 'name' ] ) ] = $row;
            }
            ksort( $ret );
            $ret = array_values( $ret );
            return $ret;
        }
        */
        private static function ValidateUsername( $username ) {
            static $reserved = array(
                'anonymous',
                'www',
                'alpha',
                'beta',
                'store',
                'radio',
                'iphone',
                'universe',
                'images',
                'images2',
                'static',
                'api',
                'developers'
            );
            return ( bool )preg_match( '#^[a-zA-Z][a-zA-Z\-_0-9]{3,19}$#', $username ) && !in_array( $username , $reserved );
        }
        private static function DeriveSubdomain( $username ) {
            /* RFC 1034 - They must start with a letter, 
            end with a letter or digit,
            and have as interior characters only letters, digits, and hyphen.
            Labels must be 63 characters or less. */
            $username = strtolower( $username );
            $username = preg_replace( '/([^a-z0-9-])/i', '-', $username ); //convert invalid chars to hyphens
            $pattern = '/([a-z]+)([a-z0-9-]*)([a-z0-9]+)/i';
            if ( !preg_match( $pattern, $username, $matches ) ) {
                return false;
            }
            return $matches[ 0 ];
        }
        public static function Create( $name, $email, $password ) {
            $password = md5( $password );
            $subdomain = self::DeriveSubdomain( $name );
            if ( !self::ValidateUsername( $name ) ) {
                throw New Exception( "invalid username" );
            }
            if ( $subdomain === false ) {
                // could not derive a subdomain
                return false;
            }

            $success = db( 'INSERT INTO `users`
                 ( `user_name`, `user_password`, `user_subdomain`, `user_rights`, `user_created` )
                 VALUES ( :name, :password, :subdomain, 30, NOW() )',
                 compact( 'name', 'password', 'subdomain' ) );

            if ( !mysql_affected_rows() ) {
                throw New Exception( "username taken" );
            }

            $id = mysql_insert_id();

            $success = db( 'INSERT INTO `userprofiles` 
                ( `profile_userid`, `profile_email`, `profile_updated` )
                VALUES ( :id, :email, NOW() );',
                compact( 'id', 'email' )
            );

            User::RenewAuthtoken( $id );
            
            // TODO: Send welcome e-mail
            return compact( 'id', 'name', 'email', 'subdomain', 'password' );
        }
        // only for TESTING
        public static function Delete( $id ) {
            return db( "DELETE FROM `users` WHERE `user_id` = :id LIMIT 1;", compact( 'id' ) );
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
    /*class Settings {
        public static function Update( $userid, $emailnotif ) {
            is_int( $userid ) or die;
            $emailnotif = $emailnotif ? "yes" : "no";
            $res = db( 
                "UPDATE 
                    `usersettings` 
                SET 
                    `setting_emailprofilecomment` = '$emailnotif', 
                    `setting_emailphotocomment` = '$emailnotif',
                    `setting_emailphototag` = '$emailnotif',
                    `setting_emailjournalcomment` = '$emailnotif',
                    `setting_emailpollcomment` = '$emailnotif',
                    `setting_emailreply` = '$emailnotif',
                    `setting_emailfriendaddition` = '$emailnotif',
                    `setting_emailfriendjournal` = '$emailnotif',
                    `setting_emailfriendpoll` = '$emailnotif',
                    `setting_emailfriendphoto` = '$emailnotif',
                    `setting_emailfavourite` = '$emailnotif',
                    `setting_emailbirthday` = '$emailnotif'
                WHERE
                    `setting_userid` = :userid
                LIMIT 1;", compact( 'userid' ) 
            );
            return $res;
        }
        public static function Get( $userid ) {
            is_int( $userid ) or die;
            $res = db( 
                "SELECT 
                    `setting_emailprofilecomment`, `setting_notifyprofilecomment` 
                FROM 
                    `usersettings` 
                WHERE 
                    `setting_userid` = :userid
                LIMIT 1;", compact( 'userid' )
            );
            $row = mysql_fetch_array( $res );
            return array( $row[ 'setting_notifyprofilecomment' ] == 'yes', $row[ 'setting_emailprofilecomment' ] == 'yes' );
        }
    }*/

    /* copeid from phoenix */
    function ValidEmail( $email ) {
        // Partially incorrect:
        // * Will allow some invalid domain names such as domains containing same-label siblings.
        // * Won't allow the ' character in usernames, which can be valid
        //
        // If you need absolutely correct validation, use your own manual string manipulation
        //

        return ( bool )preg_match( '/^              # start of string
            [a-z0-9%_+.-]+                          # username (can contain any of a-z, 0-9, and the symbols %, _, +, . and -
            @                                       # @ symbol at the middle of the e-mail address
            (                                       # after-@-symbol part; can be either a domain name...
                (                                   # domain node (parts between the dots)
                    [a-z0-9]                        # must start with a letter or number (not a dash)
                    [a-z0-9-]{0,62}                 # must be at most 63 characters long, and at least 1
                    (?<!-)                          # cannot end in a dash
                    \.                              # each part is separated from the next with a dot                        
                )*                                  # can have any number of domain nodes (even zero if this is a
                                                    # top-level domain such as in admin@edu)
                (                                   # top-level domain
                    [a-z]{2,4}                      # country domain such as .gr, .de, .nl;
                                                    # special cases such as .aero, .com, .edu;
                    |museum                         # and the special "museum" case 
                )
            |                                       # ...or an IP address
                (([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))\.){3} # (0-255).(0-255).(0-255).
                ([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))        # (0-255)
                (?<!0\.0\.0\.0)                                              # ...but it cannot be 0.0.0.0
            )
            $                                       # end of string
                                    /ix', $email );
    }
?>
