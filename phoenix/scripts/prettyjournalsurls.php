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

    $query = $db->Prepare( 'SELECT * FROM :journals' );
    $query->BindTable( 'journals' );
    $res = $query->Execute();

    $journals = array();
    while ( $row = $res->FetchArray() ) {
        $userId = $row[ 'journal_userid' ];
        if ( !isset( $journals[ $userId ] ) ) {
            $journals[ $userId ] = array();
        }
        $journals[ $userId ][] = array(
            'id' => $row[ 'journal_id' ],
            'title' => $row[ 'journal_title' ]
        );
    }

    $result = array();
    foreach ( $journals as $userId => $hisJournals ) {
        $urls = array();
        foreach ( $hisJournals as $journalInfo ) {
            $candidate = URL_Format( $journalInfo[ 'title' ] );
            while ( isset( $urls[ $candidate ] ) ) {
                $candidate .= '_';
            }
            $urls[ $candidate ] = true;
            $result[ $journalInfo[ 'id' ] ] = $candidate;
        }
    }

    $i = 0;
    foreach ( $result as $id => $url ) {
        if ( $i >= $offset && $i <= $offset + $limit ) {
            $query = $db->Prepare(
                'UPDATE
                    :journals 
                SET
                    `journal_url` = :journal_url
                WHERE
                    `journal_id` = :journal_id
                LIMIT 1;'
            );
            $query->BindTable( 'journals' );
            $query->Bind( 'journal_url', $url );
            $query->Bind( 'journal_id', $id );
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
        ?>.<?php
        $water->Post();
        ?>
        <script type="text/javascript">
        setTimeout( function() {
            window.location.href = "prettyjournalsurls.php?offset=<?php
            echo $offset;
            ?>";
        }, 1000 );
        </script>
        </body></html><?php
    }

    Rabbit_Destruct();

?>
