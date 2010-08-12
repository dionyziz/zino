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
	//var_dump( $vals );
	//var_dump( $index );
	$songid = 1787526;	

	$ids = array();
	foreach( $index[ 'SONG' ] as $songtag ) {
		if ( $vals[ $songtag ][ 'type' ] == 'open' ) {
			$ids[] = ( int )$vals[ $songtag ][ 'attributes' ][ 'ID' ];
		}
	}
	//var_dump( $ids );
	$songid = $ids[ 0 ];

?>

<html>
	<head>
		<title>Zino Party!</title>
		<script type="text/javascript">
			var songid = new Array();
			<?php 
				$i = 0;
				foreach ( $ids as $id ) {
					echo "songid[" .  $i . "] = " . $id . ";";
					$i++;
				}
			?>
			songcounter = 0;
			songamount = <?php echo count( $ids ); ?>;
			function LoadNext() { 
				alert( "happy" );
				if ( songamount <= $songcounter	) {
					alert( "no more  songs" );
					return;
				}

				songcounter++;
				document.getElementById("player").innerHTML = "<object>" +
		            '<param name="movie" value="http://listen.grooveshark.com/songWidget.swf">' +
		            '<param name="wmode" value="opaque">' +
		            '<param name="allowScriptAccess" value="always">' +
		            '<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songid ?>&amp;style=metal">' +
		            '<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=' + songid[ songcounter ] + '&amp;style=metal" allowScriptAccess="always" wmode="opaque"></embed>' +
		        '</object>';
				return;
			}
        </script>

	</head>
	<body>

		<div class="mplayer" >
		    <div class="player" id = "player">
		        <object>
		            <param name="movie" value="http://listen.grooveshark.com/songWidget.swf">
		            <param name="wmode" value="opaque">
		            <param name="allowScriptAccess" value="always">
		            <param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songid ?>&amp;style=metal">
		            <embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $ids[ 0 ]; ?>&amp;style=metal" allowScriptAccess="always" wmode="opaque"></embed>
		        </object>
		    </div>
		</div>

		<div class = "nextbutton" >
			<form>		
				<div><input type = "button" name="next" value = "next" onclick = "javascript:LoadNext();" /></div>
			</form>
		</div>
	</body>
</html>
