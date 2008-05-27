<?php
	
	function UnitFavouritesAdd( tInteger $itemid , tInteger $typeid ) {
		
		$favourite = New Favourite();
		$favourite->Itemid = $itemid->Get();
		$favourite->Typeid = $typeid->Get();
		$favourite->Save();
	}
?>
