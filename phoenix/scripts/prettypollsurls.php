<?php
    return;
        
    global $water;

    $offset = ( integer )$_GET[ 'offset' ];

    $limit = 100;

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;
    global $db;

    $libs->Load( 'url' );

    if ( $offset === 0 ) {
        $query = $db->Prepare( 'UPDATE :polls SET `poll_url` = NULL WHERE NOT (`poll_url` IS NULL)' );
        $query->BindTable( 'polls' );
        $query->Execute();
    }
    $query = $db->Prepare( 'SELECT * FROM :polls ORDER BY `poll_id`' );
    $query->BindTable( 'polls' );
    $res = $query->Execute();

    $polls = array();
    while ( $row = $res->FetchArray() ) {
        $userId = $row[ 'poll_userid' ];
        if ( !isset( $polls[ $userId ] ) ) {
            $polls[ $userId ] = array();
        }
        $polls[ $userId ][] = array(
            'id' => $row[ 'poll_id' ],
            'question' => substr( $row[ 'poll_question' ], 0, 255 )
        );
    }

    $result = array();
    foreach ( $polls as $userId => $hisPolls ) {
        $urls = array();
        foreach ( $hisPolls as $pollInfo ) {
            $candidate = URL_Format( $pollInfo[ 'question' ] );
            $length = strlen( $candidate );
            while ( isset( $urls[ strtolower( $candidate ) ] ) ) {
                if ( $length < 255 ) {
                    $candidate .= '_';
                    ++$length;
                }
                else {
                    $candidate[ rand( 0, $length - 1 ) ] = '_';
                }
            }
            $urls[ strtolower( $candidate ) ] = true;
            $result[ $pollInfo[ 'id' ] ] = $candidate;
        }
    }

    $i = 0;
    foreach ( $result as $id => $url ) {
        if ( $i >= $offset && $i <= $offset + $limit ) {
            $query = $db->Prepare(
                'UPDATE
                    :polls 
                SET
                    `poll_url` = :poll_url
                WHERE
                    `poll_id` = :poll_id
                LIMIT 1;'
            );
            $query->BindTable( 'polls' );
            $query->Bind( 'poll_url', $url );
            $query->Bind( 'poll_id', $id );
            $query->Execute();
        }
        ++$i;
    }
    if ( $offset + $limit <= count( $result ) ) {
        $offset += $limit;
        ?><html><head><title>Processing...</title>
        </head><body>
        Processed <?php
        echo $offset;
        ?> out of <?php
        echo count( $result );
        ?>.
        <script type="text/javascript">
        setTimeout( function() {
            window.location.href = "prettypollsurls.php?offset=<?php
            echo $offset;
            ?>";
        }, 1000 );
        </script>
        </body></html><?php
    }

    Rabbit_Destruct();

?>
