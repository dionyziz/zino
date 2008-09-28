<?php
	function ElementUserProfileMain( $theuser, $articlesnum = '', $profilecommentsnum = '', $oldcomments = false ) {
        global $water;

		?><div class="leftbar info" style="padding-top:20px;width:400px;"><?php
                Element( 'user/profile/poll', $theuser );
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
