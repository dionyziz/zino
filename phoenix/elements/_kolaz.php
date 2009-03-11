<?php    
    class ElementKolaz extends Element {
        public function Render( tInteger $personid ) {
	        global $page;
	        global $user;
	        global $libs;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");
	        
	        $kolaz = new KolazCreator;
	        $kolaz->RetrievePositions( array( array( "id"=>1,"width"=>10,"height"=>10 ) ) );
	        
	        echo '<p>->->' . $kolaz->mPositions[1][ "xpos" ] . ' ' . $kolaz->mPositions[1][ "ypos" ] . '</p>' ;
	        
	        ?><div><img src="images/kolaz/kolaz.php" alt="img"/></div><?php
	        
	        
	        $Tagfinder = new ImageTagFinder();
	        $tags = $Tagfinder->FindByPersonId( $personid );
            echo $tags[0]->Imageid;
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
