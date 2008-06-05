<?php

	function ElementUserList( $relations ) {
		?><div class="people">
			<ul><?php
				foreach ( $relations as $relation ) {
					?><li><a href="<?php
					Element( 'user/url' , $relation->User );
					?>"><?php
					Element( 'user/avatar' , $relation->User , 100 , '' , '' , false , 0 , 0 );
					?><strong><?php
					echo Element( 'user/name' , $relation->User , false );
					?></strong><span>προβολή προφίλ &raquo;</span></a></li><?php
				}			
			?></ul>
			<div class="eof"></div>
		</div><?php
	}
?>
