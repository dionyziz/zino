<?php
    class ElementUserSettingsInterests extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            global $libs;
            //----------------
            global $page;
            
            $page->AttachScript( 'js/user/suggest.js' );
            $page->AttachScript( 'js/user/_settings.js' );
            $page->AttachStyleSheet( 'css/user/settings.css' );
            //----------------
            
            $libs->Load( 'tag' );
            $finder = New TagFinder();
            $tags = $finder->FindByUser( $user );
            $bytype = array(
                TAG_HOBBIE => array(),
                TAG_MOVIE => array(),
                TAG_BOOK => array(),
                TAG_SONG => array(),
                TAG_ARTIST => array(),
                TAG_GAME => array(),
                TAG_SHOW => array()
            );
            foreach ( $tags as $tag ) {
                $bytype[ $tag->Typeid ][] = $tag;
            }
            
            $hobbies = $bytype[ TAG_HOBBIE ];
            $movies = $bytype[ TAG_MOVIE ];
            $books = $bytype[ TAG_BOOK ];
            $songs = $bytype[ TAG_SONG ];
            $artists = $bytype[ TAG_ARTIST ];
            $games = $bytype[ TAG_GAME ];
            $shows = $bytype[ TAG_SHOW ];
            
            // INTEREST_TAG_TYPE   Please Update the whole file when you add a new interest tag type
            ?><div class="option">
                <label>Hobbies:</label>
                <div class="setting">
                    <ul class="interesttags hobbies"><?php
                        foreach ( $hobbies as $hobbie ) {
                            ?><li>
                                <div class="aplbubble">
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $hobbie->Id;
                                    ?>' , this );return false;" class="delete">&nbsp;</a>
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $hobbie->Text );
                                    ?></span>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add hobbies">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'hobbies' );Suggest.fire( event, 'hobbies' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'hobbies' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένα τραγούδια:</label>
                <div class="setting">
                    <ul class="interesttags songs"><?php
                        foreach ( $songs as $song ) {
                            ?><li>
                                <div class="aplbubble">
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $song->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $song->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add songs">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'songs' );Suggest.fire( event, 'songs' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'songs' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένες ταινίες:</label>
                <div class="setting">
                    <ul class="interesttags movies"><?php
                        foreach ( $movies as $movie ) {
                            ?><li>
                                <div class="aplbubble">
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $movie->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $movie->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add movies">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'movies' );Suggest.fire( event, 'movies' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'movies' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένες σειρές:</label>
                <div class="setting">
                    <ul class="interesttags shows"><?php
                        foreach ( $shows as $show) {
                            ?><li>
                                <div class="aplbubble">
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_left.png" alt="" />
                                    <span style="margin-left:0.5px"><?php
                                    echo htmlspecialchars( $show->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $show->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_right.png" alt="" />
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add shows">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'shows' );Suggest.fire( event, 'shows' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'shows' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένα βιβλία:</label>
                <div class="setting">
                    <ul class="interesttags books"><?php
                        foreach ( $books as $book ) {
                            ?><li>
                                <div class="aplbubble">
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_left.png" alt="" />
                                    <span style="margin-left:0.5px"><?php
                                    echo htmlspecialchars( $book->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $book->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_right.png" alt="" />
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add books">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'books' );Suggest.fire( event, 'books' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'books' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένοι καλλιτέχνες:</label>
                <div class="setting">
                    <ul class="interesttags artists"><?php
                        foreach ( $artists as $artist ) {
                            ?><li>
                                <div class="aplbubble">
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_left.png" alt="" />
                                    <span style="margin-left:0.5px"><?php
                                    echo htmlspecialchars( $artist->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $artist->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_right.png" alt="" />
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add artists">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'artists' );Suggest.fire( event, 'artists' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'artists' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <div class="option">
                <label>Αγαπημένα παιχνίδια:</label>
                <div class="setting">
                    <ul class="interesttags games"><?php
                        foreach ( $games as $game ) {
                            ?><li>
                                <div class="aplbubble">
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_left.png" alt="" />
                                    <span style="margin-left:0.5px"><?php
                                    echo htmlspecialchars( $game->Text );
                                    ?></span>
                                    <a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $game->Id;
                                    ?>' , this );return false;" class="delete"><img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>delete.png" alt="" /></a>
                                    <img src="<?php
                                    echo $rabbit_settings[ 'imagesurl' ];
                                    ?>aplbubble_right.png" alt="" />
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add games">
                        <input type="text" onkeyup="Suggest.inputMove( event, 'games' );Suggest.fire( event, 'games' );"  />
                        <a href="" onclick="return false;"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>add.png" alt="Προσθήκη" title="Προσθήκη" /></a>
                        <form action="">
                            <select onkeypress="Suggest.selectMove( event, 'games' );"><option value=""/></select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="aplbubble creation">
                <img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>aplbubble_left.png" alt="" />
                <span></span>
                <a href="" style="display:none;" class="delete"><img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>delete.png" alt="" /></a>
                <img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>aplbubble_right.png" alt="" />
            </div><?php
        }
    }
?>
