
<comment id="<?= $comment[ 'id' ] ?>">
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? if ( $user[ 'avatarid' ] ): ?>

        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
        <? endif; ?>

    </author>
    <published><?= $comment[ 'created' ] ?></published>
    <text><?= $comment[ 'text' ]; ?></text>
</comment>
