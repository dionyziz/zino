<?php

	function ElementFrontpageShoutboxView( $shout ) {
		?><div class="comment" style="border-color: #dee;">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $shout->Since;
				?></span>
			</div>
			<div class="who">
				<a href="<?php
				Element( 'user/url' , $shout->User );
				?>"><?php
					Element( 'user/avatar' , $shout->User , 100 , 'avatar' , '' , true , 50 , 50 );
					echo $shout->User->Name;
				?></a> είπε:
			</div>
			<div class="text"><?php
				echo htmlspecialchars( $shout->Text );
			?></div>
		</div><?php
	}
?>