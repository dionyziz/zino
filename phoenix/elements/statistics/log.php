<?php

    class ElementStatisticsLog extends Element {
        public function Render( $masterelementid ) {
            global $libs;
            global $user;

            $libs->Load( 'pageview' );

            if ( $user->Exists() ) {
                return false;
            }

            if ( isset( $_SESSION[ 'log' ] ) && $_SESSION[ 'log' ] == false ) {
                return false;
            }
            else {
                $decision = Pageview_LogVisitorOrNot();
                if ( $decision === false ) {
                    return false;
                }
            }

            $pageview = New Pageview();
            $pageview->Element = $masterelementid;
            $pageview->Sessionid = session_id();
            $pageview->Save();
        }
    }

?>
