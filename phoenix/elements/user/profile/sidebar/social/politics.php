<?php
    class ElementUserProfileSidebarSocialPolitics extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Politics != '-' ) {
                /*
                if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
                    $politics = array( 
                        'right' => 'Δεξιός',
                        'left' => 'Αριστερός',
                        'center' => 'Κεντρώος',
                        'radical left' => 'Ακροαριστερός',
                        'radical right' => 'Ακροδεξιός',
                        'nothing' => 'Τίποτα'
                    );
                }
                else {
                    $politics = array( 
                        'right' => 'Δεξιά',
                        'left' => 'Αριστερή',
                        'center' => 'Κεντρώα',
                        'radical left' => 'Ακροαριστερή',
                        'radical right' => 'Ακροδεξιά',
                        'nothing' => 'Τίποτα'
                    );
                }
                */
                ?><li><strong>Πολιτική ιδεολογία</strong>
                <?php
                Element( 'user/trivial/politics', $theuser->Profile->Politics, $theuser->Gender );
                ?></li><?php
            }
        }
    }
?>
