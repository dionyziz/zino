<?php
    class ElementTest extends Element {
        public function Render( $notif ) {
            global $libs;
            global $water;
            
            $libs->Load( 'image/tag' );
            
            $tag = New ImageTag();
            $tag->Imageid = 100292;
            $tag->Personid = 791;
            $tag->Ownerid = 822;
            $tag->Left = 0;
            $tag->Top = 0;
            $tag->Width = 170;
            $tag->Height = 170;
            $tag->Save();
        }
    }
?>