<crowd>
    <? foreach ( $users as $user ): ?>
    <user id="<?= $user[ 'id' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
    </user>
    <? endforeach; ?>
</crowd>

