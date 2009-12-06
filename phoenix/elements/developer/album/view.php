<?php

    class ElementDeveloperAlbumView extends Element {
        public function Render( $album, $images, $pageno ) {
            global $user;

            if ( $album->Ownertype == TYPE_USERPROFILE ) {
                Element( 'developer/user/sections', 'album' , $album->Owner );
            }
            else {
                Element( 'developer/school/info', $album->Owner, true );
            }
            ?><div id="photolist"<?php
                if ( $album->Ownertype == TYPE_SCHOOL ) {
                    ?> class="schoolupload"<?php   
                }
                ?>><?php
                if ( $album->IsDeleted() ) {
                    return Element( '404', 'Το album έχει διαγραφεί' ); 
                }
                ?><div class="objectinfo" style="margin-left: 160px;"><h2><?php
                    if ( $album->Ownertype == TYPE_USERPROFILE && $album->Id == $album->Owner->Egoalbumid ) {
                    ?>Εγώ<?php
                }
                else {
                    echo htmlspecialchars( $album->Name );
                }
                ?></h2>
                <dl><?php
                    if ( $album->Numphotos > 0 ) {
                        ?><dt class="photonum"><span class="s_photonum">&nbsp;</span><?php
                        echo $album->Numphotos;
                        ?></dt><?php
                    }
                    if ( $album->Numcomments > 0 ) {
                        ?><dt class="commentsnum"><span class="s1_0027">&nbsp;</span><?php
                        echo $album->Numcomments;
                        ?></dt><?php
                    }
                ?></dl><?php
                if ( $album->Ownertype == TYPE_USERPROFILE && ( $album->Ownerid == $user->Id || $user->HasPermission( PERMISSION_ALBUM_DELETE_ALL ) ) ) {
                    if ( $album->Id != $user->Egoalbumid ) {
                        ?><div class="owner">
                            <div class="edit"><a href="" onclick="return PhotoList.Rename( '<?php
                            echo $album->Id;
                            ?>' )"><span class="s_edit">&nbsp;</span>Μετονομασία</a>
                            </div>
                            <div class="delete"><a href="" onclick="return PhotoList.Delete( '<?php
                            echo $album->Id;
                            ?>' )"><span class="s1_0007">&nbsp;</span>Διαγραφή</a></div>
                        </div><?php
                    }
                }
                ?></div><?php
                if ( $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                    switch ( $album->Ownertype ) {
                        case TYPE_USERPROFILE:
                            $canupload = $album->Ownerid == $user->Id;
                            break;
                        case TYPE_SCHOOL:
                            $canupload = $user->Profile->Schoolid == $album->Ownerid; 
                            break;
                        default:
                            $canupload = false;
                    }
                    if ( $canupload ) {
                        ?><div class="uploaddiv"><?php
                        if ( UserBrowser() == 'MSIE' ) {
                            ?><iframe src="?p=upload&amp;albumid=<?php
                            echo $album->Id;
                            ?>&amp;typeid=0&amp;color=fff" class="uploadframe" id="uploadframe" scrolling="no" frameborder="0">
                            </iframe><?php
                        }
                        else {
                            ?><object data="?p=upload&amp;albumid=<?php
                            echo $album->Id;
                            ?>&amp;typeid=0&amp;color=fff" class="uploadframe" id="uploadframe" type="text/html">
                            </object><?php
                        }
                        ?></div><?php
                    }
                }
                ?><div class="eof"></div><ul><?php
                    foreach( $images as $image ) {
                        ?><li><?php
                        Element( 'developer/album/photo/small', $image, false, true );
                        ?></li><?php
                    }
                ?></ul>
                <div class="eof"></div>
                <div class="pagifyimages"><?php

                $link = '?p=album&id=' . $album->Id . '&pageno=';
                $total_pages = ceil( $album->Numphotos / 20 );
                $text = '( ' . $album->Numphotos . ' Φωτογραφί' ;
                if ( $album->Numphotos == 1 ) {
                    $text .= 'α';
                }
                else {
                    $text .= 'ες';
                }
                $text .= ' )';
                Element( 'developer/pagify', $pageno, $link, $total_pages, $text );
                ?></div>
            </div>
            <div class="eof"></div><?php
        }
    }

?>
