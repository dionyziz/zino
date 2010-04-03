<feed type="news">
	<? foreach ( $content as $item ): ?>
    <entry type="<?= $item[ 'type' ] ?>" id="<?= $item[ 'id' ]; ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
        <? if ( $item[ 'type' ] == 'poll' ): ?>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <? else: ?>
        <title><?= htmlspecialchars( $item[ 'title' ] ) ?></title>
        <? endif; ?>
    </entry>
    <? endforeach; ?>
</feed>
