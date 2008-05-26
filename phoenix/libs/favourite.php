<?php

	define( 'FAVOURITE_JOURNAL', 1 );
	define( 'FAVOURITE_POLL', 2 );
	define( 'FAVOURITE_PHOTO', 4 );
	define( 'FAVOURITE_ALL', FAVOURITE_JOURNAL | FAVOURITE_POLL | FAVOURITE_PHOTO );

	function Favourites_TypeFromEntity( $entity ) {
		switch ( get_class( $entity ) ) {
			case "Journal":
				return FAVOURITE_JOURNAL;
			case "Poll":
				return FAVOURITE_POLL;
			case "Image":
				return FAVOURITE_PHOTO;
			default:
				throw new Exception( "Wrong entity in Favourites_TypeFromEntity" );
		}
	}

	class FavouriteFinder extends Finder {
		protected $mModel = 'Favourite';

		function FindByUserAndType( $user, $types ) {
			$prototype = New Favourite();
			$prototype->Typeid = $types;
			$prototype->Userid = $user->Id;

			return $this->FindByPrototype( $prototype );
		}
		function FindByUserAndEntity( $user, $entity ) {
			$prototype = New Favourite();
			$prototype->Typeid = Favourites_TypeFromEntity( $entity );
			$prototype->Itemid = $entity->Id;
			$prototype->Userid = $user->Id;

			return $this->FindByPrototype( $prototype );
		}
	}

    class Favourite extends Satori {
		protected $mDbTableAlias = 'favourites';

		public function Relations() {
			switch ( $this->Typeid ) {
				case FAVOURITE_JOURNAL:
					$class = 'Journal';
					break;
				case FAVOURITE_POLL:
					$class = 'Poll';
					break;
				case FAVOURITE_PHOTO:
					$class = 'Photo';
					break;
				default:
					throw new Exception( 'Unknown typeid on favourite' );
					break;
			}

			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->Item = $this->HasOne( $class, 'Itemid' );
		}
		public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
		}
    }

?>
