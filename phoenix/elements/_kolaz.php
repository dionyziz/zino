<?php    
    class ElementKolaz extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        global $libs;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        
	        $Tagfinder = new ImageTagFinder();
	        $tags = $Tagfinder->FindByPersonId( 4005 );
	        foreach ( $tags as $tag ) {
                ?><p><?php
                echo $tag->Id;
                ?> <?php
                echo $tag->Width;                
                ?> <?php
                echo $tag->Height;
                ?> <?php
                ?></p><?php
                Element( 'image/view', $tag->ImageId , $tag->PersonId , $tag->Width , $tag->Height );/*
                public function Render( $imageid, $imageuserid , $imagewidth , $imageheight , $type = IMAGE_PROPORTIONAL_210x210, $class = '', $alttitle = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 , $numcom = 0 )*/                
            }
        }
    }
?>
