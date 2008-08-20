<?php
    class ElementPollSmall extends Element {
        public function Render( $poll , $showcommnum = false ) {
            global $user;
            global $rabbit_settings; 
            global $water;
            
            $finder = New PollVoteFinder();
            $showresults = $finder->FindByPollAndUser( $poll, $user );
            //used to show results, will be true if the user has voted or is anonymous
            ?><div class="pollsmall">
                <h4><a href="<?php
                    ?>?p=poll&amp;id=<?php
                    echo $poll->Id;
                ?>"><?php
                echo htmlspecialchars( $poll->Question );
                ?></a></h4>
                <div class="results"><?php
                Element( 'poll/result/view', $poll );
                if ( $showcommnum ) {
                    if ( $poll->Numcomments > 0 ) {
                        ?><dl class="<?php
                        if ( $showresults ) {
                            ?>pollinfo<?php
                        }
                        else {
                            ?>pollinfo2<?php
                        }
                        ?>">
                            <dd><a href="?p=poll&amp;id=<?php
                            echo $poll->Id;
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
                }
                ?></div>
                <div class="voting"><img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>ajax-loader.gif" alt="Παρακαλώ περιμένετε..." title="Παρακαλώ περιμένετε..." /> Παρακαλώ περιμένετε...
                </div>
            </div><?php
        }
    }
?>
