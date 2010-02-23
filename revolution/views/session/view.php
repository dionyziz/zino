<session>
    <? if ( $user !== false ): ?>
    <userid><?= $user[ 'id' ] ?></userid>
    <authtoken><?= $user[ 'authtoken' ] ?></authtoken>
    <? else: ?>
    <userid>0</userid>
    <? endif; ?>
</session>
