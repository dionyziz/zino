<?php
    class ElementAlbumPhotoTaglist extends Element {
        public function Render( tInteger $id , tInteger $pageno ) {
            global $page;
            global $user;
            global $rabbit_settings; 
            global $water;
            
            $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");	        
	        $libs->Load("user/user");
            
            $newuser = new User( $id->Get() );
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            if ( !$newuser->Exists() ) {
                ?>To album δεν υπάρχει<div class="eof"></div><?php
                return;
            }
            
            Element( 'user/sections', 'album' , $newuser );
            
            ?><div id="photolist"<?php
            
            $Tagfinder = new ImageTagFinder();
            $tags = $Tagfinder->FindByPersonId( $newuser->Id ); 
                        
            $ids = array();
            foreach ( $tags as $tag ) { 
                $ids[] = $tag->Imageid;
            }             
            
            $imageFinder = new ImageFinder();//Find image owners ids
            $images = $imageFinder->FindByIds( $ids );
                   
            ?><ul><?php
                foreach( $images as $image ) {
                    ?><li><?php
                    Element( 'album/photo/small' , $image , false , true );
                    ?></li><?php
                }
            ?></ul><?php
            
            ?></div><?php
            
            /*<div class="eof"></div>
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
            </div><div class="eof"></div><?php*/
            
            
        }
    }
?>
