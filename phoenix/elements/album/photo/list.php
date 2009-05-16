<?php
    class ElementAlbumPhotoList extends Element {
        public function Render( tInteger $id , tInteger $pageno ) {
            global $page;
            global $user;
            global $rabbit_settings; 
            global $water;
            
            $album = New Album( $id->Get() );
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            if ( !$album->Exists() ) {
                return Element( '404', 'To album δεν υπάρχει' );
            }
            if ( $album->Ownertype == TYPE_USERPROFILE ) {
                Element( 'user/sections', 'album' , $album->Owner );
            }
            else {
                Element( 'school/info', $album->Owner, true );
            }
            ?><div id="photolist"<?php
                if ( $album->Ownertype == TYPE_SCHOOL ) {
                    ?> class="schoolupload"<?php   
                }
                ?>><?php
                if ( $album->IsDeleted() ) {
                    return Element( '404', 'Το album έχει διαγραφεί' ); 
                }
                $finder = New ImageFinder();
                $images = $finder->FindByAlbum( $album , ( $pageno - 1 ) * 20 , 20 );
                if ( $album->Ownertype == TYPE_USERPROFILE && $album->Id == $album->Owner->Egoalbumid ) {
                    if ( strtoupper( substr( $album->Owner->Name, 0, 1 ) ) == substr( $album->Owner->Name, 0, 1 ) ) {
                        $page->SetTitle( $album->Owner->Name . " Φωτογραφίες" );
                    }
                    else {
                        $page->SetTitle( $album->Owner->Name . " φωτογραφίες" );
                    }
                }
                else {
                    $page->SetTitle( $album->Name );
                }
                ?><h2><?php
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
                        ?><dt class="commentsnum"><span class="s_commnum">&nbsp;</span><?php
                        echo $album->Numcomments;
                        ?></dt><?php
                    }
                ?></dl><?php
                if ( $album->Ownertype == TYPE_USERPROFILE && ( $album->Owner->Id == $user->Id || $user->HasPermission( PERMISSION_ALBUM_DELETE_ALL ) ) ) {
                    if ( $album->Id != $user->Egoalbumid ) {
                        ?><div class="owner">
                            <div class="edit"><a href="" onclick="return PhotoList.Rename( '<?php
                            echo $album->Id;
                            ?>' )"><span class="s_edit">&nbsp;</span>Μετονομασία</a>
                            </div>
                            <div class="delete"><a href="" onclick="return PhotoList.Delete( '<?php
                            echo $album->Id;
                            ?>' )"><span class="s_delete">&nbsp;</span>Διαγραφή</a></div>
                        </div><?php
                    }
                }
                if ( $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                    switch ( $album->Ownertype ) {
                        case TYPE_USERPROFILE:
                            $canupload = $album->Owner->Id == $user->Id;
                            break;
                        case TYPE_SCHOOL:
                            $canupload = $user->Profile->Schoolid == $album->Owner->Id; 
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
                ?><ul><?php
                    foreach( $images as $image ) {
                        ?><li><?php
                        Element( 'album/photo/small' , $image , false , true );
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
                Element( 'pagify', $pageno, $link, $total_pages, $text );
                ?></div>
                </div><div class="eof"></div><?php
        }
    }
?>
