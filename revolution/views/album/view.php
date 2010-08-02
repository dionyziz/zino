<? if ( $album === false || $album[ 'delid' ] == 1 ): ?>
<album id="<?= $album[ 'id' ] ?>" deleted="yes">
<? else: ?>
<album id="<?= $album[ 'id' ] ?>">
    <author>
        <name><?= $user[ 'name' ] ?></name>
    </author>
    <? include 'views/photo/listing.php'; ?>
<? endif; ?>
</album>
