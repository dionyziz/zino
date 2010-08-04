<songs>
    <query><?= $query ?></query>
    <? foreach ( $songs as $song ): ?>
        <song id="<?= $song[ 'songID' ] ?>">
            <name><?= htmlspecialchars( $song[ 'songName' ] ) ?></name>
            <media url="<?= $song[ 'image' ][ 'small' ] ?>" />
            <duration><?= $song[ 'estDurationSecs' ] ?></duration>
            <? if ( $song[ 'genreID' ] != 0 ): ?>
            <genre id="<?= $song[ 'genreID' ] ?>">
                <name><?= htmlspecialchars( $song[ 'genreName' ] ) ?></name>
            </genre>
            <? endif; ?>
            <artist id="<?= $song[ 'artistID' ] ?>">
                <name><?= htmlspecialchars( $song[ 'artistName' ] ) ?></name>
            </artist>
            <album id="<?= $song[ 'albumID' ] ?>">
               <name><?= htmlspecialchars( $song[ 'albumName' ] ) ?></name>
            </album>
        </song>
    <? endforeach; ?>
</songs>
