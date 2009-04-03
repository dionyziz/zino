<?php
    class ElementAlbumPhotoTaglist extends Element {
        public function Render( tInteger $id , tInteger $pageno ) {
            global $page;
            global $user;
            global $rabbit_settings; 
            global $water;
            
            $newuser = new User( $id->Get() );
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            if ( !$newuser->Exists() ) {
                ?>To album δεν υπάρχει<div class="eof"></div><?php
                return;
            }
            
            Element( 'user/sections', 'album' , $newuser->Id );
        }
    }
?>
