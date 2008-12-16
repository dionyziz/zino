<?php
    global $water;

    $offset = ( integer )$_GET[ 'offset' ];
    $limit = 100;

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;
    global $db;

    $libs->Load( 'url' );

    $query = $db->Prepare( 'SELECT * FROM :polls' );
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
            'question' => $row[ 'poll_question' ]
        );
    }

    $result = array();
    foreach ( $polls as $userId => $hispolls ) {
        $urls = array();
        foreach ( $hispolls as $pollInfo ) {
            $candidate = URL_Format( $pollInfo[ 'question' ] );
            $i = 0;
            while ( isset( $urls[ $candidate ] ) ) {
                ++$i;
                if ( $i <= 254 ) {
                    $candidate .= '_';
                }
                else {
                    $candidate[ rand( 0, strlen( $candidate ) - 1 ) ] = '_';
                }
            }
            $urls[ $candidate ] = true;
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
        ?><html><head><question>Processing...</question>
        </head><body>
        Processed <?php
        echo $offset;
        ?> out of <?php
        echo count( $result );
        ?>.<?php
        $water->Post();
        ?>
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
