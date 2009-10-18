<?php
        class BadgeFinder extends Finder{
		protected $mModel = 'Badge';
		public function FindByItemIds( $ids ){
			return $this->FindByIds( $ids );
		}
	}

        class Badge extends Satori{
		protected $mDbTableAlias = 'badges';
	}
?>
