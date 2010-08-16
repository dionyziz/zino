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
		if ( $vals[ $songtag ][ 'type' ] == 'complete' ) {
			$ids[] = ( int )$vals[ $songtag ][ 'attributes' ][ 'ID' ];
		}
	}
	//var_dump( $ids );
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
				if ( songamount + 1 <= songcounter	) {
					alert( "no more  songs" );
					return;
				}

				songcounter++;
				document.getElementById("player").innerHTML = "<object>" +
		            '<param name="movie" value="http://listen.grooveshark.com/songWidget.swf">' +
		            '<param name="wmode" value="opaque">' +
		            '<param name="allowScriptAccess" value="always">' +
		            '<param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=' + songid[ songcounter ] + '&amp;style=metal&amp;p=1">' +
		            '<embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=' + songid[ songcounter ] + '&amp;style=metal&amp;p=1" allowScriptAccess="always" wmode="opaque"></embed>' +
		        '</object>';
				return;
			}
        </script>

	</head>
	<body onKeyDown="javascript:LoadNext();">

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
				<div><input type = "button" name="next" value = "next" onclick = "javascript:LoadNext();" /></div>
			</form>
		</div>
	</body>
</html>
