<?php
    class ElementPollSmall extends Element {
        public function Render( $poll , $showcommnum = false ) {
            global $user;
            global $xc_settings;
            
            $finder = New PollVoteFinder();
            $showresults = $finder->FindByPollAndUser( $poll, $user );
            //used to show results, will be true if the user has voted or is anonymous
            $domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
            $url = $domain . 'polls/' . $poll->Url;
            ?><div class="posmall">
                <h4><a href="<?php
                Element( 'url', $poll ); 
                ?>"><?php
                echo htmlspecialchars( $poll->Question );
                ?></a></h4>
                <div class="results"><?php
                Element( 'poll/result/view', $poll, $showresults );
                if ( $showcommnum && $poll->Numcomments > 0 ) {
                    ?><dl class="<?php
                    if ( $showresults ) {
                        ?>pollinfo<?php
                    }
                    else {
                        ?>pollinfo2<?php
                    }
                    ?>">
                        <dd><a href="<?php
                        echo $url;
                        ?>"><span>&nbsp;</span><?php
                        echo $poll->Numcomments;
                        ?> σχόλι<?php
                        if ( $poll->Numcomments == 1 ) {
                            ?>ο<?php
                        }
                        else { 
                            ?>α<?php
                        }
                        ?></a></dd>
                    </dl><?php
                }
                Element( 'poll/vote' );
                ?></div>
            </div><?php
        }
    }
?>
