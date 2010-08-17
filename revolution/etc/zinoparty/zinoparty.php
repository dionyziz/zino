<?php
	$data = file( "http://www.zino.gr/randomsongs" );
	$xml = "";
	foreach ( $data as $piece ) {
		$xml .= $piece;	
	}
	//var_dump( $data );
	//echo $xml;
	$p = xml_parser_create();
	xml_parse_into_struct($p, $xml, $vals, $index);
	xml_parser_free( $p );
//	var_dump( $vals );
//	var_dump( $index );
	$songid = 1787526;	

	$ids = array();
	$duration = array();
	$i = 0;
	foreach( $index[ 'SONG' ] as $songtag ) {
		if ( $vals[ $songtag ][ 'type' ] == 'open' ) {
			$ids[ $i ] = ( int )$vals[ $songtag ][ 'attributes' ][ 'ID' ];
			$i++;
		}
	}
	$i = 0;
	foreach ( $index[ 'DURATION' ] as $tag ) {
		$duration[ $i ] = (int )$vals[ $tag ][ 'value' ];
		$i++; 
	}
//	var_dump( $ids );
//	var_dump( $duration );
	$songid = $ids[ 0 ];

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
			<?php 
				$i = 0;
				foreach ( $ids as $key => $val ) {
					echo "songid[" .  $i . "] = " . $val . ";";
					echo "duration[" . $i ."] =  " . $duration[ $i ] . ";";
					$i++;
				}
			?>
			songcounter = 1;
			songamount = <?php echo count( $ids ); ?>;
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
	<body onload = "var x = setTimeout( 'LoadNext();', <? echo $duration[ 0 ]*1000+5000; ?> );timers.push( x );">
 
		<div class="mplayer" >
		    <div class="player" id = "player">
		        <object>
		            <param name="movie" value="http://listen.grooveshark.com/songWidget.swf">
		            <param name="wmode" value="opaque">
		            <param name="allowScriptAccess" value="always">
		            <param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $ids[ 0 ]; ?>&amp;style=metal&amp;p=1">
		            <embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $ids[ 0 ]; ?>&amp;style=metal&amp;p=1" allowScriptAccess="always" wmode="opaque"></embed>
		        </object>
		    </div>
		</div>

		<div class = "nextbutton" >
			<form>		
				<span><input type = "button" name="stop" value = "stop autoplay" id = "stop" onclick ="javascript:StopAutoplay();" /></span>
				<span><input type = "button" name="next" value = "next" id = "next" onclick = "javascript:LoadNext();" /></span>				
			</form>
		</div>
	</body>
</html>
