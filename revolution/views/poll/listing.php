<stream type="poll">
	<? foreach ( $polls as $item ): ?>
    <entry type="poll" id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </entry>
    <? endforeach; ?>
</stream>

