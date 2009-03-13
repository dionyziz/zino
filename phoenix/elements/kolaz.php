<?php    
    class ElementKolaz extends Element {
        public function Render( tInteger $personid ) {
	        global $page;
	        global $user;
	        global $libs;
	        global $xc_settings, $rabbit_settings;
	        
	        $page->setTitle( 'Κολάζ' );
	        
	        $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");	        
	        
	        ?><h2>Κολάζ!</h2><?php
	        
	        $personid = $personid->Get();
	        $Tagfinder = new ImageTagFinder();
            $tags = $Tagfinder->FindByPersonId( $personid );
            
            if ( count( $tags ) == 0 ) {
                die( "Δεν έχεις κανένα tag:(" );
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
                die("kolaz was fucked up");
            }
            
            $imageFinder = new ImageFinder();
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
                ob_end_clean();/*
                $url = $xc_settings[ 'imagesurl' ] . $owners[ $key ] . '/';
                if ( !$rabbit_settings[ 'production' ] ) {
                    $url = $url .  '_';
                }
                $url = $url . $key . '/' . $key . '_' . IMAGE_FULLVIEW . '.jpg';*/
                
                ?><div style="width:<?php echo $data[ $key ][ 'width' ];?>px; height:<?php echo $data[ $key ][ 'height' ];?>px; position:absolute; left:<?php echo $val[ 'xpos' ];?>px; top:<?php echo $val[ 'ypos' ];?>px;">
                        <img style="position: absolute;left:-<?php echo $data[ $key ][ 'left' ]?>px;top:-<?php echo $data[ $key ][ 'top' ]?>px" src="<?php echo $url;?>" alt="img" />
                </div><?php
            }	        
	        ?></div><?php
        }
    }
?>
