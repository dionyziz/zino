<?php

    /*
    Pageview class for statistics
    Log pageviews for 1% of anonymous users
    Hopefully this will help us find out why we have such a huge bounce rate

    Developer: abresas
    */

    class PageviewFinder extends Finder {
        protected $mModel = 'Pageview';

        // returns number of bounces by page and total number of bounces
        public function FindTopBounces( $maxelements = false ) {
            $query = $this->mDb->Prepare( 
                'SELECT
                    pageview_element, COUNT(*) AS bounces
                FROM
                    ( SELECT
                        pageview_element, pageview_sessionid, COUNT(*) AS sessioncount
                    FROM
                        :pageviews
                    GROUP BY
                        pageview_sessionid ) AS sessioned
                WHERE
                    sessioned.sessioncount = 1
                GROUP BY
                    pageview_element
                ORDER BY
                    bounces DESC;'
            );

            $query->BindTable( 'pageviews' );
            $res = $query->Execute();
            $totalbounces = 0;
            $bouncesByElement = array();
            while ( $row = $res->FetchArray() ) {
                $bounces = $row[ 'bounces' ];
                $totalbounces += $bounces;
                if ( $maxelements !== false ) {
                    if ( $maxelements == 0 ) {
                        continue; // do not return any other element
                    }
                    --$maxelements;
                }
                else {
                    // maxelements is false (default), return all elements
                }
                $bouncesByElement[ $row[ 'pageview_element' ] ] = $bounces;
            }

            return array( $bouncesByElement, $totalbounces );
        }
    }
    
    class Pageview extends Satori {
        protected $mDbTableAlias = 'pageviews';
        protected $mLog = false;

        public function OnConstruct() {
            global $user;

            if ( $this->Exists() ) {
                return;
            }

            // new pageview

            if ( $user->Exists() ) { // do not log
                return;
            }
            if ( !isset( $_SESSION[ 'log' ] ) ) { // not yet decided if we will keep logs or not
                $this->mLog = rand( 0, 1 ) == 0; // 1% possibility to keep logs
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
