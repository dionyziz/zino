<?php

    /*
    Pageview class for statistics
    Log pageviews for 1% of anonymous users
    Hopefully this will help us find out why we have such a huge bounce rate

    Developer: abresas
    */

    class PageviewFinder extends Satori {
        protected $mModel = 'Pageview';

        // returns number of bounces by page and
        // sets $totalbounces parameter to total number of bounces
        public function FindTopBounces( &$totalbounces, $maxurls = false ) {
            $query = $this->mDb->Prepare( 
                'SELECT
                    pageview_url, COUNT(*) AS bounces
                FROM
                    ( SELECT
                        pageview_url, pageview_sessionid, COUNT(*) AS sessioncount
                    FROM
                        :pageviews
                    GROUP BY
                        pageview_url, pageview_sessionid ) AS sessioned
                WHERE
                    sessioned.sessioncount = 1
                GROUP BY
                    pageview_url
                ORDER BY
                    bounces DESC;'
            );

            $query->BindTable( 'pageviews' );
            $res = $query->Execute();
            $totalbounces = 0;
            $bouncesByUrl = array();
            while ( $row = $res->FetchArray() ) {
                $bounces = $row[ 'bounces' ];
                $totalbounces += $bounces;
                if ( $maxurls !== false ) {
                    if ( $maxurls == 0 ) {
                        continue; // do not return any other url
                    }
                    --$maxurls;
                }
                else {
                    // maxurls is false (default), return all urls
                }
                $bouncesByUrl[ $row[ 'pageview_url' ] ] = $bounces;
            }

            return $bouncesByUrl;
        }
    }
    
    class Pageview extends Satori {
        protected $mDbTableAlias = 'pageview';
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
            $this->Url = $_SERVER[ 'SCRIPT_URL' ];
            $this->Created = NowDate();
            $this->Userip = UserIp(); 
            $this->Sessionid = session_id();
        }
    }

?>
