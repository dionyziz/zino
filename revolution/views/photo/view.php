<entry id="<?= $photo[ 'id' ] ?>">
    <author>
        <name><?= $photo[ 'username' ] ?></name>
        <subdomain><?= $photo[ 'subdomain' ] ?></subdomain>
        <gender><?= $photo[ 'gender' ] ?></gender>
    </author>
    <published><?= $photo[ 'created' ] ?></published>
    <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_full.jpg" width="<?= $photo[ 'w' ] ?>" height="<?= $photo[ 'h' ] ?>" /><?
    include 'views/comment/listing.php';
    if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</entry>
