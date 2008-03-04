<?php
	function ElementUserIcon( $theuser , $link = true , $tiny = false ) {
		global $libs;
		
		$libs->Load( 'image/image' );
		
        $link = $link && $theuser->Exists();
		if ( $link ) {
			?><a href="<?php
            Element( 'user/url', $theuser );
			?>"><?php
		}
		if ( $tiny ) {
			$style = 'width:16px;height:16px;';
		}
        else {
            $style = 'width:50px;height:50px;';
        }
		Element( 'image' , $theuser->Icon() , 50 , 50 , 'avatar' , $style , $theuser->Username() , $theuser->Username() );
		if ( $link ) {
			?></a><?php
		}
	}
?>
