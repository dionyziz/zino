<stream>
    <? foreach ( $favourites as $favourite ): ?>
	<? switch ( $favourite[ 'typeid' ] ):
    case TYPE_JOURNAL: 
    ?>

    <entry id="<?= $favourite[ "id" ] ?>" type="journal">
	<?
	break;
	endswitch; ?>
	<? endforeach; ?>
</stream>
