<operation resource="pollvote" method="create">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
    <? else: ?>
        <result>FAIL</result>
    <? endif; ?>
</operation>
<entry>
	<options totalvotes="<?= $poll[ 'numvotes' ] ?>">
        <? foreach ( $options as $option ): ?>
        <option id="<?= $option[ 'id' ] ?>" votes="<?= $option[ 'numvotes' ] ?>">
            <title><?= htmlspecialchars( $option[ 'text' ] ) ?></title>
        </option>
        <? endforeach; ?>
    </options>
</entry>