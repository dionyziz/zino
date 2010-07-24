<operation resource="presence" method="delete">
    <? if ( $success ): ?>
    <result>SUCCESS</result>
    <data>
        <user id="<?= $userid ?>">
            <name><?= $username ?></name>
        </user>
    </data>
    <? else: ?>
    <result>FAIL</result>
    <? endif; ?>
</operation>
