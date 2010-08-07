
<comment id="<?= $comment[ 'id' ] ?>">
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? if ( isset( $user[ 'age' ] ) ): ?>
        <age><?= $user[ 'age' ]; ?></age>
        <? endif; ?>
        <? if ( isset( $user[ 'place' ] ) ): ?>
        <location><?= $user[ 'place' ][ 'name' ]; ?></location>
        <? endif; ?>
        <? if ( !empty( $user[ 'avatarid' ] ) ): ?>

        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
        <? endif; ?>

    </author>
    <? if ( isset( $comment[ 'created' ] ) ): ?>
    <published><?= $comment[ 'created' ] ?></published>
    <? endif; ?>
    <text><?= $comment[ 'text' ]; ?></text>
</comment>
