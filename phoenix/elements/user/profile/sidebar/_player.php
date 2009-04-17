<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {?>
			<script type="text/javascript" src="http://beta.zino.gr/phoenix/etc/mockups/jquery-1.3.2.min.js"></script>
			<script type='text/javascript' src='http://beta.zino.gr/phoenix/etc/mockups/swfobject.js'></script>
			<div id="zinoPlayer">
				<div id="playerControls">
					<a href="javascript:;" id="playButton" class="play" onclick="togglePlayback();">Button</a>
					<div id="progressBar">
						<div id="buffered">
							<div id="progress"></div><div id="progressEnd"></div>
						</div><div id="bufferedEnd"></div>
					</div>
				</div>
				<ul id="playList">
					<?php
						$song = $theuser->Profile->Song;
					?>
					<li><a id="song_1" href="javascript:;" onclick="playSong('<?php echo $song["url"]?>', 'song_1')"><span></span><?php echo $song["name"] ?></a></li>
				</ul>		
				<embed id="playerObject" name="playerObject" src="http://gatoni.kamibu.com/player.swf" allowscriptaccess="always"
 width="0" height="0" />
			</div>
			<script type="text/javascript">
				var playlist = new Array();
				var totalSongs = 0; var nextSong = 0;
				var playing = false;
				var zplayer = document.getElementById('playerObject');
				var currentDuration;
				var trackStarted = false; 
				var listPlayback = false;


				function playSong( url, song_id ) {
					$("#playList li a.selected").removeClass('selected');
					$("#playList li a span.playing").removeClass('playing');
					$("#" + song_id).addClass('selected');
					$("#" + song_id + " span" ).addClass('playing');
					zplayer.sendEvent("LOAD", url );
					zplayer.sendEvent("PLAY","true");
					$("#progress").css("width","3px");
					zplayer.addModelListener("TIME","progressBarHandler"); 
					zplayer.addModelListener("LOADED", "bufferHandler" );
					zplayer.addModelListener("STATE", "stateHandler" );
					playing = true;
					$("#playButton.play").removeClass("play");
					$("#playButton").addClass("pause");
				}

				function togglePlayback() {
					if ( !listPlayback && !playing ) {
						playlist[nextSong++]();
					}

					if (playing) {
						zplayer.sendEvent("PLAY","false");
						playing = false;
						$("#playButton.pause").removeClass("pause");
						$("#playButton").addClass("play");
					}
					else {
						zplayer.sendEvent("PLAY","true");
						playing = true;
						$("#playButton.play").removeClass("play");
						$("#playButton").addClass("pause");
					}
				}

				function bufferHandler(obj) {
					var buffered = ( obj.loaded * 253 ) / obj.total;
					$("#buffered").css("width", Math.floor(buffered) + "px" );
				}

				function progressBarHandler(obj) {
					// A little hack to make progress bar move, without knowing the actual time.
					if ( obj.duration == 0 ) {
						currentDuration = 360;	// Set to 6 minutes
						$("#progressBar").css("cursor", "default");
					}
					else {
						currentDuration = obj.duration; 
						$("#progressBar").css("cursor", "pointer");
					}
					var progressTime = ( 253 * obj.position ) / currentDuration;
					$("#progress").css("width", Math.floor(progressTime) + "px" );
				}

				function stateHandler(obj) {
				}

				function makePlaylist(htmlContainer) {
					$( "#" + htmlContainer + " li a" ).each( function() {
						playlist[ totalSongs++ ] = $(this).attr('onclick');
					});
				}

				$(document).ready( function() {
					makePlaylist("playList");
					$("#progressBar").click( function(e) {
						zplayer.sendEvent("SEEK", Math.floor( ( ( e.pageX - 95 ) * currentDuration ) / 250 ) );
					});
				});
			</script><?php
        }
    }
?>
