<?php
class Elementfavouritefavourite extends Element {
	/*
	So I still know shit on PHP/Rabbit/Satori, So this code is being written
	while learning stuff from albums element. Everything here is far from
	complete -just trying to make things work, at the moment.
	
	Gatoni
	*/
	public function Render( tInteger $userid ) {
			// Load libraries
			global $libs;
			$libs->Load( 'favourite' );
			$libs->Load( 'journal' );
			
			$userid = $userid->Get();
			
			// Find all user's favourite journals
			$favfinder = new FavouriteFinder();
			$favourites = $favfinder->FindByUserAndType( $this->$userid, TYPE_JOURNAL );

			// print what you have find
			foreach ( $favourites as $value ) {
				echo "id: " . $value->Itemid;
			}
		}
    }
?>
