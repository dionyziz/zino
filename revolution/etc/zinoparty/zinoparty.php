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
	</head>
	<body>

		<div class="mplayer">
		    <div class="player">
		        <object>
		            <param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param>
		            <param name="wmode" value="opaque"></param>
		            <param name="allowScriptAccess" value="always"></param>
		            <param name="flashvars" value="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songid ?>&amp;style=metal"></param>
		            <embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="hostname=cowbell.grooveshark.com&amp;songID=<?php echo $songid ?>&amp;style=metal" allowScriptAccess="always" wmode="opaque"></embed>
		        </object>
		    </div>
		</div>

	</body>
</html>
