<?php
	class Ban {
		public static function ItemByUserid( $userid ) {
			$res = db_array(
				'SELECT
					`bannedusers_id` as id, `bannedusers_userid` as userid, `bannedusers_rights` as rights, `bannedusers_started` as started, `bannedusers_expire` as expire, `bannedusers_delalbums` as delalbums,	`bannedusers_reason` as reason, `bannedusers_admin` as admin
				FROM 
					`bannedusers`
				WHERE
					`bannedusers_userid` = :userid
				LIMIT 1', compact( 'userid' ) 
			);
			if ( empty( $res ) ) {
				return false;
			}
			else {
				return array_shift( $res );
			}
		}
		public static function Listing() {
			return db_array(
				'SELECT
					`user_name` as name, `bannedusers_id` as id, `bannedusers_userid` as userid, `bannedusers_rights` as rights, `bannedusers_started` as started, `bannedusers_expire` as expire, `bannedusers_delalbums` as delalbums, `bannedusers_reason` as reason, `bannedusers_admin` as admin
				FROM 
					`bannedusers`
				LEFT JOIN
					`users`
				ON
					`user_id` = `bannedusers_userid`
				ORDER BY `bannedusers_started` DESC'
				
			);
		}
		public static function isBannedUser( $userid ) {
			clude( 'models/date.php' );
			$userid = ( int )$userid;
			$banned = Ban::ItemByUserid( $userid );
			if ( $banned === false ) {
				return false;
			}
			else {
                $diff = strtotime( NowDate() ) - strtotime( $banned[ 'expire' ] );
                
                if ( $diff > 0 ) {
                    Ban::Revoke( $banned[ 'userid' ] );
                    return false;
                }
                else {
                    return true;
                }
            }
        }

		public static function Revoke( $userid ) {
			clude( 'models/user.php' );   
			$userid = ( int )$userid;
            $banned = Ban::ItemByUserid( $userid );
			if ( $banned === false ) {
				throw new Exception( "Ban::Revoke - User is not banned" );
			}
			else {
				Ban::Delete( $banned[ 'id' ] );
				User::SetRights( $userid, $banned[ 'rights' ] );
            }
            return;
        }

		public static function Delete( $id ) {
			$id = ( int )$id;
			db( 'DELETE
                 FROM
                    `bannedusers`
                 WHERE
                    `bannedusers_id` = :id
                 LIMIT 1', compact( 'id' ) );    
		}

		public static function Create( $userid, $reason, $time_banned, $oldrights ) {
			$time_banned = ( int )$time_banned;//secs
			$userid = ( int )$userid;
			$reason = ( string )$reason;
			$oldrights = ( int )$oldrights;
			if ( $time_banned <= 0 ) {
				throw New Exception( "Ban::Create - time to be banned should be a positive value" );
			}
			$banned = Ban::ItemByUserid( $userid );
			if ( $banned !== false ) {
				throw New Exception( "Ban::Create - This user is already banned" );
			}	
			$started = date( 'Y-m-d H:i:s', time() );
			$expire = date( 'Y-m-d H:i:s', time() + $time_banned );
			$adminname = $_SESSION[ 'user' ][ 'name' ];
			
			$success = db( 'INSERT INTO `bannedusers` 
                ( `bannedusers_userid`, `bannedusers_rights`, `bannedusers_started`, `bannedusers_expire`, `bannedusers_delalbums`,	`bannedusers_reason`, `bannedusers_admin` )
                VALUES ( :userid, :oldrights, :started, :expire, 0, :reason, :adminname );',
                compact( 'userid', 'oldrights', 'started', 'expire', 'reason', 'adminname' )
            );         
            return;        
        }
    }
?>
