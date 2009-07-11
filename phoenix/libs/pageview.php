<?php

    /*
    Pageview class for statistics
    Log pageviews for 1% of anonymous users
    Hopefully this will help us find out why we have such a huge bounce rate

    Developer: abresas
    */

    class PageviewFinder extends Finder {
        protected $mModel = 'Pageview';

        public function FindBounceRates() {
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
                    bounces DESC'
            );

            $query->BindTable( 'pageviews' );

            $res = $query->Execute();
            $bouncesByElement = array();
            while ( $row = $res->FetchArray() ) {
                $bouncesByElement[ $row[ 'pageview_element' ] ] = $row[ 'bounces' ];
            }

            $elements = array_keys( $bouncesByElement );
            $landingsByElement = $this->FindLandingsByElement( $elements );
            $bounceRates = array();
            foreach ( $landingsByElement as $element => $landings ) {
                $bounces = $bouncesByElement[ $element ];
                $bouncerates[ $element ] = $bounces / $landings;
            }

            return array( $bouncerates, $bouncesByElement, $landingsByElement );
        }
        public function FindLandingsByElement( $elements ) {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( a.pageview_id ) AS c, a.pageview_element AS element
                FROM
                    :pageviews AS a
                    LEFT JOIN :pageviews AS b
                        ON a.pageview_id > b.pageview_id AND a.pageview_sessionid = b.pageview_sessionid
                WHERE
                    a.pageview_element IN :elements AND
                    b.pageview_id IS NULL
                GROUP BY
                    a.pageview_element
                LIMIT
                    :maxelements;'
            );
            $query->BindTable( 'pageviews' );
            $query->Bind( 'elements', $elements );
            $query->Bind( 'maxelements', count( $elements ) );

            return $query->Execute()->MakeArray(); 
        }
    }
    
    function Pageview_LogVisitorOrNot() {
        //$this->mLog = rand( 0, 99 ) == 0; // 1% possibility to keep logs
        return rand( 0, 99 ) == 0;
    }

    class Pageview extends Satori {
        protected $mDbTableAlias = 'pageviews';

        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Userip = UserIp(); 
        }
    }

?>
