<?php    
    class ElementKolaz extends Element {
        public function Render( $personid ) {
	        global $page;
	        global $user;
	        global $libs;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        
	        $Tagfinder = new ImageTagFinder();
	        $tags = $Tagfinder->FindByPersonId( $personid );
	        foreach ( $tags as $tag ) {
                ?><p><?php
                echo $tag->Imageid;
                ?> <?php
                echo $tag->Width;                
                ?> <?php
                echo $tag->Height;                               
                ?> <?php
                echo $tag->Personid;
                ?> <?php
                Element( 'image/view', $tag->Imageid , $tag->Personid , $tag->Width , $tag->Height , IMAGE_FULLVIEW , "" , "" , "", false , 0 , 0, 0 );/*
                public function Render( $imageid, $imageuserid , $imagewidth , $imageheight , $type = IMAGE_PROPORTIONAL_210x210, $class = '', $alttitle = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 , $numcom = 0 )*/
                ?></p><?php
            }
        }
    }
?>
