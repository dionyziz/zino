<operation resource="presence" method="create">
    <? if ( $info !== false ): ?>
    <result>SUCCESS</result>
    <data>
        <user id="<?= $userid ?>">
            <name><?= $info[ 'name' ] ?></name>
        </user>
    </data>
        <name><?= $info[ 'name' ] ?></name>
    <? else: ?>
    <result>FAIL</result>
    <? endif; ?>
</operation>
