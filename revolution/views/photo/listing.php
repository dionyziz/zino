<feed type="photos">
    <? global $settings;
    foreach ( $photos as $photo ): ?>
    <entry id="<?= $photo[ 'id' ] ?>">
        <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_150.jpg" />
        <discussion count="<?= $photo[ 'numcomments' ] ?>" />
    </entry>
    <? endforeach; ?>
</feed>
