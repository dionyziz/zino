<?php
    class ElementUserProfileMainTweeter extends Element {
        public function Render( $userid, $name, $gender ) {
            global $user;
            global $libs;

            $libs->Load( 'user/statusbox' );

            $finder = New StatusBoxFinder();
            $tweet = $finder->FindLastByUserId( $userid );
            if ( $tweet !== false || $userid == $user->Id ) {
                ?>
                <div class="tweetbox<?php
                    if ( $userid == $user->Id ) {
                        ?> tweetactive<?php
                        if ( $tweet === false ) {
                            ?> tweetblind<?php
                        }
                    }
                    ?>"<?php
                    if ( $userid == $user->Id ) {
                        ?> title="Άλλαξε το μήνυμα του &quot;τι κάνεις τώρα;&quot;"<?php
                    }
                    ?>>
                    <i class="right corner">&nbsp;</i>
                    <i class="left corner">&nbsp;</i>
                    <div class="tweet">
                        <div><?php
                        if ( $userid == $user->Id ) {
                            ?><a href=""><?php
                        }
                        if ( $gender == 'f' ) {
                            ?>Η <?php
                        }
						else if ( $userid == 872 ) {
							?>Το <?php
						}
                        else {
                            ?>Ο <?php
                        }
                        echo htmlspecialchars( $name );
                        ?> <span><?php
                        if ( $tweet !== false ) {
                            echo htmlspecialchars( $tweet->Message );
                        }
                        else {
                            ?><i>τι κάνεις τώρα;</i><?php
                        }
                        ?></span><?php
                        if ( $userid == $user->Id ) {
                            ?></a><?php
                        }
                        ?></div>
                    </div>
                </div><?php
                if ( $userid == $user->Id ) {
                    ?><div id="tweetedit">
                        <h3 class="modaltitle">Τι κάνεις τώρα;</h3>
                        <form>
                            <div class="input"><?php
                                if ( $gender == 'f' ) {
                                    ?>Η <?php
                                }
                                else {
                                    ?>Ο <?php
                                }
                                echo htmlspecialchars( $name );
                                ?> <input type="text" value="<?php
                                echo htmlspecialchars( $tweet->Message );
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
