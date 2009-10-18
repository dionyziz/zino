<?php
        class BadgeFinder extends Finder{
		protected $mModel = 'Badge';
		public function FindByItemIds( $ids ){
			$query = $this->mDb->Prepare(
                        'SELECT
                            *
                        FROM
                            :badges
                        WHERE
                            `badge_id` IN :ids
                        LIMIT
                            1000'
                        );
                        $query->BindTable( 'badges' );
                        $query->Bind( 'ids', $ids );
                        $res = $query->Execute();
                        $data = array();
                        while ( $row = $res->FetchArray() ) { 
                                $data[] = new Badge( $row );
                        }
                        return $data;
		}
	}

        class Badge extends Satori{
		protected $mDbTableAlias = 'badges';
	}
?>
