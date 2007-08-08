<?php
	function ElementUserProfileMain( $theuser, $articlesnum = '', $profilecommentsnum = '', $oldcomments = false ) {
		?><div class="leftbar info" style="padding-top:20px"><?php
				Element( 'user/profile/ccrelated' , $theuser );
				Element( 'user/profile/personal' , $theuser );
                Element( 'user/profile/characteristics', $theuser );
		?></div>
		<div class="rightbar"><?php
			Element( 'user/profile/contact' , $theuser );
			Element( 'user/profile/statistics' , $theuser, $articlesnum, $profilecommentsnum, $oldcomments );
		?></div><?php
	}
?>
