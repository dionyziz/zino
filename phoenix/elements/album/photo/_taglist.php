<?php
    class ElementAlbumPhotoTaglist extends Element {
        public function Render( tInteger $id , tInteger $pageno ) {
            global $page;
            global $user;
            global $rabbit_settings; 
            global $water;
            global $libs;
            
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
            
            ?><div id="photolist"><?php
            
            $Tagfinder = new ImageTagFinder();
            $tags = $Tagfinder->FindByPersonId( $newuser->Id, ( $pageno - 1 ) * 20 , 20 ); 
            $alltags = $Tagfinder->FindByPersonId( $newuser->Id ); 
            $Numphotos = count( $alltags );
                        
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
            
            ?><div class="eof"></div>
            <div class="pagifyimages"><?php

            $link = '?p=taglist&id=' . $newuser->Id . '&pageno=';
            $total_pages = ceil( $Numphotos / 20 );
            $text = '( ' . $Numphotos . ' Φωτογραφί' ;
            if ( $Numphotos == 1 ) {
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
