<?php
class Elementfavouritefavourite extends Element {
	/*
	So I still know shit on PHP/Rabbit/Satori, So this code is being written
	while learning stuff from albums element. Everything here is far from
	complete -just trying to make things work, at the moment.
	
	Gatoni
	*/
	public function Render( tText $username) {
			// Load libraries
			global $libs;
			$libs->Load( 'favourite' );
			$libs->Load( 'journal' );

			// Get username as a parameter, and get the user object using UserFinder
			$username = $username->Get();
			$ufinder = new UserFinder();
			$mUser = $ufinder->FindByName( $username );
			
			// Find all user's favourite journals
			$favfinder = new FavouriteFinder();
			$favourites = $finder->FindByUserAndType( $this->$mUser->Id, TYPE_JOURNAL );

			// print what you have find
			foreach ( $favourites as $value ) {
				echo $value;
			}
		}
    }
?>
