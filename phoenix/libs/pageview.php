<?php

    /*
    Pageview class for statistics
    Log pageviews for 1% of anonymous users
    Hopefully this will help us find out why we have such a huge bounce rate

    Developer: abresas
    */
     

    class Pageview extends Satori {
        protected $mTableAlias = 'pageview';
        protected $mLog = false;

        public function OnConstruct() {
            if ( $this->Exists() ) {
                return;
            }

            // new pageview

            if ( $user->Exists() ) { // do not log
                return;
            }
            if ( !isset( $_SESSION[ 'log' ] ) ) { // not yet decided if we will keep logs or not
                $this->mLog = rand( 0, 99 ) == 0; // 1% possibility to keep logs
                $_SESSION[ 'log' ] = $this->mLog;
            }
            else {
                $this->mLog = $_SESSION[ 'log' ];
            }
        }
        public function OnBeforeCreate() {
            return $this->mLog;
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Userip = UserIp(); 
            $this->Sessionid = session_id();
        }
    }

?>
