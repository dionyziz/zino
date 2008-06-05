<?php

	function ElementUserList( $theusers ) {
		?><div class="people">
			<ul><?php
				foreach ( $theusers as $theuser ) {
					?><li><a href="<?php
					Element( 'user/url' , $theuser );
					?>"><?php
					Element( 'avatar' , 100 , '' , '' , false , 0 , 0 );
					?></li><?php
				}
			?></ul>
			<div class="eof"></div>
		</div><?php
	}
?>
