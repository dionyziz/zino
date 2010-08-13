<polls>
    <? if ( isset( $user ) ): ?>
    <author>
        <name><?= $user[ 'name' ] ?></name>
    </author>
    <? endif; ?>
	<? foreach ( $polls as $item ): ?>
    <poll id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
        <author>
            <name><?= $user[ 'name' ]; ?></name>
            <subdomain><?= $user[ 'subdomain' ]; ?></subdomain>
            <gender><?= $user[ 'gender' ]; ?></gender>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </poll>
    <? endforeach; ?>
</polls>
