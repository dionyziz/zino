<?php
    class ElementUserSettingsInterests extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            global $libs;
            
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
            ?><b>Πληκτρολόγησε κάθε ενδιαφέρον σου ξεχωριστά και πίεσε Enter</b>
			<div class="option">
                <label>Hobbies:</label>
                <div class="setting">
                    <ul class="interesttags hobbies"><?php
                        foreach ( $hobbies as $hobbie ) {
                            ?><li>
                                <div class="aplbubble">
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $hobbie->Text );
                                    ?></span>
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $hobbie->Id;
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add hobbies">
                        <input type="text" onclick="$( 'div.hobbies ul' ).show();" onkeyup="Suggest.inputMove( event, 'hobbies' );" onblur="Suggest.hideBlur( 'hobbies' );" onfocus="$( 'div.hobbies ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.hobbies = true;" onmouseout="Suggest.over.hobbies = false;">
                            <li></li>
                        </ul>
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
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add songs">
                        <input type="text" onclick="$( 'div.songs ul' ).show();" onkeyup="Suggest.inputMove( event, 'songs' );" onblur="Suggest.hideBlur( 'songs' );" onfocus="$( 'div.songs ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.songs = true;" onmouseout="Suggest.over.songs = false;">
                            <li></li>
                        </ul>
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
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add movies">
                        <input type="text" onclick="$( 'div.movies ul' ).show();" onkeyup="Suggest.inputMove( event, 'movies' );" onblur="Suggest.hideBlur( 'movies' );" onfocus="$( 'div.movies ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.movies = true;" onmouseout="Suggest.over.movies = false;">
                            <li></li>
                        </ul>
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
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $show->Text );
                                    ?></span>
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $show->Id;
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add shows">
						<input type="text" onclick="$( 'div.shows ul' ).show();" onkeyup="Suggest.inputMove( event, 'shows' );" onblur="Suggest.hideBlur( 'shows' );" onfocus="$( 'div.shows ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.shows = true;" onmouseout="Suggest.over.shows = false;">
                            <li></li>
                        </ul>
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
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $book->Text );
                                    ?></span>
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $book->Id;
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add books">
						<input type="text" onclick="$( 'div.books ul' ).show();" onkeyup="Suggest.inputMove( event, 'books' );" onblur="Suggest.hideBlur( 'books' );" onfocus="$( 'div.books ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.books = true;" onmouseout="Suggest.over.books = false;">
                            <li></li>
                        </ul>
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
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $artist->Text );
                                    ?></span>
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $artist->Id;
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add artists">
                        <input type="text" onclick="$( 'div.artists ul' ).show();" onkeyup="Suggest.inputMove( 'artists' );" onblur="Suggest.hideBlur( event, 'artists' );" onfocus="$( 'div.artists ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.artists = true;" onmouseout="Suggest.over.artists = false;">
                            <li></li>
                        </ul>
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
									<span class="aplbubbleleft">&nbsp;</span>
                                    <span class="aplbubblemiddle"><?php
                                    echo htmlspecialchars( $game->Text );
                                    ?></span>
									<a href="" onclick="Settings.RemoveInterest( '<?php
                                    echo $game->Id;
                                    ?>' , this );return false" class="delete">&nbsp;</a>
                                    <span class="aplbubbleright">&nbsp;</span>
                                </div>
                            </li><?php
                        }
                    ?></ul>
                    <div class="add games">
                        <input type="text" onclick="$( 'div.games ul' ).show();" onkeyup="Suggest.inputMove( event, 'games' );" onblur="Suggest.hideBlur( 'games' );" onfocus="$( 'div.games ul').show();"/>
                        <a href="" onclick="return false" title="Προσθήκη"></a><br />
                        <ul onmouseover="Suggest.over.games = true;" onmouseout="Suggest.over.games = false;">
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="aplbubble creation">
				<span class="aplbubbleleft">&nbsp;</span>
				<span class="aplbubblemiddle"></span>
				<a href="" class="delete">&nbsp;</a>
				<span class="aplbubbleright">&nbsp;</span>
            </div><?php
        }
    }
?>
