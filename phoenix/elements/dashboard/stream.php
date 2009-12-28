<?php
    class ElementDashboardStream extends Element {
        public function Render( $stream ) {
            ?><div id="stream">
                <h2>Τι συμβαίνει;</h2>
                <ul><?php
                    foreach ( $stream as $fish ) {
                        $type = $fish[ 'type' ];
                        $item = $fish[ 'item' ];
                        $comments = $fish[ 'comments' ];
                        ?><li class="link">
                        <div class="avatar">
                            <div class="tl corner"></div>
                            <div class="tr corner"></div>
                            <div class="bl corner"></div>
                            <div class="br corner"></div>
                            <a href="" title="<?php
                                echo $item->User->Name;
                                ?>"><?php
                                Element( 'image/view', $item->User->Avatarid, $item->Userid, 100, 100, IMAGE_CROPPED_100x100, '', $item->User->Name, '', false, 0, 0, 0 );
                                ?>

                            </a>
                        </div>
                        <div class="spotcontent">
                        <a href="" class="filter" title="Κι άλλα όπως αυτό"></a><?php
                        switch ( $type ) {
                            case 'Journal':
                                $journal = $item;
                                ?>
                                <h3><strong><?php
                                Element( 'user/name', $journal->Userid, $journal->User->Name, $journal->User->Subdomain );
                                ?></strong> 
                                <i class="journal icon"></i> <?php
                                echo htmlspecialchars( $journal->Title );
                                ?></h3>
                                <?php
                                break;
                            case 'Image':
                                ?>
                                <h3>
                                    <i class="photo icon"></i> 
                                    <?php
                                    if ( !is_array( $item ) ) {
                                        $items = array( $item );
                                    }
                                    else {
                                        $items = $item;
                                    }
                                    $username = $items[ 0 ]->User->Name;
                                    $gender = $items[ 0 ]->User->Gender;
                                    ?>
                                    Νέες φωτογραφίες <?php
                                    switch ( $gender ) {
                                        case 'f':
                                            ?>της<?php
                                            break;
                                        case 'm':
                                        default:
                                            ?>του<?php
                                    }
                                    ?> <strong><a href="" class="inline"><?php
                                    echo $username;
                                    ?></a></strong></h3>
                                    <?php
                                    foreach ( $items as $photo ) {
                                        ?><a href=""><?php
                                            Element( 'image/view', $photo->Id, $photo->Userid, 100, 100, IMAGE_CROPPED_100x100, '', $photo->Name, '', false, 0, 0, 0 );
                                        ?></a><?php
                                    }
                                break;
                            case 'Poll':
                                ?>
                                <h3>
                                    <strong><?php
                                        Element( 'user/name', $item->Userid, $item->User->Name, $item->User->Subdomain );
                                        ?></strong> 
                                        <i class="poll icon"></i> <?php
                                        echo htmlspecialchars( $item->Title );
                                        ?>
                                </h3>
                                <?php
                                break;
                        }
                        ?></div>
                        <div class="comments"><?php
                        if ( $item->Numcomments ) {
                                ?><h4><?php
                                echo $item->Numcomments;
                                ?> σχόλια</h4>
                                <ul class="comments">
                                    <li><a href=""><strong>abresas</strong> Cool! OMG!</a></li>
                                    <li class="lvl2"><a href=""><strong>pagio91</strong> Yes, it IS COOL!</a></li>
                                </ul><?php
                        }
                        ?></div><?php
                        ?></li><?php
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
