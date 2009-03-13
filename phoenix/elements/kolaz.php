<?php    
    class ElementKolaz extends Element {
        public function Render( tText $username ) {
	        global $page;
	        global $user;
	        global $libs;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");	        
	        $libs->Load("user/user");
	        
	        $username = $username->Get();
	        
	        $userFinder = new UserFinder();
	        $_user = $userFinder->FindByName( $name );	        
	        echo "<p>" . $_user->Name . " " . $_user->Id . " " . $_user[ 'id' ] . "</p>";	        
	        if ( $res == NULL ) {
	            ?><p>Δεν υπάρχει χρήστης με αυτό το όνομα.</p><?php
                return;
	        }
	        else {	        
	            $personid = $_user->Id;
	        }
	        
	        $Tagfinder = new ImageTagFinder();
            $tags = $Tagfinder->FindByPersonId( $personid );
            
            if ( count( $tags ) == 0 ) {
                ?><p>Δεν έχεις κανένα tag.</p><?php
                return;
            }
            
            $input = array();
            $data = array();
            $ids = array();
            foreach ( $tags as $tag ) {
                $input[] = array( 'id' => $tag->Imageid, 'width' => $tag->Width, 'height' => $tag->Height );
                $data[ $tag->Imageid ] = array( 'width' => $tag->Width, 'height' => $tag->Height, 'personid' => $tag->Personid, 'left' => $tag->left, 'top' => $tag->top );
                $ids[] = $tag->Imageid;
            }
            
            $kolaz = new KolazCreator;
            if ( $kolaz->RetrievePositions( $input ) == false ) {
                ?><p>Υπήρξε ένα πρόβλημα με αυτήν την σελίδα!</p><?php
                return;
            }
            
            $imageFinder = new ImageFinder();//Find image owners ids
            $images = $imageFinder->FindByIds( $ids );
            $owners = array();
            foreach ( $images as $image ) {
                $owners[ $image->Id ] = $image->Userid;
            }    


	        ?><div class="kolazimage" style="width:<?php echo $kolaz->maxX;?>px;height:<?php echo $kolaz->maxY;?>px;position: relative;"><?php
            foreach ( $kolaz->mPositions as $key=>$val ) {
                ob_start();
                Element( 'image/url', $key, $owners[ $key ], IMAGE_FULLVIEW );
                $url = ob_get_contents();
                ob_end_clean();
                
                ?><div style="width:<?php echo $data[ $key ][ 'width' ];?>px; height:<?php echo $data[ $key ][ 'height' ];?>px; position:absolute; left:<?php echo $val[ 'xpos' ];?>px; top:<?php echo $val[ 'ypos' ];?>px;">
                        <img style="position: absolute;left:-<?php echo $data[ $key ][ 'left' ]?>px;top:-<?php echo $data[ $key ][ 'top' ]?>px" src="<?php echo $url;?>" alt="img" />
                </div><?php
            }	        
	        ?></div><?php
        }
    }
?>
