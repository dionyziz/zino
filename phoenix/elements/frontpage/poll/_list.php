<?php
    
    /* 
    Masked by: abresas
    Reason: spot
    */

    class ElementFrontpagePollList extends Element {
        // protected $mPersistent = array( 'pollseq' );

        public function Render( $pollseq ) {
            global $libs;
            global $xc_settings;
            global $user;

            $libs->Load( 'poll/poll' );
            $finder = New PollFinder();
            if ( $user->Exists() ) {
                $polls = $finder->FindUserRelated( $user );
            }
            else { // no spot for anonymous
                $libs->Load( 'poll/frontpage' );
                $polls = $finder->FindFrontpageLatest( 0 , 4 );
            }
            
            ?><div class="list">
                <h2>Δημοσκοπήσεις (<a href="polls">προβολή όλων</a>)</h2><?php
                foreach ( $polls as $poll ) {
                    $domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                    $url = $domain . 'polls/' . $poll->Url;
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display' , $poll->Userid , $poll->User->Avatarid , $poll->User, true );
                        ?> καταχώρησε
                        </div>
                        <div class="subject">
                            <a href="<?php
                            echo $url;
                            ?>"><?php
                            echo htmlspecialchars( $poll->Question );
                            ?></a>
                        </div>
                    </div><?php
                }
            ?></div><?php
        }
    }
?>
