<operation resource="pollvote" method="create">
    <? if ( $success ): ?>
        <result>SUCCESS</result>
    <? else: ?>
        <result>FAIL</result>
    <? endif; ?>
</operation>
<?
if ( !$success ):
    return;
endif;
?>
<poll>
    <title><?= htmlspecialchars( $poll[ 'question' ] ) ?></title>
	<options totalvotes="<?= $poll[ 'numvotes' ] ?>">
        <? foreach ( $options as $option ): ?>
        <option id="<?= $option[ 'id' ] ?>" votes="<?= $option[ 'numvotes' ] ?>"<?
            if ( $option[ 'id' ] == $optionid ):
                ?> voted="yes"<?
            endif;
            ?>>
            <title><?= htmlspecialchars( $option[ 'text' ] ) ?></title>
        </option>
        <? endforeach; ?>
    </options>
</poll>
