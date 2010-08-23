<?php
	$data = file( "http://www.zino.gr/randomsongs" );
	$xml = "";
	foreach ( $data as $piece ) {
		$xml .= $piece;	
	}
	$s = new SimpleXMLElement( $xml );
	$songs = array();
	$i = 0;
	//print_r( $s );
	foreach( $s->songs->song  as $song  ) {
		$songs[ $i ][ 'id' ] = $song[ 'id' ];
		$songs[ $i ][ 'name' ] = $song->name;
		$songs[ $i ][ 'duration' ] = $song->duration;
		$songs[ $i ][ 'artist' ] = $song->artist->name;
		$songs[ $i ][ 'album' ] = $song->album->name;
		$songs[ $i ][ 'icon' ] = $song->media[ 'url' ];
		if ( $songs[ $i ][ 'icon' ] == 'http://beta.grooveshark.com/webincludes/img/defaultart/album/sdefault.png' ) {
			$songs[ $i ][ 'icon' ] = '';
		}
		$i++;
	}
	//var_dump( $data );
	$songid = 1787526;	
?>

<html>
	<head>
		<title>Zino Party!</title>
		<style type="text/css" media="all">
			html {
				background-image: url( 'sexpistols.jpg' );
			}
			body {
				width : 20cm;
				margin : auto;
				text-align : center;			
				padding-top : 7cm; 
			}
			div.mplayer {
				margin-bottom : 0.5cm;
			}
			div.player object {
				height : 40px;
				width : 280px;
			}
			div.nextbutton form input {	
				height : 40px;
				width : 100px;
			}
		</style>
		<script type="text/javascript">
			var songid = new Array();
			var duration = new Array();
			var timers = [];
			var icon = [];
			var songname = [];
			var artist = [];
			var album = [];
			<?php 
				$i = 0;
				foreach ( $songs as $song ) {
					echo "songid[" . $i . "] = " . $song[ 'id' ] . ";";
					echo "duration[" . $i . "] = " . $song[ 'duration' ] . ";";
					echo "icon[" . $i . "] = '" . htmlspecialchars( $song[ 'icon' ], ENT_QUOTES ) . "';";
					echo "songname[" . $i . "] = '" . htmlspecialchars( $song[ 'name' ], ENT_QUOTES ) . "';";
					echo "artist[" . $i . "] = '" . htmlspecialchars( $song[ 'artist' ], ENT_QUOTES ) . "';";
					echo "album[" . $i . "] = '" .htmlspecialchars(  $song[ 'album' ], ENT_QUOTES ) . "';\n";
					$i++;
				}
			?>
			songcounter = 1;
			songamount = <?= count( $songs ); ?>;
			function Init() {
				LoadNext();
			}
			function StopAutoplay() {
				for ( x in timers ) {
					clearTimeout( timers[ x ] );
				}
				timers = [];
				return;
			}
			function LoadNext() { 
				if ( songamount + 1 <= songcounter	) {
					location.reload( true );
					return;
				}

				document.getElementById("player").innerHTML = "<object>" +
		            '<param name="movie" value="http://listen.grooveshark.com/songWidget.swf">' +
		            '<param name="wmode" value="opaque">' +
		            '<param name="allowScriptAccess" value="always">' +
		            '<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=' + songid[ songcounter ] + '&amp;style=metal&amp;p=1">' +
		            '<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=' + songid[ songcounter ] + '&amp;style=metal&amp;p=1" allowScriptAccess="always" wmode="opaque"></embed>' +
		        '</object>';


				info = "<ul>"+
				"<li><p>" + songname[ songcounter ] + "</p></li>"+
				"<li><p>" + artist[ songcounter ] + "</p></li>" +
				"<li><p>" + album[ songcounter ] + "</p></li>";
				if ( icon[ songcounter ] != "" ) {
					info += "<li><img src = " + icon[ songcounter ] + " alt = 'art' title = 'art' /></li>"
				}
				info +=	"</ul>"; 
				document.getElementById( "songinfo" ).innerHTML = info;


				for ( x in timers ) {
					clearTimeout(timers[ x ] );
				}
				timers = [];
				var k = setTimeout( 'LoadNext();', duration[ songcounter]*1000 + 5000 );
				timers.push( k );
				songcounter++;
				return;
			}
        </script>

	</head>
	<body onload = "var x = setTimeout( 'LoadNext();', <? echo $songs[ 0 ][ 'duration' ]*1000+5000; ?> );timers.push( x );"> 
		<div class="mplayer" >
		    <div class="player" id = "player">
		        <object>
		            <param name="movie" value="http://listen.grooveshark.com/songWidget.swf">
		            <param name="wmode" value="opaque">
		            <param name="allowScriptAccess" value="always">
		            <param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songs[ 0 ][ 'id' ]; ?>&amp;style=metal&amp;p=1">
		            <embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songs[ 0 ][ 'id' ]; ?>&amp;style=metal&amp;p=1" allowScriptAccess="always" wmode="opaque"></embed>
		        </object>
		    </div>
		</div>
		<div class = "songinfo" id = "songinfo" >
			<ul>
				<li><p><?php echo $songs[ 0 ][ 'name' ] ?></p></li>
				<li><p><?php echo $songs[ 0 ][ 'artist' ] ?></p></li>
				<li><p><?php echo $songs[ 0 ][ 'album' ] ?></p></li>
				<?php if ( $songs[ 0 ][ 'icon' ] !== "" ) { ?>
				<li><img src = <?php echo htmlspecialchars( $songs[ 0 ][ 'icon' ], ENT_QUOTES ); ?> alt = "art" title = "art" /></li>
				<?php } ?>
			</ul>
		</div>
		<div class = "nextbutton" >
			<form>		
				<span><input type = "button" name="stop" value = "stop autoplay" id = "stop" onclick ="javascript:StopAutoplay();" /></span>
				<span><input type = "button" name="next" value = "next" id = "next" onclick = "javascript:LoadNext();" /></span>
			</form>
		</div>
	</body>
</html>
