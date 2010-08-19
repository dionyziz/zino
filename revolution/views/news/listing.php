<news>
	<? foreach ( $content as $item ): ?>
    <<?= $item[ 'type' ] ?> id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
            <? if ( $item[ 'avatarid' ] ): ?>
                <avatar>
                    <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
                </avatar>
            <? endif ?>
        </author>
        <? switch ( $item[ 'type' ] ):
           case 'poll': ?>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <? break; ?>
        <? case 'photo': ?>
        <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'id' ] ?>/<?= $item[ 'id' ] ?>_150.jpg" />
        <? break; ?>
        <? case 'journal': ?>
        <title><?= htmlspecialchars( $item[ 'title' ] ) ?></title>
        <? endswitch; ?>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </<?= $item[ 'type' ] ?>>
    <? endforeach; ?>
</news>
