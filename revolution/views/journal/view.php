<journal id="<?= $journal[ 'id' ] ?>">
    <title><?= htmlspecialchars( $journal[ 'title' ] ) ?></title>
    <? if ( isset( $user ) ): ?>
        <author>
            <name><?= $user[ 'name' ] ?></name>
            <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
            <? if ( isset( $user[ 'gender' ] ) ): ?>
            <gender><?= $user[ 'gender' ] ?></gender>
            <? endif; ?>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
    <? endif; ?>
    <published><?= $journal[ 'created' ] ?></published>
    <text><?= $journal[ 'text' ] ?></text>
    <?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</journal>
