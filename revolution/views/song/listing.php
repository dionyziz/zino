<songs>
    <query><?= $query ?></query>
    <? foreach ( $songs as $song ): ?>
        <song id="<?= $song[ 'SongID' ] ?>">
            <name><?= htmlspecialchars( $song[ 'SongName' ] ) ?></name>
            <artist id="<?= $song[ 'ArtistID' ] ?>">
                <name><?= htmlspecialchars( $song[ 'ArtistName' ] ) ?></name>
            </artist>
            <album id="<?= $song[ 'AlbumID' ] ?>">
               <name><?= htmlspecialchars( $song[ 'AlbumName' ] ) ?></name>
            </album>
        </song>
    <? endforeach; ?>
</songs>
