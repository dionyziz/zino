<?php
    for ( $i = 0; $i < 15; ++$i ) {
        ?>Downloading export #<?php
        echo $i;
        ?>...

        <?php
        $file = file_get_contents( 'http://www.zino.gr/scripts/tophoenix/export_casual?step=' . $i );
        file_put_contents( '/home/dionyziz/migrate/' . $i . '.sql.gz' );
    }
    ?>Done downloading.
    
    <?php
?>
