<operation resource="session" method="create">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
    <? else: ?>
        <result>FAIL</result>
		<cause><?= $cause ?></cause>
    <? endif; ?>
		<user>
			<name><?= $name ?></name>
		</user>
</operation>
