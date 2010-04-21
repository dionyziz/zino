<?php
    class VideoStream{
        public static function Create( $id, $stratusid ){
            db( 'INSERT INTO `videostream`
                    (`videostream_userid`, `videostream_stratusid`)
                VALUES
                    (:id, :stratusid );', compact( 'id', 'stratusid' ) );
                    
        }
        public static function Retrieve( $userid, $targetuserid ){
            db( 'SELECT
                    `videostream_stratusid` AS stratusid, `videostream_token` AS token
                 FROM
                    `videostream`
                 WHERE
                    `videostream_targetuserid` = :userid AND 
                    `videostream_userid` = :targetuserid
                 LIMIT 1;', compact( 'userid', 'targetuserid' ) );

        }
        public static function GrantPermission( $userid, $targetuserid ){
            $token = $this->GenerateToken;
            db( 'UPDATE `videostream`
                 SET
                    `videostream_targetuserid` = :targetuserid,
                    `videostream_token` = :token
                 WHERE
                    `videostream_userid` = :userid
                 LIMIT 1', compact( 'userid', 'targetuserid', 'token' ) );
            return $token;
        }
        public static function GenerateToken(){
            $authtoken = '';
            for ( $i = 0; $i < 7; ++$i ) {
                $authtoken .= dechex( rand( 0, 15 ) );
            }
            return $authtoken;
        }
    }


?>
