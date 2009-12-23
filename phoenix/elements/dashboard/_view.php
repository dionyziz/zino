<?php
    class ElementDashboardView extends Element {
        public function Render() {
            global $page;
            global $libs;
            global $user;

            $page->AttachStylesheet( 'css/default.css' );
            $page->AttachStylesheet( 'css/banner.css' );
            $page->AttachStylesheet( 'css/footer.css' );
            $page->AttachStylesheet( 'css/links.css' );
            $page->AttachStylesheet( 'css/emoticons.css' );
            $page->AttachStylesheet( 'css/spriting/sprite1.css' );
            $page->AttachStylesheet( 'css/spriting/sprite2.css' );
            $page->AttachStylesheet( 'css/spriting/spritex.css' );

            $page->AttachStylesheet( 'css/dashboard.css' );

            $page->AttachScript( 'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/coala.js' );
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/comet.js' );
            $page->AttachScript( 'js/dashboard.js' ); 

            ob_start();
            ?>Dashboard.OnLoad();<?php
            if ( $user->Exists() ) {
                ?>
                var User = "<?php
                echo $user->Name;
                ?>";
                <?php
            }
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew0' );
            Comet.Subscribe( 'FrontpageShoutboxTyping0' );<?php
            $page->AttachInlineScript( ob_get_clean() );

            $libs->Load( 'chat/message' );

            $finder = New ShoutboxFinder();
            $chats = $finder->FindByChannel( 0, 0, 20 );

            $messages = array();
            foreach ( $chats as $chat ) {
                array_unshift( $messages, array(
                    'id' => $chat->Id,
                    'username' => $chat->User->Name,
                    'html' => $chat->Text
                ) );
            }

			?>
            <div id="nowbar">
                <div class="border">
                    <div id="friends">
                        <a href="" title="Συνοπτική προβολή" class="collapse"></a>
                        <h2>Online τώρα</h2>
                        <div class="input">

                            <input type="text" value="Αναζήτηση φίλων" />
                        </div>
                        <ol>
                            <li>
                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>

                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/13078/225801/225801_100.jpg" alt="Celena" style="width:50px;height:50px" />
                                    </div>
                                    <span class="username">Celena</span>
                                    <span class="twit">
                                        περιμένει
                                    </span>
                                    <div class="overlay"></div>
                                </a>

                            </li>
                            <li>
                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/7295/225927/225927_100.jpg" alt="mar1no0bi" style="width:50px;height:50px" />

                                    </div>
                                    <span class="username">mar1no0bi</span>
                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/famous.jpg" class="mood" alt="Διάσημη" title="Διάσημη" />
                                        8elei doro cac st x-mac&lt;3
                                    </span>
                                    <div class="overlay"></div>
                                </a>

                            </li>
                            <li>
                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/58/223815/223815_100.jpg" alt="izual" style="width:50px;height:50px" />

                                    </div>
                                    <span class="username">izual</span>
                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/inlove.jpg" class="mood" alt="Ερωτευμένος" title="Ερωτευμένος" />
                                        einai stokos
                                    </span>
                                    <div class="overlay"></div>
                                </a>
                            </li>

                            <li>
                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/7895/225701/225701_100.jpg" alt="Nefeloumpa" style="width:50px;height:50px" />
                                    </div>

                                    <span class="username">Nefeloumpa</span>
                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/inlove.jpg" class="mood" alt="Ερωτευμένη" title="Ερωτευμένη" />
                                        ΣΤΑΞΕ ΤΟ ΔΑΚΡΥ ΣΤΟ ΤΣΙΖΚΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΕΙΙΚ!!!!!!!!!!!!!!!!!!!!!!! (vazelina &lt;3) TRWW THN POUTSA THS
                                    </span>
                                    <div class="overlay"></div>
                                </a>
                            </li>

                            <li>
                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/13223/223100/223100_100.jpg" alt="Psychotron" style="width:50px;height:50px" />
                                    </div>

                                    <span class="username">Psychotron</span>
                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/famous.jpg" class="mood" alt="Διάσημος" title="Διάσημος" />
                                         τιποτα/
                                    </span>
                                    <div class="overlay"></div>
                                </a>
                            </li>
                            <li>

                                <a href="" class="item">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/1463/222782/222782_100.jpg" alt="Seraphim" style="width:50px;height:50px" />
                                    </div>
                                    <span class="username">Seraphim</span>

                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/inlove.jpg" class="mood" alt="Ερωτευμένος" title="Ερωτευμένος" />
                                        λάμπει απο ραδιενέργεια! :D
                                    </span>
                                    <div class="overlay"></div>
                                </a>
                            </li>
                            <li>
                                <a href="" class="item">

                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <img src="http://images2.zino.gr/media/4451/222617/222617_100.jpg" alt="Zizou" style="width:50px;height:50px" />
                                    </div>
                                    <span class="username">zizou</span>

                                    <span class="twit">
                                        <img src="http://static.zino.gr/phoenix/moods/detached.jpg" class="mood" alt="Στον κόσμο του" title="Στον κόσμο του" />
                                        agapaei thn jojo tu :$
                                    </span>
                                    <div class="overlay"></div>
                                </a>
                            </li>
                        </ol>
                        <div class="toolbox">

                            <a href="" class="a">Επιλογές</a>
                            <a href="" class="b">Βρες φίλους</a>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    <div id="chat">
                        <a href="chat" class="maximize" title="Μεγιστοποίηση"></a>
                        <a href="" class="minimize" title="Ελαχιστοποίηση"></a>

                        <h2>Συζήτηση</h2>
                        <ol>
                            <!-- <li class="history">Προβολή προηγούμενων μηνυμάτων</li> --><?php
                            foreach ( $messages as $message ) {
                                ?><li class="text" id="s_<?php
                                echo $message[ 'id' ];
                                ?>"><strong><?php
                                echo $message[ 'username' ];
                                ?></strong> <div class="text"><?php
                                echo $message[ 'html' ];
                                ?></div></li><?php
                            }
                        ?></ol><?php
                        if ( $user->Exists() ) {
                            ?><div class="input">
                                <textarea>Πρόσθεσε ένα σχόλιο στη συζήτηση</textarea>
                            </div><?php
                        }
                        ?>
                    </div>
                </div><!-- class=border -->
            </div>
            <div id="frontpage">
            <div id="upstrip">
            <?php
                Element( 'banner' );
            ?>
            </div>
            <div id="midstrip">
                <div id="strip1">
                    <div id="strip1left" class="s1_0013">
                    </div>

                    <div id="strip1right" class="s1_0014">
                    </div>
                </div>

                <div id="strip2" class="sx_0010"><div id="content">
                <div id="notifications">
                    <div style="" class="shadow-left"></div>
                    <div style="" class="shadow-right"></div>
                    <div style="" class="shadow-bottom">

                        <div class="real"></div>
                    </div>
                    <div style="" class="shadow-bl"></div>
                    <div style="" class="shadow-br"></div>
                    <div id="notifybox">
                        <div class="border">
                            <a href="notifications" class="maximize" title="Μεγιστοποίηση"></a>
                            <a href="notifications" class="minimize" title="Ελαχιστοποίηση"></a>
                            <h2>14 νέες ενημερώσεις</h2>

                            <ol>
                                <li>
                                    <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>
                                    <a href="" class="item">
                                        <div class="avatar">
                                            <div class="tl corner"></div>
                                            <div class="tr corner"></div>
                                            <div class="bl corner"></div>

                                            <div class="br corner"></div>
                                            <img src="http://images2.zino.gr/media/5260/225368/225368_100.jpg" alt="funeral" />
                                        </div>
                                        <strong>funeral</strong> Καλά είσαι και βλάκας παιδί μου...
                                    </a>
                                </li>
                                <li>
                                    <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>

                                    <a href="" class="item">
                                        <div class="avatar">
                                            <div class="tl corner"></div>
                                            <div class="tr corner"></div>
                                            <div class="bl corner"></div>
                                            <div class="br corner"></div>
                                            <img src="http://images2.zino.gr/media/4856/184764/184764_100.jpg" alt="ronaldo7" />
                                        </div>
                                        <strong>ronaldo7</strong> Nice pic!
                                    </a>

                                </li>
                                <li>
                                    <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>
                                    <a href="" class="item">
                                        <div class="avatar">
                                            <div class="tl corner"></div>
                                            <div class="tr corner"></div>
                                            <div class="bl corner"></div>

                                            <div class="br corner"></div>
                                            <img src="http://images2.zino.gr/media/4451/222617/222617_100.jpg" alt="zizou" />
                                        </div>
                                        <strong>zizou</strong> OMG! LOL
                                    </a>
                                </li>
                                <li>
                                    <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>

                                    <a href="" class="item">
                                        <div class="avatar">
                                            <div class="tl corner"></div>
                                            <div class="tr corner"></div>
                                            <div class="bl corner"></div>
                                            <div class="br corner"></div>
                                            <img src="http://images2.zino.gr/media/11201/192216/192216_100.jpg" alt="gianniZzZ" />
                                        </div>
                                        <strong>gianniZzZ</strong> Τι λέει ρε συ;
                                    </a>

                                </li>
                                <li>
                                    <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>
                                    <a href="" class="item">
                                        <div class="avatar">
                                            <div class="tl corner"></div>
                                            <div class="tr corner"></div>
                                            <div class="bl corner"></div>

                                            <div class="br corner"></div>
                                            <img src="http://images2.zino.gr/media/4427/223555/223555_100.jpg" alt="kwNnayO" />
                                        </div>
                                        Η <strong>kwNnayO</strong> αγαπάει την φωτογραφία σου.
                                    </a>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div id="stream">
                    <h2>Τι συμβαίνει;</h2>
                    <ul>
                        <li>
                            <div class="avatar">
                                <div class="tl corner"></div>

                                <div class="tr corner"></div>
                                <div class="bl corner"></div>
                                <div class="br corner"></div>
                                <a href="" title="izual">
                                    <img src="http://images2.zino.gr/media/58/223815/223815_100.jpg" alt="izual" style="width:50px;height:50px" />
                                </a>
                            </div>
                            <div class="spotcontent">
                                <a href="" class="filter" title="Κι άλλα όπως αυτό"></a>

                                <h3>
                                    <i class="photo icon"></i> 
                                    Νέες φωτογραφίες του <strong><a href="" class="inline">izual</a></strong></h3>
                                <a href="">
                                    <img src="http://images2.zino.gr/media/58/224071/224071_100.jpg" alt="" />
                                </a>
                                <a href="">
                                    <img src="http://images2.zino.gr/media/58/223815/223815_100.jpg" alt="Shiny happy people :-)" title="Shiny happy people :-)" />

                                </a>
                                <a href="">
                                    <img src="http://images2.zino.gr/media/58/223102/223102_100.jpg" alt="" />
                                </a>
                            </div>
                            <div class="comments">
                                <h4>13 σχόλια</h4>
                                <ul class="comments">

                                    <li><a href=""><strong>dionyziz</strong> Τα σπάει!</a></li>
                                    <li class="lvl2"><a href=""><strong>izual</strong> Καλό είναι!</a></li>
                                    <li><a href=""><strong>pagio91</strong> Nice</a></li>
                                </ul>

                            </div>
                        </li>
                        <li class="link">
                            <div class="avatar">
                                <div class="tl corner"></div>
                                <div class="tr corner"></div>
                                <div class="bl corner"></div>
                                <div class="br corner"></div>
                                <a href="" title="zizou">

                                    <img src="http://images2.zino.gr/media/4451/222617/222617_100.jpg" alt="zizou" style="width:50px;height:50px" />
                                </a>
                            </div>
                            <div class="spotcontent">
                                <a href="" class="filter" title="Κι άλλα όπως αυτό"></a>
                                <h3>
                                    <strong><a href="">zizou</a></strong> 
                                        <i class="poll icon"></i> Πόσο σου αρέσει η μαλακία;
                                </h3>

                            </div>
                            <div class="comments">
                                <h4>4 σχόλια</h4>
                                <ul class="comments">
                                    <li><a href=""><strong>pagio91</strong> πάαααααααααααααααρα πολύ!!!1</a></li>
                                </ul>
                            </div>

                        </li>
                        <li class="link">
                            <div class="avatar">
                                <div class="tl corner"></div>
                                <div class="tr corner"></div>
                                <div class="bl corner"></div>
                                <div class="br corner"></div>
                                <a href="" title="pagio91">
                                    <img src="http://images2.zino.gr/media/4005/217702/217702_100.jpg" alt="pagio91" style="width:50px;height:50px" />

                                </a>
                            </div>
                            <div class="spotcontent">
                                <a href="" class="filter" title="Κι άλλα όπως αυτό"></a>
                                <h3><strong><a href="http://pagio91.zino.gr/">pagio91</a></strong> 
                                    <i class="journal icon"></i> SPOT: The internal workings</h3>
                            </div>
                            <div class="comments">

                                <h4>16 σχόλια</h4>
                                <ul class="comments">
                                    <li><a href=""><strong>abresas</strong> Cool! OMG!</a></li>
                                    <li class="lvl2"><a href=""><strong>pagio91</strong> Yes, it IS COOL!</a></li>
                                </ul>

                            </div>
                        </li>
                        <li class="link">
                            <div class="avatar">
                                <div class="tl corner"></div>
                                <div class="tr corner"></div>
                                <div class="bl corner"></div>
                                <div class="br corner"></div>
                                <a href="" title="beboula">

                                    <img src="http://images2.zino.gr/media/4000/219356/219356_100.jpg" alt="beboula" style="width:50px;height:50px" />
                                </a>
                            </div>
                            <div class="spotcontent">
                                <a href="" class="filter" title="Κι άλλα όπως αυτό"></a>
                                <h3>
                                    <strong><a href="">beboula</a></strong> 
                                        <i class="poll icon"></i> den einai uperoxo pou ta magazia evalan xristougenniatika?? :) :) exete mpei sto klima???
                                </h3>

                            </div>
                            <div class="comments">
                                <h4>19 σχόλια</h4>
                                <ul class="comments">
                                    <li><a href=""><strong>kard0uLina</strong> tcu k dn 9elw :P</a></li>
                                    <li><a href=""><strong>_daemon_</strong> w nai, iperoxo, as poulisoume kana xristougeniatiko giati pirame to poulo me ta ipoloipa! hell yeah goustarw katanalwtikes epidromes logo xmas</a></li>

                                    <li><a href=""><strong>Seraphim</strong> giou ar xot</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="avatar">
                                <div class="tl corner"></div>

                                <div class="tr corner"></div>
                                <div class="bl corner"></div>
                                <div class="br corner"></div>
                                <a href="" title="uLee">
                                    <img src="http://images2.zino.gr/media/1778/190846/190846_100.jpg" alt="uLee" style="width:50px;height:50px" />
                                </a>
                            </div>
                            <div class="spotcontent">
                                <a href="" class="filter" title="Κι άλλα όπως αυτό"></a>

                                <h3>
                                    <i class="photo icon"></i> 
                                    Νέες φωτογραφίες της <strong><a href="" class="inline">uLee</a></strong></h3>
                                    <a href="">
                                        <img src="http://images2.zino.gr/media/1778/120808/120808_100.jpg" alt="" />
                                    </a>
                                    <a href="">
                                        <img src="http://images2.zino.gr/media/1778/169693/169693_100.jpg" alt=":P" title=":P" />

                                    </a>
                                    <a href="">
                                        <img src="http://images2.zino.gr/media/1778/165339/165339_100.jpg" alt="" title="" />
                                    </a>
                            </div>
                            <div class="comments">
                                <h4>13 σχόλια</h4>
                                <ul class="comments">

                                    <li><a href=""><strong>B1anka</strong> gmth pic :-)</a></li>
                                    <li class="lvl2"><a href=""><strong>uLee</strong> Thanks :D</a></li>
                                    <li class="lvl3"><a href=""><strong>B1anka</strong> tpt :P</a></li>
                                </ul>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            </div></div>
            
                <div id="strip3">
                    <div id="strip3left" class="s1_0015">
                    </div>

                    <div id="strip3right" class="s1_0016">
                    </div>
                    <div id="strip3middle" class="sx_0003">
                    </div>
                </div>
                
                <div id="downstrip" class="sx_0002" style="position:relative">
                    <div>
                        <a class="wlink" href="about">Πληροφορίες</a>

                        <a class="wlink" href="legal">Νομικά</a>
                        <a class="wlink" href="?p=ads">Διαφήμιση</a>
                    </div>
                    <div id="copyleft">
                        <span>&copy; 2009</span> <a class="wlink" href="http://www.kamibu.com/">Kamibu</a>
                    </div>

                </div>
                
            </div>
			<?php
            return array( 'tiny' => true, 'selfmanaged' => true );
        }
    }
?>
