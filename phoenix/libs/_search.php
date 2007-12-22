<?php

    /* new Search Library proposal 
        not ready yet
        developer: abresas
    */

    global $libs;
    $libs->Load( 'prototype/search' );

    class Search {
        public $Limit;
        public $Offset;
        public $SortTable;
        public $SortField;
        public $SortOrder;
        public $GroupByTable;
        public $GroupByField;
        protected $mQuery;
        protected $mPrototypes;
        protected $mConnections;

        private function GetPrototype( $n ) { // get nth prototype
            $prototypeClasses = array_keys( $this->mPrototypes );
            $prototypeNth     = $prototypeClasses[ $n ];
            return $this->mPrototypes[ $prototypeNth ];
        }
        private function ValidatePrototype( $prototype ) {
            w_assert( $prototype instanceof SearchPrototype );
            w_assert( isset( $this->mPrototypes[ $prototype->GetClass() ] ), "Trying to use a prototype that is not added" );
        }
        private function CountTotalValues() {
            $count = 0;
            foreach ( $this->mPrototypes as $prototype ) {
                $count += count( $prototype->GetValues() );
            }

            return $count;
        }
        public function Connect( $prototype1, $prototype2, $type = 'cross' ) {
            global $water;

            $this->ValidatePrototype( $prototype1 );
            $this->ValidatePrototype( $prototype2 );

            $this->mConnections[ $prototype1->GetClass() ][] = array( "to" => $prototype2->GetClass(), "type" => strtoupper( $type ) );

            $water->Trace( "Search: connected prototype " . $prototype1->GetClass() . " with " . $prototype2->GetClass() );
            $water->Trace( "Search: connection type: $type" );
        }
        public function AddPrototype( $prototype, $connectto = false, $connecttype = 'right' ) {
            global $water;

            $this->mPrototypes[ $prototype->GetClass() ] = $prototype;
            $water->Trace( "Search: added prototype: " . $prototype->GetClass() );

            if ( is_object( $connectto ) ) {
                $this->Connect( $prototype, $connectto, $connecttype );
            }
        }
        private function PrepareSelect() {
            global $water;

            $water->Profile( "Search: SELECT" );

            $this->mQuery = "SELECT";
            $first = true;
            foreach ( $this->mPrototypes as $prototype ) {
                $water->Trace( "Search: adding fields of prototype " . $prototype->GetClass(), $prototype->GetFields() );

                $table = $prototype->GetTable();
                $fields = $prototype->GetFields();
                w_assert( is_array( $fields ) && count( $fields ) > 0 );

                foreach ( $fields as $property => $field ) {
                    if ( !$first ) {
                        $this->mQuery .= ", ";
                    }
                    else {
                        $first = false;
                    }
                    $this->mQuery .= " `$table`.`$field`";
                }
            }

            $water->ProfileEnd();
        }
        private function PrepareTableRefs() {
            global $water;

            $water->Profile( "Search: FROM" );

            w_assert( count( $this->mPrototypes ) > 0 );

            $this->mQuery .= " FROM ";

            $first = true;
            foreach ( $this->mPrototypes as $prototype ) {
                if ( in_array( $prototype->GetClass(), $this->mConnected ) ) {
                    continue;
                }
                $table = $prototype->GetTable();

                if ( !$first ) {
                    $this->mQuery .= ", ";
                }
                else {
                    $first = false;
                }

                $this->mQuery .= "`$table` ";
                $this->PrepareConnections( $prototype );
            }

            $water->ProfileEnd();
        }
        private function PrepareConnections( $prototype1 ) {
            if ( in_array( $prototype1->GetClass(), $this->mConnected ) ) {
                return true;
            }

            $this->ValidatePrototype( $prototype1 );

            $references1 = $prototype1->GetReferences();
            $fields1 = $prototype1->GetFields();
            $table1 = $prototype1->GetTable();

            if ( !isset( $this->mConnections[ $prototype1->GetClass() ] ) ) {
                return true;
            }

            $connections = $this->mConnections[ $prototype1->GetClass() ];

            foreach ( $connections as $join ) {
                w_assert( isset( $this->mPrototypes[ $join[ "to" ] ] ), "Trying to connect with a prototype that is not added" );

                $prototype2 = $this->mPrototypes[ $join[ "to" ] ];
                $table2     = $prototype2->GetTable();
                $class2     = $prototype2->GetClass();
                $fields2    = $prototype2->GetFields();

                // you may specify references whether on Prototype::GetReferences() or when calling Search::Connect()
                // the references have priority; Search::Connect uses a default type you may not want
                $type = isset( $ref[ 3 ] ) ? $ref[ 3 ] : $join[ "type" ];

                $this->mQuery .= $type . " JOIN ";
                $this->mQuery .= "`$table2` ";
               
                if ( isset( $references1[ $class2 ] ) ) {
                    $this->mQuery .= "ON ";
                    $first = true;
                    foreach ( $references1[ $class2 ] as $ref ) {
                        $field1 = $fields1[ $ref[ 0 ] ];
                        $field2 = $fields2[ $ref[ 1 ] ];

                        if ( !$first ) {
                            $this->mQuery .= " AND ";
                        }
                        $this->mQuery .= "`$table1`.`$field1` = `$table2`.`$field2` ";
                    }
                }
                else {
                    // using CROSS JOIN
                }
                $this->PrepareConnections( $prototype2 );
                $this->mConnected[] = $class2;
            }
        }
        private function PrepareWhere() {
            if ( $this->CountTotalValues() == 0 ) {
                return;
            }

            $this->mQuery .= "WHERE ";

            $conditionsCount = array();
            foreach ( $this->mPrototypes as $prototype ) {
                $table =  $prototype->GetTable();
                $properties = $prototype->GetValues();
                $fields = $prototype->GetFields();

                if ( empty( $properties ) ) {
                    continue;
                }

                foreach ( $properties as $property => $values ) {
                    $field = $fields[ $property ];

                    if ( count( $conditionsCount ) ) {
                        $this->mQuery .= " AND ";
                    }

                    if ( !isset( $conditionsCount[ $table ] ) ) {
                        $conditionsCount[ $table ] = array();
                    }
                    if ( !isset( $conditionsCount[ $table ][ $field ] ) ) {
                        $conditionsCount[ $table ][ $field ] = 0;
                    }

                    if ( count( $values ) > 1 ) {
                        $this->mQuery .= " ( ";
                    }
                    foreach ( $values as $value ) {
                        die( print_r( $conditionsCount ) );
                        if ( count( $conditionsCount[ $table ][ $field ] ) ) {
                            $this->mQuery .= " OR ";
                        }

                        if ( !is_array( $value ) ) { // property equals value
                            $this->mQuery .= "`$table`.`$field` = '$value'";
                        }
                        else if ( $value[ 0 ] != "range" )  { // property OPERATOR value
                            w_assert( count( $value ) == 2 );

                            $this->mQuery .= "`$table`.`$field` " . $value[ 0 ] . " '" . $value[ 1 ] . "'";
                        }
                        else { // property of range (min,max)
                            w_assert( count( $value ) == 3 );

                            $this->mQuery .= "( `$table`.`$field` > '" . $value[ 1 ] . "' AND `$table`.`$field` < '" . $value[ 1 ] . "' )";
                        }
                        ++$conditionsCount[ $table ][ $field ];
                    }
                    if ( count( $values ) > 1 ) {
                        $this->mQuery .= " ) ";
                    }
                }
            }

            $this->mQuery .= " ";
        }
        private function PrepareGroupBy() {
            if ( empty( $this->GroupByField ) ) {
                return;
            }

            w_assert( !empty( $this->GroupByTable ) );

            $this->mQuery .= "GROUP BY `" . $this->GroupByTable . "`.`" . $this->GroupByField . "` ";
        }
        private function PrepareOrderBy() {
            if ( empty( $this->SortField ) ) {
                return;
            }

            w_assert( !empty( $this->SortTable ) );
            w_assert( !empty( $this->SortOrder ) );

            $this->mQuery .= "ORDER BY `" . $this->SortTable . "`.`" . $this->SortField . "` " . $this->SortOrder . " ";
        }
        private function PrepareLimit() {
            global $water;
            
            if ( empty( $this->Limit ) ) {
                return;
            }

            $this->mQuery .= "LIMIT ";

            if ( !empty( $this->mOffset ) ) {
                $this->mQuery .= $this->mOffset . ",";
            }

            $this->mQuery .= $this->Limit;
        }
        public function SetOrderBy( $prototype, $property, $order = 'DESC' ) {
            $this->ValidatePrototype( $prototype );

            w_assert( $order == 'DESC' || $order == 'ASC', "invalid order!" );
            
            $fields = $prototype->GetFields();

            w_assert( isset( $fields[ $property ] ) );

            $this->SortTable = $prototype->GetTable();
            $this->SortField = $fields[ $property ];
            $this->SortOrder = strtoupper( $order );
        }
        public function SetGroupBy( $prototype, $property ) {
            $this->ValidatePrototype( $prototype );

            $fields = $prototype->GetFields();

            $this->GroupByTable = $prototype->GetTable();
            $this->GroupByField = $fields[ $property ];
        }
        // Search::Get() will create instances of $prototype->GetClass()
        private function CreateQuery() {
            global $water;
            
            if ( count( $this->mPrototypes ) == 0 ) {
                $water->Warning( "No prototypes added to search!" );
                return false;
            }

            $this->PrepareSelect();

            $this->PrepareTableRefs();
            $this->PrepareWhere();
            $this->PrepareGroupBy();
            $this->PrepareOrderBy();
            $this->PrepareLimit();

            w_assert( !empty( $this->mQuery ) );

            $water->Trace( "Query: " . $this->mQuery );

            return true;
        }
        public function Results() {
            global $db;

            if ( !$this->CreateQuery() ) {
                return false;
            }

            return $db->Query( $this->mQuery )->Results();
        }
        public function NumRows() {
            global $db;

            if ( !$this->CreateQuery() ) {
                return 0;
            }

            return $db->Query( $this->mQuery )->NumRows();
        }
        public function Get( $prototype = false ) {
            global $db;
            global $water;

            if ( $prototype === false ) {
                if ( count( $this->mPrototypes ) > 1 ) {
                    $water->Notice( "Search: no prototype specified on Search::Get(); using the first added" );
                }
                $prototype = $this->GetPrototype( 0 );
            }

            if ( empty( $this->Limit ) ) {
                $water->Warning( "No limit applied to search; Setting limit to 100" );
                $this->Limit = 100;
            }

            if ( $this->Limit > 100 ) {
                $water->Warning( "Too high limit was applied; Setting limit to 100" );
                $this->Limit = 100;
            }

            if ( !$this->CreateQuery() ) {
                return array();
            }

            $this->ValidatePrototype( $prototype );

            return $db->Query( $this->mQuery )->ToObjectsArray( $prototype->GetClass() );
        }
        public function Search() {
            $this->mConnections = array();
            $this->mPrototypes  = array();
            $this->mConnected   = array();
            $this->mQuery       = "";
        }
    }

    /*

    $events = new EventsSearch;
    $events->Type = array( EVENTS_COMMENTS, EVENTS_PHOTOS );
    $events->User = $user;
    $events->DelId = 0;
    $events->Offset = 40;
    $events->Limit = 20;
    
    $events = $events->Get();

    */

?>
