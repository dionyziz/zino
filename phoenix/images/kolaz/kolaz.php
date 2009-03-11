<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct( 'plain' );

	global $libs;
	global $user;
	global $xc_settings, $rabbit_settings;
	
	$libs->Load("image/tag");
	$libs->Load("kolaz/kolaz");
	
	/*$userid = $_GET[ 'userid' ];	       */
    /*Proccess : Find tags , allocate new image , find positions invoking cpp prog, load and copy its image to kollaz*/    
    /*echo Element( 'image/url', $tags[0]->Imageid, $tags[0]->Personid,  IMAGE_FULLVIEW );*/
    
    $Tagfinder = new ImageTagFinder();
    $tags = $Tagfinder->FindByPersonId( 1 );
    
    $input = array();
    $data = array();
    foreach ( $tags as $tag ) {
        $input[] = array( 'id' => $tag->Imageid, 'width' => $tag->Width, 'height' => $tag->Height );
        $data[ $tag->Imageid ] = array( 'width' => $tag->Width, 'height' => $tag->Height, 'personid' => $tag->Personid, 'left' => $tag->left, 'top' => $tag->top );
    }
    
    $kolaz = new KolazCreator;
    if ( $kolaz->RetrievePositions( $input ) == false ) {
        die("kolaz was fucked up");
    }

    $img = imagecreatetruecolor( $kolaz->maxX, $kolaz->maxY );
    foreach ( $kolaz->mPositions as $key=>$val ) {
        $url = $xc_settings[ 'imagesurl' ] . $data[ $key ][ 'personid' ] . '/';
        if ( !$rabbit_settings[ 'production' ] ) {
            $url = $url .  '_';
        }
        $url = $url . $key . '/' . $key . '_' . IMAGE_FULLVIEW . '.jpg';
        
        if ( file_exists( $url ) ) {
            $src = imagecreatefromstring(file_get_contents( $url ));
            imagecopy( $img, $src,$val[ 'xpos' ],$val[ 'ypos' ],$data[ $key ][ 'left' ],$data[ $key ][ 'top' ],$data[ $key ][ 'width' ],$data[ $key ][ 'height' ] );
            imagedestroy($src);
        }
    }
    /*
    $url = $xc_settings[ 'imagesurl' ] . $tags[0]->Personid . '/';
    if ( !$rabbit_settings[ 'production' ] ) {
        $url = $url .  '_';
    }
    $url = $url . $tags[0]->Imageid . '/' . $tags[0]->Imageid . '_' . IMAGE_FULLVIEW . '.jpg';
    $src = imagecreatefromstring(file_get_contents( $url ));
    imagecopy( $img, $src,0,0,$tags[0]->left,$tags[0]->top,$tags[0]->Width,$tags[0]->Height );
    $url = $xc_settings[ 'imagesurl' ] . $tags[1]->Personid . '/';
    if ( !$rabbit_settings[ 'production' ] ) {
        $url = $url .  '_';
    }
    $url = $url . $tags[1]->Imageid . '/' . $tags[1]->Imageid . '_' . IMAGE_FULLVIEW . '.jpg';
    $src = imagecreatefromstring(file_get_contents( $url ));
    imagecopy( $img, $src,0,331,$tags[1]->left,$tags[1]->top,$tags[1]->Width,$tags[1]->Height );
    $url = $xc_settings[ 'imagesurl' ] . $tags[2]->Personid . '/';
    if ( !$rabbit_settings[ 'production' ] ) {
        $url = $url .  '_';
    }
    $url = $url . $tags[2]->Imageid . '/' . $tags[2]->Imageid . '_' . IMAGE_FULLVIEW . '.jpg';
    $src = imagecreatefromstring(file_get_contents( $url ));
    imagecopy( $img, $src,363,0,$tags[2]->left,$tags[2]->top,$tags[2]->Width,$tags[2]->Height );
	*/

	header( 'Content-type: image/jpg' );
	imagejpeg($img);
	imagedestroy($img);
			
	Rabbit_Destruct();
?>
