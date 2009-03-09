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
    /*$img = imagecreate(420,510);//Element( 'image/url', $tag->Imageid, $tag->Personid, IMAGE_FULLVIEW );   
    $src = imagecreatefrompng( Element( 'image/url', $tags[0]->Imageid, $tags[0]->Personid, IMAGE_FULLVIEW ) );
    imagecopy( $img, $src,0,0,$tags[0]->left,$tags[0]->top,$tags[0]->Width,$tags[0]->Height );*/
    /* createfromstring
    imagecreatefromstring*/
    $img = imagecreate(420,510);
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
    $url = $url . $tags[0]->Imageid . '/' . $tags[1]->Imageid . '_' . IMAGE_FULLVIEW . '.jpg';
    $src = imagecreatefromstring(file_get_contents( $url ));
    imagecopy( $img, $src,0,331,$tags[1]->left,$tags[1]->top,$tags[1]->Width,$tags[1]->Height );
    
	

	header( 'Content-type: image/jpg' );
	imagejpeg($img);
	imagedestroy($src);
	imagedestroy($img);
			
	Rabbit_Destruct();
?>
