<?php
    class ElementTest extends Element {
        public function Render( ) {
            global $libs;
            global $water;
            
            $libs->Load( 'image/tag' );
            
            $finder = New ImageTagFinder();
            $tags = $finder->FindByImage( New Image( 100292 ) );
            foreach ( $tags as $tag ) {
                $tag->Delete();
            }
            
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