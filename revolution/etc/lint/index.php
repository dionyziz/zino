<?php
    header( 'Content-type: application/xhtml+xml' );
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>JSLINT</title>
        <script type="text/javascript" src="http://www.zino.gr/js/jslint/fulljslint.js"></script>
        <link rel="stylesheet" type="text/css" href="http://www.zino.gr/css/jslint.css"></link>
        <style type="text/css">
            html, body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            body {
                font-family: Verdana, "Trebuchet MS", Helvetica;
                font-size: 90%;
            }
            a, a:visited, a:active {
                text-decoration: none;
                color: #105cb6;
            }
            a:hover {
                color: #000033;
            }
            div.content {
                margin: 10px;
            }
            div.username {
                position: absolute;
                right: 20px;
                top: 10px;
                font-weight: bold;
                font-size: 80%;
            }
            div.eof {
                clear: both;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table tr.l {
                background-color: #eee;
            }
            textarea {
                margin: 5px;
                width: 100%;
                height: 50px;
            }
            table thead tr td {
                font-weight: bold;
                font-size: 80%;
            }
            table thead tr {
                background-color: #eee;
            }
            table td {
                padding: 5px;
            }
            table tbody tr {
                border-top: 1px solid #ccc;
            }
            h1 {
                font-size: 150%;
                font-weight: normal;
                text-align: left;
                color: #666;
                margin: 10px;
                padding: 0;
            }
            h2 {
                font-size: 130%;
                color: #666;
                font-weight: normal;
                text-align: left;
            }
            h3 {
                font-size: 105%;
                color: #666;
                text-align: left;
            }
            div.diff {
                width: 100%;
                border: 1px solid #ccc;
                padding: 5px;
                overflow: auto;
                max-height: 800px;
            }
            span.file {
                font-weight: bold;
            }
            div.copy {
                color: #aaa;
                font-size: 75%;
                margin: 20px 0 10px 10px;
            }
        </style>
	</head>
    <body>
        <h1>Javascript Lint</h1>
        <?php
        $jspath = '../../js';
        $jslintsources = array();
        $queue = array( $jspath );
        while ( !empty( $queue ) ) {
            $path = array_pop( $queue );
            $dir = opendir( $path );
            while ( false !== ( $file = readdir( $dir ) ) ) {
                switch ( $file ) {
                    case '.':
                    case '..':
                        break;
                    default:
                        if ( !is_dir( $path . '/' . $file ) && substr( $file, -3 ) == '.js' ) {
                            $jslintsources[ substr( $path . '/' . $file, strlen( $jspath . '/' ) ) ] = file_get_contents( $path . '/' . $file );
                        }
                        else if ( is_dir( $path . '/' . $file ) ) {
                            $queue[] = $path . '/' . $file;
                        }
                }
            }
        }
        ?>
        <div class="content">
            <span id="jslintnumerrors">no errors</span> in total
            <br /><br />
            <dl id="jslintresults">
            </dl>
        </div>
        <script type="text/javascript"><?php
            ob_start();
            ?>
            var lintnumerrors = 0;
            function Lint( file, source ) {
                var jssource;
                var results = document.getElementById( 'jslintresults' );
                var filename;
                
                filename = document.createElement( 'dt' );
                filelink = document.createElement( 'a' );
                filelink.href = '../../js/' + file;
                var loader = document.createElement( 'img' );
                loader.src = 'http://static.zino.gr/images/ajax-loader.gif';
                loader.style.cssFloat = 'right';
                filename.appendChild( loader );
                filelink.appendChild( document.createTextNode( file ) );
                filename.appendChild( filelink );
                results.appendChild( filename );
                var parseresult = document.createElement( 'dd' );
                results.appendChild( parseresult );
                
                setTimeout( function ( parseresult ) {
                    return function () {
                        var jslintresult = JSLINT( source, {
                            'laxbreak': true
                        } );
                        
                        if ( jslintresult === true ) {
                            parseresult.className = 'pass';
                            parseresult.appendChild( document.createTextNode( 'PASS' ) );
                        }
                        else {
                            parseresult.className = 'fail';
                            var table = document.createElement( 'table' );
                            var headlines = document.createElement( 'tr' );
                            var th;
                            var titles = [ 'error', 'source', 'line/char' ];
                            var i;
                            
                            for ( i = 0; i < titles.length; ++i ) {
                                th = document.createElement( 'th' );
                                th.appendChild( document.createTextNode( titles[ i ] ) );
                                headlines.appendChild( th );
                            }
                            table.appendChild( headlines );
                            var tr;
                            var td;
                            var evidence;
                            var chara;
                            var prefix;
                            var suffix;
                            var j = 0;
                            const MAXLINELEN = 61; // assert( ( MAXLINELEN - 1 ) % 2 == 0 )
                            const AREALEN = 5; // assert( ( AREALEN - 1 ) % 2 == 0 )
                            
                            for ( i = 0; i < JSLINT.errors.length; ++i ) {
                                if ( JSLINT.errors[ i ] !== null ) {
                                    tr = document.createElement( 'tr' );
                                    td = document.createElement( 'td' );
                                    td.appendChild( document.createTextNode( JSLINT.errors[ i ].reason ) );
                                    tr.appendChild( td );
                                    td = document.createElement( 'td' );
                                    if ( typeof JSLINT.errors[ i ].evidence == 'string' ) {
                                        evidence = JSLINT.errors[ i ].evidence;
                                        chara = JSLINT.errors[ i ].character;
                                        chara -= ( AREALEN - 1 ) / 2;
                                        if ( chara < 0 ) {
                                            chara = 0;
                                        }
                                        var leftpart = evidence.substr( 0, chara );
                                        var rightpart = evidence.substr( chara + AREALEN, evidence.length );
                                        var realevidence = evidence.substr( chara, AREALEN );
                                        
                                        chara = JSLINT.errors[ i ].character;
                                        chara -= ( MAXLINELEN - 1 ) / 2;
                                        if ( chara < 0 ) {
                                            chara = 0;
                                            prefix = '';
                                        }
                                        else {
                                            prefix = '...';
                                        }
                                        if ( evidence.length > chara + ( MAXLINELEN - 1 ) / 2 ) {
                                            suffix = '...';
                                        }
                                        else {
                                            suffix = '';
                                        }
                                        leftpart = prefix + leftpart.substr( chara, MAXLINELEN );
                                        rightpart = rightpart.substr( 0, ( MAXLINELEN - 1 ) / 2 ) + suffix;
                                        td.appendChild( document.createTextNode( leftpart ) );
                                        var b = document.createElement( 'span' );
                                        b.appendChild( document.createTextNode( realevidence ) );
                                        td.appendChild( b );
                                        td.appendChild( document.createTextNode( rightpart ) );
                                    }
                                    tr.appendChild( td );
                                    td = document.createElement( 'td' );
                                    td.appendChild( document.createTextNode( ( JSLINT.errors[ i ].line + 1 ) + '/' + JSLINT.errors[ i ].character ) );
                                    tr.appendChild( td );
                                    if ( j % 2 == 0 ) {
                                        tr.className = 'l';
                                    }
                                    table.appendChild( tr );
                                    ++j;
                                    ++lintnumerrors;
                                    document.getElementById( 'jslintnumerrors' ).firstChild.nodeValue = lintnumerrors + ((lintnumerrors == 1)? ' error': ' errors');
                                }
                            }
                            parseresult.appendChild( table );
                        }
                        var prev;
                        while ( prev = parseresult.previousSibling ) {
                            if ( prev.nodeName == 'dt' ) {
                                // remove the AJAX loader
                                prev.removeChild( prev.getElementsByTagName( 'img' )[ 0 ] );
                            }
                        }
                    }
                }( parseresult ), 50 );
            }
            var jslintsources = <?php
            echo json_encode( $jslintsources );
            ?>;
            
            j = 0;
            for ( i in jslintsources ) {
                if ( i.substr( i.length - 3, 3 ) == '.js' ) {
                    setTimeout( function ( file, source ) {
                        return function () {
                            Lint( file, source );
                        };
                    }( i, jslintsources[ i ] ), j * 100 );
                    ++j;
                }
            }
            
            <?php
            echo htmlspecialchars( ob_get_clean() );
            ?>
        </script>
        <div class="copy">Kamibu Developer Tools</div>
    </body>
</html>
