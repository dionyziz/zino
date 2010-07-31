<operation resource="friendship" method="create">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
        <friend id="<?= $friendid; ?>"></friend>
    <? else: ?>
        <result>FAIL</result>
    <? endif; ?>
</operation>
