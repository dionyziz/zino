<?php
    class ElementUserJoined extends Element {
        public function Render() {
            global $user;
            global $rabbit_settings;
            global $page;
            
            $page->SetTitle( 'Καλωσήρθες' );

            if ( !$user->Exists() ) {
                return Redirect( $rabbit_settings[ 'webaddress' ] );
            }
            ?><div id="joined">
                <div class="ybubble">
                    <div class="body">
                        <div class="welcome">
                            <div>Συγχαρητήρια! Mόλις δημιουργήσες λογαριασμό στο <?php
                            echo $rabbit_settings[ 'applicationname' ];
                            ?>.<br />
                            To προφίλ σου είναι <b><?php
                            Element( 'user/url' , $user );
                            ?></b>.</div>
                            <i class="bl"></i>
                            <i class="br"></i>
                        </div>
                    </div>
                </div>
                <div class="profinfo">
                    <p>
                    Συμπλήρωσε μερικές λεπτομέρειες για τον εαυτό σου.<br />Αν δεν θες να το κάνεις τώρα,
                    μπορείς αργότερα από το προφίλ σου.
                    </p>
                    <form>
                        <div>
                            <span>Ημερομηνία γέννησης:</span><?php
                            Element( 'user/settings/personal/dob' );
                        ?></div>
                        <div>
                            <span>Φύλο:</span><?php
                            Element( 'user/settings/personal/gender' , $user->Gender );
                        ?></div>
                        <div>
                            <span>Περιοχή:</span><?php
                            Element( 'user/settings/personal/place' , $user->Profile->Location->Id );
                        ?></div>
                    </form>
					<span class="invaliddob"><span>&nbsp;</span>Η ημερομηνία δεν είναι έγκυρη</span>
                </div>
                <div style="text-align:center;">
                    <a href="" class="button button_big">Συνέχεια &raquo;</a>
                </div>
            </div><?php
        }
    }
?>
