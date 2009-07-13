<?php
    class ElementApplicationList extends Element {
    
        public function Render() {
            global $libs;
            global $user;
            
            $libs->Load( 'application' );
            
            $testers = Array(
                                4499, //ch0rvus-beta
                                5104, //chorvus-live
                            );
            
            if ( !in_array( $user->Id, $testers ) ) {
                ?>Δεν έχεις πρόσβαση σε αυτήν την σελίδα.<?
            }
            else {
                ?><div id="appmanager">
                    <ul id="applist"><?php
                    $finder = New ApplicationFinder();
                    $apps = $finder->FindByUser( $user );
                    if ( $apps ) {
                        foreach ( $apps as $app ) {
                            Element( 'application/view', $app );
                        }
                    }
                    else {
                        ?>Δεν έχεις καταχωρήσει καμία εφαρμογή. Μπορείς να το κάνεις <a href="/?p=newapp">εδώ</a><?php
                    }
                    ?></ul>
                </div><?php
            }
        }
    }
?>