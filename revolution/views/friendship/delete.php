<operation resource="friendship" method="delete">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
        <friend id="<?= $friendid; ?>"></friend>
    <? else: ?>
        <result>FAIL</result>
    <? endif; ?>
</operation>
