<operation resource="session" method="create">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
    <? else: ?>
        <result>FAIL</result>
    <? endif; ?>
		<user>
			<name><?= $name ?></name>
		</user>
</operation>
