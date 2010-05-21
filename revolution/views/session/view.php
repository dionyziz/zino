<session>
    <? if ( $user !== false ): ?>
    <user id="<?= $user[ 'id' ] ?>" />
    <authtoken><?= $user[ 'authtoken' ] ?></authtoken>
    <? else: ?>
    <userid>0</userid>
    <? endif; ?>
</session>
