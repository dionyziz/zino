<?php
    class ElementiPhoneUserList extends Element {
        public function Render( Array $theusers ) {
            global $xc_settings;

            ?><ul><?php
            foreach ( $theusers as $theuser ) {
                w_assert( $theuser instanceof User );
                ?><li><div class="card"><a href="<?php
                    echo $xc_settings[ 'iphoneurl' ];
                    ?>?p=user&amp;subdomain=<?php
                    echo $shout->User->Subdomain;
                    ?>">
                    <span class="who"><?php
                    Element( 'user/avatar', $theuser->Avatar->Id, $theuser->Id,
                                 $theuser->Avatar->Width, $theuser->Avatar->Height,
                                 $theuser->Name, 100, 'avatar', '', true, 50, 50 );
                    ?></span>
                    <span class="text"><strong><?php
                        Element( 'user/name' , $theuser->Id, $theuser->Name, $theuser->Subdomain, false );
                        ?></strong><br />
                        <?php
                        $info = array();
                        if ( $theuser->Profile->Gender == 'm' ) {
                            $info[] = 'Αγόρι';
                        }
                        elseif ( $theuser->Profile->Gender == 'f' ) {
                            $info[] = 'Κοπέλα';
                        }
                        if ( $theuser->Profile->Age ) {
                            $info[] = $theuser->Profile->Age . ' ετών';
                        }
                        if ( $theuser->Profile->Placeid > 0 ) {
                            $info[] = 'από ' . $theuser->Profile->Location->Name;
                        }
                        echo htmlspecialchars( implode( ', ', $info ) );
                    ?></span>
                </a></div></li><?php
            }
            ?></ul><?php
        }
    }
?>
