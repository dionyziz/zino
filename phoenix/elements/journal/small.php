<?php
    
    class ElementJournalSmall extends Element {
        public function Render( $journal ) {
            global $user;
            global $libs;
            global $xc_settings;
            
            $libs->Load( 'favourite' );
            $finder = New FavouriteFinder();
            $fav = $finder->FindByUserAndEntity( $user, $journal );
            ?><div class="jsmall">
                <h4><a href="<?php
                Element( 'url', $journal );
                ?>"><?php
                echo htmlspecialchars( $journal->Title );
                ?></a></h4>
                <p><?php
                echo $journal->GetText( 300 );
                ?></p>
                <ul>
                    <li>
                        <dl><?php
                        if ( $journal->Numcomments > 0 ) {
                            ?><dt class="commentsnum"><a href="<?php
                            echo $url;
                            ?>"><span class="s_commnum">&nbsp;</span><?php
                            echo $journal->Numcomments;
                            ?> σχόλι<?php
                            if ( $journal->Numcomments == 1 ) {
                                ?>ο<?php
                            }
                            else {
                                ?>α<?php
                            }
                            ?></a></dt><?php
                        }
                        ?></dl>
                    </li>
                </ul>
            </div><?php
        }
    }
?>
