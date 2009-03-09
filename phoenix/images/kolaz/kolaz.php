<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct( 'plain' );

	global $libs;
	global $user;
	global $xc_settings, $rabbit_settings;
	
	$libs->Load("image/tag");
	
	/*$personid = $_GET[ 'personid' ];*/
	        
    $Tagfinder = new ImageTagFinder();
    $tags = $Tagfinder->FindByPersonId( 4005 );
    /*Proccess : Find tags , allocate new image , find positions invoking cpp prog, load and copy its image to kollaz*/

    $img = imagecreate(450,550);
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
	

	header( 'Content-type: image/jpg' );
	imagejpeg($img);
	imagedestroy($src);
	imagedestroy($img);
			
	Rabbit_Destruct();
?>
