<?php

	$types = array(
		'SCHOOL_ELEMENTARY',
		'SCHOOL_JUNIORHIGH',
		'SCHOOL_HIGH',
		'SCHOOL_TEE',
		'SCHOOL_TEI',
		'SCHOOL_AEI'
	);

	foreach ( $types as $number => $type ) {
		define( $type, $number + 1 );
	}

	class SchoolException extends Exception {
	}

	class SchoolFinder extends Finder {
		protected $mModel = 'School';

		public function Find( $placeid = false, $typeid = false, $offset = 0, $limit = 10000 ) {
			$prototype = New School();
			if ( $placeid !== false ) {
				$prototype->Placeid = $placeid;
			}
			if ( $typeid !== false ) {
				$prototype->Typeid = $typeid;
			}
			return $this->FindByPrototype( $prototype, $offset, $limit, 'Name' );
		}

		public function Count() {
			$query = $this->mDb->Prepare( 'SELECT COUNT(*) AS schoolsnum FROM :schools;' );
			$query->BindTable( 'schools' );
			$res = $query->Execute();
			$row = $res->FetchArray();
			return ( int )$row[ 'schoolsnum' ];
		}
	}

	class School extends Satori {
		protected $mDbTableAlias = 'schools';

		public function __set( $key, $value ) {
			if ( $key == 'Typeid' ) {
				if ( $value < 1 || $value > 6 ) {
					throw New SchoolException( 'Type id must be between 1 and 6, inclusive' );
				}
			}
			parent::__set( $key, $value );
		}

		protected function Relations() {
			$this->Place = $this->HasOne( 'Place', 'Placeid' );
			$this->Users = $this->HasMany( 'UserFinder', 'FindBySchool', $this );
		}

		protected function LoadDefaults() {
			$this->Created = NowDate();
			$this->Approved = 0;
		}
	}

?>
