<?php

	function ElementUserSig( $theuser ) {
		// AttachStylesheet( 'css/comment.css' ); <-- In file calling this element
		?><div class="sig"><?php
			echo htmlspecialchars( nl2br( $theuser->Signature() ) );
			?><br />
		</div><?php
	}

?>