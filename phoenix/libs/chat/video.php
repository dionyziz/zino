<?php
    class ChatVideo extends Satori {
        protected $mDbTableAlias = 'chatvideo';

        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function RenewAuthtoken() {
            $this->Authtoken = Chat_GenerateVidAuthtoken();
        }
        protected function OnBeforeCreate() {
            $this->RenewAuthtoken();
        }
    }

    class ChatVideoFinder extends Finder {
        protected $mModel = 'ChatVideo';

        public function FindByChannelId( $channelid ) {
            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :chatvideo CROSS JOIN :users
                        ON video_userid = user_id
                WHERE
                    video_channelid = :channelid;"
            );
            $query->BindTable( 'chatvideo', 'users' );
            $query->Bind( 'channelid', $channelid );
            $res = $query->Execute();

            return $this->FindBySqlResource( $res );
        }
    }

    function Chat_GenerateVidAuthtoken() {
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
