<operation resource="user" method="create">
    <? if ( empty( $error ) ): ?>
        <result>SUCCESS</result>
        <user id="<?= $user[ 'id' ] ?>">
            <name><?= $user[ 'name' ] ?></name>
            <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
            <email><?= $user[ 'email' ] ?></email>
        </user>
    <? else: ?>
        <result>FAIL</result>
        <error><?= $error ?></error>
    <? endif; ?>
</operation>
