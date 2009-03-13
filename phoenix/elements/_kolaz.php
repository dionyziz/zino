<?php    
    class ElementKolaz extends Element {
        public function Render( tInteger $personid ) {
	        global $page;
	        global $user;
	        global $libs;
	        global $xc_settings, $rabbit_settings;
	        
	        $page->setTitle( 'Κολάζ--' );
	        
	        $libs->Load("image/tag");
	        $libs->Load("kolaz/kolaz");
	        
	        echo "<p>Php image</p>";
	        ?><div><img src="images/kolaz/kolaz.php?userid=<?php echo $personid; ?>" alt="img"/></div><?php	        
	        
	        
	        echo "<p>Html image</p>";
	        /*Find tags an positions*/
	        $Tagfinder = new ImageTagFinder();
            $tags = $Tagfinder->FindByPersonId( $personid );
            
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
	        /*end*/
	        
	        /*make view*/
	        ?><div class="kolazimage" style="width:<?php echo $kolaz->maxX;?>;height:<?php echo $kolaz->maxY;?>;position: relative;"><?php
            foreach ( $kolaz->mPositions as $key=>$val ) {
                $url = $xc_settings[ 'imagesurl' ] . $owners[ $key ] . '/';
                if ( !$rabbit_settings[ 'production' ] ) {
                    $url = $url .  '_';
                }
                $url = $url . $key . '/' . $key . '_' . IMAGE_FULLVIEW . '.jpg';
                
                ?><div style="overflow : hidden;width:<?php echo $data[ $key ][ 'width' ];?>px; height:<?php echo $data[ $key ][ 'height' ];?>px; position:absolute; left:<?php echo $val[ 'xpos' ];?>px; top:<?php echo $val[ 'ypos' ];?>px;">
                        <img style="position: absolute;left:-<?php echo $data[ $key ][ 'left' ]?>px;top:-<?php echo $data[ $key ][ 'top' ]?>px" src="<?php echo $url;?>" alt="img" />
                </div><?php
            }	        
	        ?></div><?php
	        /**/
	        
	        echo "<p>Further info</p>";
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
                Element( 'image/view', $tag->Imageid , $tag->Ownerid , $tag->Width , $tag->Height , IMAGE_FULLVIEW , "" , "" , "", false , 0 , 0, 0 );/*
                public function Render( $imageid, $imageuserid , $imagewidth , $imageheight , $type = IMAGE_PROPORTIONAL_210x210, $class = '', $alttitle = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 , $numcom = 0 )*/
                ?></p><?php
            }
        }
    }
?>
