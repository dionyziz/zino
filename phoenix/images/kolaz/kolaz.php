<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct( 'plain' );

	global $libs;
	global $user;
	global $xc_settings, $rabbit_settings;
	
	$libs->Load("image/tag");
	$libs->Load("kolaz/kolaz");
	$libs->Load("image/image");
	
	$userid = $_GET[ 'userid' ];
    /*Proccess : Find tags , allocate new image , find positions invoking cpp prog, load and copy its image to kollaz*/    
    /*echo Element( 'image/url', $tags[0]->Imageid, $tags[0]->Personid,  IMAGE_FULLVIEW );*/
    
    $Tagfinder = new ImageTagFinder();
    $tags = $Tagfinder->FindByPersonId( $userid );
    
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

    $img = imagecreatetruecolor( $kolaz->maxX, $kolaz->maxY );
    foreach ( $kolaz->mPositions as $key=>$val ) {
        $url = $xc_settings[ 'imagesurl' ] . $owners[ $key ] . '/';
        if ( $rabbit_settings[ 'production' ] ) {
            $url = $url .  '_';
        }
        $url = $url . $key . '/' . $key . '_' . IMAGE_FULLVIEW . '.jpg';
               
        $src = imagecreatefromstring(file_get_contents( $url ));
        imagecopy( $img, $src,$val[ 'xpos' ],$val[ 'ypos' ],$data[ $key ][ 'left' ],$data[ $key ][ 'top' ],$data[ $key ][ 'width' ],$data[ $key ][ 'height' ] );
        imagedestroy($src);
    }
    
	header( 'Content-type: image/jpg' );
	imagejpeg($img);
	imagedestroy($img);
			
	Rabbit_Destruct();
?>
