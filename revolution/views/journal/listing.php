<stream type="journal">
	<? foreach ( $journals as $item ): ?>
    <entry type="journal" id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
        <author>
            <name><?= $item[ 'user' ][ 'name' ]; ?></name>
            <subdomain><?= $item[ 'user' ][ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'user' ][ 'gender' ]; ?></gender>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
        <title><?= htmlspecialchars( $item[ 'title' ] ) ?></title>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </entry>
    <? endforeach; ?>
</stream>
