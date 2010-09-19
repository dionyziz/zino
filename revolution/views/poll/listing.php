<polls>
    <? if ( isset( $user ) ): ?>
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <? if ( isset( $user[ 'gender' ] ) ): ?>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? endif; ?>
    </author>
    <? endif; ?>
	<? foreach ( $polls as $item ): ?>
    <poll id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
         <? if ( isset( $user ) ): ?>
            <author>
                <name><?= $user[ 'name' ]; ?></name>
                <subdomain><?= $user[ 'subdomain' ]; ?></subdomain>
                <gender><?= $user[ 'gender' ]; ?></gender>
                <avatar>
                    <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
                </avatar>
            </author>
        <? endif; ?>
        <? if ( !isset( $user ) ): ?>
            <author>
                <name><?= $item[ 'user' ][ 'name' ]; ?></name>
                <subdomain><?= $item[ 'user' ][ 'subdomain' ]; ?></subdomain>
                <gender><?= $item[ 'user' ][ 'gender' ]; ?></gender>
                <avatar>
                    <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
                </avatar>
            </author>
        <? endif; ?>        
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= htmlspecialchars( $item[ 'question' ] ) ?></question>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </poll>
    <? endforeach; ?>
</polls>
