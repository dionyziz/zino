<songs>
    <? foreach ( $songs as $song ): ?>
        <song id="<?= $song[ 'songid' ] ?>" />
    <? endforeach; ?>
</songs>
