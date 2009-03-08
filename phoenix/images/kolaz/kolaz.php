<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct( 'plain' );

	global $libs;
	global $user;
	
	$libs->Load("image/tag");
	
	/*$personid = $_GET[ 'personid' ];*/
	        
    $Tagfinder = new ImageTagFinder();
    $tags = $Tagfinder->FindByPersonId( 4005 );
    
    //$img = imagecreate(420,510);//Element( 'image/url', $tag->Imageid, $tag->Personid, IMAGE_FULLVIEW );   
    $src = imagecreatefrompng( Element( 'image/url', $tags[0]->Imageid, $tags[0]->Personid, IMAGE_FULLVIEW ) );
    //imagecopy( $img, $src,0,0,$tags[0]->left,$tags[0]->top,$tags[0]->Width,$tags[0]->Height );
    
	

	header( 'Content-type: image/png' );
	imagepng($src);
	//imagepng($img);
	//imagedestroy($img);
	imagedestroy($src);
			
	Rabbit_Destruct();
?>
