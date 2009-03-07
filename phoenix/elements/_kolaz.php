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
                ?></p><?php
            }
        }
    }
?>
