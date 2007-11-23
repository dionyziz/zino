<?php

	abstract class Search {
		protected $mIndex;
		protected $mTables;
		protected $mFields;
		protected $mFilters;
		protected $mNegativeFilters;
		protected $mSortField;
		protected $mGroupByField;
		protected $mRelations;
		private $mSortOrder;
		private $mLimit;
		private $mOffset;
		private $mGetTotalLength;
		private $mCount;
		private $mLength;
		private $mExtraFields;
		
		protected function SetRelation( $relation ) {
			$this->mRelations[] = $relation;
		}
		protected function SetSortOrder( $order ) {
			$order = mystrtoupper( $order );
			w_assert( $order == "DESC" || $order == "ASC" );
			
			$this->mSortOrder = $order;
		}
		protected function SetGroupByField( $field ) {
			$this->mGroupByField = $field;
		}
		public function SetLimit( $limit ) {
			w_assert( is_numeric( $limit ) && $limit >= 0 );
			
			$this->mLimit = $limit;
		}
		public function SetOffset( $offset ) {
			w_assert( is_numeric( $offset ) && $offset >= 0 );
			
			$this->mOffset = $offset;
		}
		public function NeedTotalLength( $yesorno ) {
			w_assert( $yesorno === true || $yesorno === false );
			
			$this->mGetTotalLength = $yesorno;
		}
        public function Length() {
            return $this->mLength;
        }
		protected function GetCached() {
			return false;
		}
		protected function SaveCache( $ret ) {
			return false;
		}
		public function Get() {
			global $db;
			global $blk;
			global $water;
            
			$cached = $this->GetCached();
			if ( $cached !== false ) {
				return $cached;
			}
			
            $water->Profile( 'Search ' . get_class( $this ) );
            
			$tables = array();
			$tablesstr = '';
			foreach ( $this->mTables as $table ) {
				$tablename = $table[ 'name' ];
				$jointype = isset( $table[ 'jointype' ] ) ? $table[ 'jointype' ] : '';
				if ( $jointype == 'LEFT JOIN' || $jointype == 'INNER JOIN' ) {
					$joincondition = $table[ 'on' ];
					w_assert( !empty( $tablesstr ) );
					$tablesstr .= ' ' . $jointype . ' `' . $tablename . '` ';
					if ( isset( $table[ 'as' ] ) ) {
						$tablesstr .= 'AS ' . $table[ 'as' ] . ' ';
					}
					$tablesstr .= 'ON ' . $joincondition;
				}
				else {
					if ( !empty( $tablesstr ) ) {
						$tablesstr .= ',';
					}
					$tablesstr .= '`' . $tablename . '`';
					if ( isset( $table[ 'as' ] ) ) {
						$tablesstr .= ' AS ' . $table[ 'as' ];
					}
				}
			}
			
			$fields = array();
			foreach ( $this->mFields as $field => $alias ) {
				$fields[] = "$field AS $alias";
			}
			$fieldstext = implode( ',' , $fields );
			
			$filters = array();
			foreach ( $this->mFilters as $filter ) {
				$fields = $filter[ 0 ];
				$operator = $filter[ 1 ];
				$value = $filter[ 2 ];
				if ( is_array( $fields ) ) {
					$wasarray = true;
					$condition = '(';
				}
				else {
					$fields = array( $fields );
					$wasarray = false;
					$condition = '';
				}
				while ( $field = array_shift( $fields ) ) {
					$dotpos = strpos( $field, '.' ); // table.field format
					$condition .= $field;
					switch ( $operator ) {
						case 0:
							$condition .= ' = \'' . myescape( $value ) . '\'';
							break;
						case 1:
							$condition .= ' LIKE \'%' . str_replace( '%' , '\\%' , myescape( $value ) ) . '%\'';
					}
					if ( count( $fields ) ) {
						$condition .= ' OR ';
					}
					else if ( $wasarray ) {
						$condition .= ') ';
					}
				}
				$filters[] = $condition;
			}
			
			foreach( $this->mNegativeFilters as $filter ) {
				$fields = $filter[ 0 ];
				$operator = $filter[ 1 ];
				$value = $filter[ 2 ];
				if ( is_array( $fields ) ) {
					$wasarray = true;
					$condition = '(';
				}
				else {
					$fields = array( $fields );
					$wasarray = false;
					$condition = '';
				}
				while ( $field = array_shift( $fields ) ) {
					$dotpos = strpos( $field, '.' ); // table.field format
					$condition .= $field;
					switch ( $operator ) {
						case 0:
							$condition .= ' != \'' . myescape( $value ) . '\'';
							break;
						case 1:
							$condition .= ' NOT LIKE \'%' . str_replace( '%' , '\\%' , myescape( $value ) ) . '%\'';
					}
					if ( count( $fields ) ) {
						$condition .= ' OR ';
					}
					else if ( $wasarray ) {
						$condition .= ') ';
					}
				}
				$filters[] = $condition;
			}
			
			$this->mRelations = array_merge( $this->mRelations , $filters );			
			$filtertext = implode( ' AND ' , $this->mRelations );
			
			$sql = "SELECT
						";
			if ( $this->mGetTotalLength ) {
				$sql .= " SQL_CALC_FOUND_ROWS ";
			}
			$sql .= $fieldstext . "
					FROM
						" . $tablesstr . "
					WHERE
						" . $filtertext;
            if ( $this->mGroupByField != '' ) {
                w_assert( preg_match( '#^[A-Za-z0-9_]+$#', $this->mGroupByField ) );
                $sql .= "
    					GROUP BY
    						`" . $this->mGroupByField . "`";
            }
			$sql .= "
                    ORDER BY 
						" . $this->mSortField . " " . $this->mSortOrder . "
					LIMIT 
						" . $this->mOffset . ", " . $this->mLimit . ";";
			
			$res = $db->Query( $sql );
			$this->mCount = $res->NumRows();
			
			if ( $this->mGetTotalLength ) {
				$sql = 'SELECT FOUND_ROWS() AS fr';
				$rows = $db->Query( $sql );
				$row = $rows->FetchArray();
				$this->mLength = $row[ 'fr' ];
			}
			$ret = $this->Instantiate( $res );
			$this->SaveCache( $ret );
			
            $water->ProfileEnd();
            
			return $ret;
		}
		public function Search() {
			$this->mFilters = array();
			$this->mNegativeFilters = array();
			$this->SetOffset( 0 ); // default offset
			$this->SetLimit( 20 ); // default limit
			$this->NeedTotalLength( false ); // by default, don't fetch the total length
		}
	}
    
?>
