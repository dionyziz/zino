<photos>
    <? if ( isset( $user ) ): ?>
    <author id="<?= $user[ 'id' ] ?>">
        <gender><?= $user[ 'gender' ] ?></gender>
        <name><?= $user[ 'name' ] ?></name>
    </author>
    <? endif;
    global $settings;
    foreach ( $photos as $photo ): ?>
    <photo id="<?= $photo[ 'id' ] ?>">
        <? if ( isset( $photo[ 'user' ] ) ): ?>
        <author id="<?= $photo[ 'user' ][ 'id' ] ?>">
            <name><?= $photo[ 'user' ][ 'name' ] ?></name>
        </author>
        <? endif; ?>
        <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_150.jpg" />
        <discussion count="<?= $photo[ 'numcomments' ] ?>" />
    </photo>
    <? endforeach; ?>
</photos>
