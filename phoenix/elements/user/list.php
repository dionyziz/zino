<?php

	function ElementUserList( $theusers ) {
		?><div class="people">
			<ul><?php
				foreach ( $theusers as $theuser ) {
					?><li><a href="<?php
					Element( 'user/url' , $theuser );
					?>"><?php
					Element( 'user/avatar' , $theuser , 100 , '' , '' , false , 0 , 0 );
					?><strong><?php
					echo Element( 'user/name' , $theuser , false );
					?></strong><span>προβολή προφίλ &raquo;</span></a></li><?php
				}			
			?></ul>
			<div class="eof"></div>
		</div><?php
	}
?>
