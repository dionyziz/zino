<?php    
    class ElementKolaz extends Element {
        public function Render( tInteger $personid ) {
	        global $page;
	        global $user;
	        global $libs;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");
	        
	        ?><div><img src="images/kolaz/kolaz.php?<?php echo $personid; ?>" alt="img"/></div><?php	        
	        
	        $Tagfinder = new ImageTagFinder();
	        $tags = $Tagfinder->FindByPersonId( $personid );
	        
	        $input = array();
	        foreach ( $tags as $tag ) {
                $input[] = array( 'id' => $tag->Imageid, 'width' => $tag->Width, 'height' => $tag->Height );
            }
	        
	        $kolaz = new KolazCreator;
	        ?><p>Program output</p><?php
	        if ( $kolaz->RetrievePositions( $input ) == true ) {
    	        foreach ( $kolaz->mPositions as $key=>$val ) {
    	            ?><p><?php
                    echo $key . " " . $val[ 'xpos' ]  . " " . $val[ 'ypos' ];
                    ?></p><?php
    	        }
    	        echo "maxX " . $kolaz->maxX . " maxY " . $kolaz->maxY;
	        }
	        else {
	            ?><p>Kolaz ws fukced up</p><?php
	        }
	        
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
