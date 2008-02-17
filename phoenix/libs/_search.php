<?php

    /*
    class UserSearch extends Search {
        protected $mModel = 'User';

        public function GetLatest( $n = 5 ) {
            $this->Limit = $n;
            $this->OrderBy = 'Created';

            return $this->Get();
        }
    }
    */

    abstract class Search {
        protected $mDb;
        protected $mPrototype;
        protected $mLimit;
        protected $mOffset;
        protected $mSortBy;
        protected $mOrder;
        protected $mValues;

        protected function SetLimit( $limit ) {
            $this->mLimit = $limit;
        }
        protected function SetOffset( $offset ) {
            $this->mOffset = $offset;
        }
        protected function SetSortBy( $attribute, $prototype = false ) {
            if ( $prototype === false ) {
                $prototype = $this->mPrototype;
            }

            $field = $this->GetFieldFromAttribute( $attribute, $prototype );
            $table = $prototype->DbTable->Alias;

            $this->mSortBy = "`$table`.`$field`";
            $this->mOrder = 'DESC';
        }
        protected function SetOrder( $order ) {
            $this->mOrder = strtoupper( $order );
        }
        private function GetChangedAttributes() {
            $attributes = array();
            foreach ( $this->mPrototype as $attribute => $value ) {
                $attributes[ $attribute ] = $value;
            }

            die( print_r( $attributes ) );

            return $attributes;
        }
        private function GetFieldFromAttribute( $attribute, $prototype ) {
            $fields = $prototype->DbFields;
            foreach ( $fields as $field => $prototype_attribute ) {
                if ( $prototype_attribute == $attribute ) {
                    return $field;
                }
            }

            return false;
        }
        private function CreateQuery() {
            $query = 'SELECT * FROM ' . $this->mPrototype->DbTable . ' ';

            $table = $this->mPrototype->mDbTable->Alias;

            if ( count( $this->mValues ) ) {
                $query .= ' WHERE ';
                $first = true;
                foreach ( $this->mValues as $field => $value ) {
                    if ( !$first ) {
                        $query .= " AND ";
                    }
                    else {
                        $first = false;
                    }
                    $query .= " `$table`.`$field` = '$value' ";
                }
            }
            
            if ( $this->mSortBy != NULL ) {
                $query .= ' ORDER BY ' . $this->mSortBy . ' ' . $this->mOrder . ' ';
            }
            $query .= ' LIMIT ' . $this->mOffset . ', ' . $this->mLimit . ';';

            return $this->mDb->Prepare( $query );
        }
        public function Get() {
            $res = $this->CreateQuery()->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[] = New TestModel( $row );
            }

            return $ret;
        }
        public function __construct( $database = false ) {
            global $db;

            if ( $database !== false ) {
                $this->mDb = $database;
            }
            else {
                $this->mDb = $db;
            }

            $this->mLimit = 20;
            $this->mOffset = 0;

            $this->mPrototype = New $this->mModel(); // MAGIC!
        }
    }

?>
