<?php
    class ElementDashboardStream extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'content' );

            $stream = Content_GetContent();


            ?><div id="stream">
                <h2>Τι συμβαίνει;</h2>
                <ul><?php
                    foreach ( $stream as $fish ) {
                        $item = $fish[ 'item' ];
                        $comments = $fish[ 'comments' ];
                        switch ( get_class( $item ) ) {
                            case 'Journal':
                                $journal = $item;
                                ?><li class="link">
                                    <div class="avatar">
                                        <div class="tl corner"></div>
                                        <div class="tr corner"></div>
                                        <div class="bl corner"></div>
                                        <div class="br corner"></div>
                                        <a href="" title="<?php
                                            echo $journal->User->Name;
                                            ?>"><?php
                                            Element( 'image/view', $journal->User->Avatarid, $journal->Userid, 100, 100, IMAGE_CROPPED_100x100, '', $journal->User->Name, '', false, 0, 0, 0 );
                                            ?>
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
                                </li><?php
                                break;
                            case 'Image':
                                ?><li>
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
                                </li><?php
                                break;
                            case 'Poll':
                                ?><li class="link">
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

                                </li><?php
                                break;
                        }
                    }
                ?>
                </ul>
                </div>
                <?php
                return;
                ?>
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
            </div><?php
        }
    }
?>
