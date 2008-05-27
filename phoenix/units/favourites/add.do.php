<?php
	
	function UnitFavouritesAdd( tInteger $itemid , tInteger $typeid ) {
		?>alert( 'itemid: <?php echo $itemid->Get(); ?>' );
		alert( 'typeid: <?php echo $typeid->Get(); ?>' );<?php
	}
?>
