<?php
    class ChatVideo extends Satori {
        protected $mDbTableAlias = 'chatvideo';

        protected function Relations() {
            $this->mUser = $this->HasOne( 'User', 'Userid' );
        }
    }

    class ChatVideoFinder extends Finder {
        protected $mModel = 'ChatVideo';

        public function FindByChannelId( $channelid ) {
            $query = $this->mDb->Prepare(
                "SELECT
                    video_authtoken, user_id, user_name
                FROM
                    :chatvideos CROSS JOIN :users
                        ON video_userid = user_id
                WHERE
                    video_channelid = :channelid;"
            );
            $query->BindTable( 'chatvideos', 'users' );
            $query->Bind( 'channelid', $channelid );
            $res = $query->Execute();

            return $this->FindBySqlResource( $res );
        }
    }

    function Chat_GenerateVidAuhtoken() {
        static $digits = array();

        if ( empty( $digits ) ) {
            for ( $i = 0; $i < 10; ++$i ) {
                $digits[] = $i;
            }
            for ( $c = 'a'; $c <= 'z'; $c = chr( ord( $c ) + 1 ) ) {
                $digits[] = $c;
            }
        }

        $vidauthtoken = '';
        for ( $i = 0; $i < 7; ++$i ) {
            $vidauthtoken .= $digits[ rand( 0, 35 ) ];
        }
        return $vidauthtoken;
    }
?>
