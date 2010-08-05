<crowd>
    <? foreach ( $users as $user ): ?>
    <user id="<?= $user[ 'id' ] ?>" state="<?= $user[ 'state' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
        <gender><?= $user[ 'gender' ] ?></gender>
        <location><?= $user[ 'location' ] ?></location>
        <age><?= $user[ 'age' ] ?></age>
        <avatar id="<?= $user[ 'avatarid' ] ?>">
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
    </user>
    <? endforeach; ?>
</crowd>

