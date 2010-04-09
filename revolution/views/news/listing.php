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
        <? switch ( $item[ 'type' ] ):
           case 'poll': ?>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <? break; ?>
        <? case 'photo': ?>
        <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $item[ 'id' ] ?>/<?= $item[ 'id' ] ?>_150.jpg" />
        <? break; ?>
        <? case 'journal': ?>
        <title><?= htmlspecialchars( $item[ 'title' ] ) ?></title>
        <? endswitch; ?>
    </entry>
    <? endforeach; ?>
</feed>
