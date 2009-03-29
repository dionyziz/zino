<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
		?><div id="zinoPlayer">
			<div id="playerControls">
				<a href="javascript:;" id="playButton" class="play" onclick="togglePlayback();">Button</a>
				<div id="progressBar">
					<div id="buffered">
						<div id="progress"></div><div id="progressEnd"></div>
					</div><div id="bufferedEnd"></div>
				</div>
			</div>
			<ul id="playList"><?php
				$songs = explode( ";", $theuser->Profile->Song );
				$num = 0;
				foreach( $songs as $s ) {
					?><li><a id="song_<?php echo ++$num; ?>" href="javascript:;" onclick="playSong( <?php echo $s ?> )"><span></span>Song <?php echo $num ?></a></li><?php
				}?>
			</ul>		
			<script type='text/javascript' src='http://beta.zino.gr/phoenix/etc/mockups/swfobject.js'></script>
			<embed id="playerObject" name="playerObject" src="http://beta.zino.gr/phoenix/etc/mockups/player.swf" width="0" height="0" />
		</div><?php
        }
    }
?>
