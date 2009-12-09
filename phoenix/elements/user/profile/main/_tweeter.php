<?php
    class ElementUserProfileMainTweeter extends Element {
        public function Render( $theuser ) {
            global $user;
            global $libs;

            $libs->Load( 'user/statusbox' );

            $finder = New StatusBoxFinder();
            $tweet = $finder->FindLastByUserId( $theuser->Id );
            if ( $tweet !== false || $theuser->Id == $user->Id ) {
                ?><div class="tweetbox<?php
                    if ( $theuser->Id == $user->Id ) {
                        ?> tweetactive<?php
                        if ( $tweet === false ) {
                            ?> tweetblind<?php
                        }
                    }
                    ?>"<?php
                    if ( $theuser->Id == $user->Id ) {
                        ?> title="Άλλαξε το μήνυμα του &quot;τι κάνεις τώρα;&quot;"<?php
                    }
                    ?>>
					<i class="left corner">&nbsp;</i>
                    <div class="tweet">
                        <div><?php
                        if ( $theuser->Id == $user->Id ) {
                            ?><a href=""><?php
                        }
                        if ( $theuser->Gender == 'f' ) {
                            ?>Η <?php
                        }
						elseif ( $theuser->Id == 872 ) {
							?>Το <?php
						}
                        else {
                            ?>Ο <?php
                        }
                        echo htmlspecialchars( $theuser->Name );
                        ?> <span><?php
                        if ( $tweet !== false ) {
                            echo htmlspecialchars( $tweet->Message );
                        }
                        else {
                            ?><i>τι κάνεις τώρα;</i><?php
                        }
                        ?></span><?php
                        if ( $theuser->Id == $user->Id ) {
                            ?></a><?php
                        }
                        ?></div>
                    </div>
                    <i style="float:left;" class="right corner">&nbsp;</i>
                </div><?php
                if ( $theuser->Id == $user->Id ) {
                    ?><div id="tweetedit" class="modal">
                        <h3>Τι κάνεις τώρα;</h3>
                        <form>
                            <div class="input"><?php
                                if ( $theuser->Gender == 'f' ) {
                                    ?>Η <?php
                                }
                                else {
                                    ?>Ο <?php
                                }
                                echo htmlspecialchars( $theuser->Name );
                                ?> <input type="text" value="<?php
                                if ( $tweet !== false ) {
                                    echo htmlspecialchars( $tweet->Message );
                                }
                                ?>" />
                                <input type="submit" style="display:none" />
                            </div>
                            <div>
                                <ul>
                                    <li><a href="" class="button">Αποθήκευση</a></li>
                                    <li><a href="" class="button">Διαγραφή</a></li>
                                </ul>
                            </div>
                        </form>
                    </div><?php
                }
            }
        }
    }
?>
