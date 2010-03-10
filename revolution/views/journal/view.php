<entry id="<?= $journal[ 'id' ] ?>">
    <title><?= htmlspecialchars( $journal[ 'title' ] ) ?></title>
    <author>
        <name><?= $journal[ 'username' ] ?></name>
        <subdomain><?= $journal[ 'subdomain' ] ?></subdomain>
        <gender><?= $journal[ 'gender' ] ?></gender>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $journal[ 'userid' ] ?>/<?= $journal[ 'avatarid' ] ?>/<?= $journal[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
    </author>
    <published><?= $journal[ 'created' ] ?></published>
    <? include 'views/comment/listing.php'; ?>
    <? if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</entry>
