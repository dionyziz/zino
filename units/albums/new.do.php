<?php
	function UnitAlbumsNew( tString $albumname , tString $albumdescription ) {		
		global $libs;
		global $water;
		global $rabbit_settings;
		
        $albumname = $albumname->Get();
        $albumdescription = $albumdescription->Get();
        
		$libs->Load( 'albums' );
		$newalbumid = Albums_CreateAlbum( $albumname , $albumdescription );
		?>location.href = '<?php
		echo $rabbit_settings[ 'webaddress' ];
		?>/index.php?p=album&id=<?php
		echo $newalbumid;
		?>';<?php
	}
?>
