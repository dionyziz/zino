<songs>
    <query><?= $query ?></query>
    <? foreach ( $songs as $song ): ?>
        <song id="<?= $song[ 'SongID' ] ?>">
            <name><?= htmlspecialchars( $song[ 'SongName' ] ) ?></name>
            <!-- 
            <media url="<?= $song[ 'image' ][ 'small' ] ?>" />
            <duration><?= $song[ 'estDurationSecs' ] ?></duration>
            <? if ( $song[ 'genreID' ] != 0 ): ?>
            <genre id="<?= $song[ 'genreID' ] ?>">
                <name><?= htmlspecialchars( $song[ 'genreName' ] ) ?></name>
            </genre>
            <? endif; ?> 
            -->
            <artist id="<?= $song[ 'ArtistID' ] ?>">
                <name><?= htmlspecialchars( $song[ 'ArtistName' ] ) ?></name>
            </artist>
            <album id="<?= $song[ 'AlbumID' ] ?>">
               <name><?= htmlspecialchars( $song[ 'AlbumName' ] ) ?></name>
            </album>
        </song>
    <? endforeach; ?>
</songs>
