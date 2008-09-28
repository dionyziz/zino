<?php

	class RelationTypeFinder extends Finder {
		protected $mModel = 'RelationType';

		public function FindAll( $offset = 0, $limit = 10000 ) {
			$prototype = New RelationType();
			
			return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Text', 'ASC' ) );
		}
		public function FindByText( $text ) {
			$prototype = New RelationType();
			$prototype->Text = $text;

			return $this->FindByPrototype( $prototype );
		}
	}

	class RelationType extends Satori {
		protected $mDbTableAlias = 'relationtypes';

		public function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
		}
		public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
			$this->Created = NowDate();
		}
	}

?>
