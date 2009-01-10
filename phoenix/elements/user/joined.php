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
                            Element( 'user/url' , $user->Id , $user->Subdomain );
                            ?></b></div>
                            <i class="bl"></i>
                            <i class="br"></i>
                        </div>
                    </div>
                </div>
                <div class="profinfo">
                    <p>
                    Συμπλήρωσε μερικές λεπτομέρειες για τον εαυτό σου
                    </p>
                    <form>
                        <div>
                            <span>Ημερομηνία γέννησης:</span><?php
                            Element( 'user/settings/personal/dob' );
                        ?><span class="invaliddob"><span class="s_invalid">&nbsp;</span>Η ημερομηνία δεν είναι έγκυρη</span>
						</div>
                        <div>
                            <span>Φύλο:</span><?php
                            Element( 'user/settings/personal/gender' , $user->Gender );
                        ?></div>
                        <div>
                            <span>Περιοχή:</span><?php
                            Element( 'user/settings/personal/place' , $user->Profile->Location->Id );
                        ?></div>
                    </form>
                </div>
                <div style="text-align:center">
                    <a href="" class="button button_big">Συνέχεια &raquo;</a>
                </div>
            </div><?php
            $page->AttachInlineScript( 'Joined.JoinedOnLoad();' );
        }
    }
?>
