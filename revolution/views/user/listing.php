<crowd>
    <? foreach ( $users as $user ): ?>
    <user id="<?= $user[ 'id' ] ?>" state="<?= $user[ 'state' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
        <gender><?= $user[ 'gender' ] ?></gender>
        <location><?= $user[ 'location' ] ?></location>
        <age><?= $user[ 'age' ] ?></age>
        <avatar id="<?= $user[ 'avatarid' ] ?>">
            <media url="" />
        </avatar>
    </user>
    <? endforeach; ?>
</crowd>

