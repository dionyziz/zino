<?php

    /* new Search Library proposal 
        not ready yet
        developer: abresas
    */


    /*
    $events = new EventsSearch;
    $events->Type = array( EVENTS_COMMENTS, EVENTS_PHOTOS );
    $events->User = $user;
    $events->DelId = 0;
    $events->Offset = 40;
    $events->Limit = 20;
    
    $events = $events->Get();

    $comments = new CommentsSearch;
    $comments->TypeId   = 1;
    $comments->Page     = $theuser;
    $comments->DelId    = 0;
    $comments->OrderBy  = array( 'date', 'DESC' );


    if ( $oldcomments ) {
        $comments->Limit = 10000;
    }
    else {
        $comments->Limit = 50;
    }

    $comments = $comments->GetParented();

    */

    class Search {
        protected $mLimit;
        protected $mOffset;
        protected $mFilters;
        protected $mFields;
        protected $mTables;
        protected $mOrderBy;
        protected $mConnections;
        private $mConnected;

        protected function Connect( $table1, $type, $table2, $fields ) {
           $this->mConnections[ $table1 ][] = array( 
                'type' => strtoupper( $type ), 
                'table' => $table2,
                'fields' => $fields 
            );
        }
        protected function SetTables( $tables ) {
            foreach ( $tables as $alias => $table ) {
                $this->mTables[ $alias ] = array( "name" => $table, "needed" => false );
            }
        }
        protected function AddTables( $tables ) {
            foreach ( $tables as $alias => $table ) {
                $this->mTables[ $alias ] = array( "name" => $table, "needed" => true );
            }
        }
        protected function AddTable( $alias ) {
            $this->mTables[ $alias ] = array( "name" => $this->mTables[ $alias ][ "name" ], "needed" => true );
        }
        protected function SetFields( $fields ) {
            $this->mFields = $fields;
        }
        protected function SetFilters( $filters, $table = false ) {
            foreach ( $filters as $field => $property ) {
                if ( is_array( $property ) && $table == false ) {
                    $this->SetFilters( $property, $field );
                }
                $this->mFilters[ $property ][ $table ] = $field;
            }
        }
        public function __get( $name ) {
            global $water;
            
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                return $this->$methodname(); // MAGIC!
            }
            
            if ( !array_key_exists( $name, $this->mFilters ) ) {
                $water->Warning( 'Attempting to read non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }

            $varname = 'm' . $name;
            return $this->$varname; // MAGIC!
        }
        public function __set( $name, $value ) {
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
                /* else fallthru */
            }

            if ( array_key_exists( $name, $this->mFilters ) ) {
                $varname = 'm' . $name;
                $this->$varname = $value;
            }
        }
        private function PrepareSelectExpression() {
            $this->mQuery .= "SELECT ";

            $first = true;
            foreach ( $this->mFields as $table => $fields ) {
                if ( isset( $this->mTables[ $table ] ) ) {
                    $table = $this->mTables[ $table ][ "name" ];
                }
                while ( $field = array_shift( $fields ) ) {
                    if ( !$first ) {
                        $this->mQuery .= ", ";
                    }
                    else {
                        $first = false;
                    }
                    $this->mQuery .= "`$table`.`$field`";
                }
            }
        }
        private function PrepareConnections( $alias ) {
            foreach ( $this->mConnections[ $alias ] as $join ) {
                $this->mQuery .= " " . $join[ 'type' ] . " JOIN ";
                $calias = $join[ 'table' ];
                $ctable = $this->mTables[ $calias ][ "name" ];
                $this->mQuery .= "`$ctable`";
                $this->mConnected[] = $calias;
                if ( count( $join[ 'fields' ] ) ) {
                    $this->mQuery .= " ON ";
                    $j = 0;
                    foreach ( $join[ 'fields' ] AS $f1 => $f2 ) {
                        if ( $j > 0 ) {
                            $this->mQuery .= "AND ";
                        }
                        $this->mQuery  .= "$f1 = $f2 ";
                        ++$j;
                    }
                }
                $this->PrepareConnections( $calias );
            }
        }
        private function PrepareTableReferences() {
            $this->mQuery .= " FROM ";

            $first = true;
            foreach ( $this->mTables as $alias => $table ) {
                if ( !$table[ "needed" ] || in_array( $alias, $this->mConnected ) ) {
                    continue;
                }
                if ( !$first ) {
                    $this->mQuery .= ", ";
                }
                else {
                    $first = false;
                }

                $table = $table[ "name" ];
                $this->mQuery .= "`$table` AS $alias";

                $this->mConnected = array();
                $this->PrepareConnections( $alias );
            }
        }
        private function PrepareWhereCondition() {
            $this->mConnected = array();

            if ( !count( $this->mFilters ) ) {
                return;
            }
            
            $this->mQuery .= " WHERE ";

            $first = true;
            foreach ( $this->mFilters as $property => $filter ) {
                $name = "m$property";
                $value = $this->$name; // MAGIC!
                if ( $value === null ) {
                    continue;
                }
                foreach ( $filter as $table => $field ) {
                    if ( !$first ) {
                        $this->mQuery .= " AND ";
                    }
                    else {
                        $first = false;
                    }
                    $this->mQuery .= "`$table`.`$field` = '$value'";
                }
            }

            $this->mQuery .= " ";
        }
        private function PrepareGroupBy() {
        }
        private function PrepareOrderBy() {
        }
        private function PrepareLimit() {
            $this->mQuery .= " LIMIT 50";
        }
        public function Get() {
            $this->mQuery = "";
            $this->PrepareSelectExpression();
            $this->PrepareTableReferences();
            $this->PrepareWhereCondition();
            $this->PrepareGroupBy();
            $this->PrepareOrderBy();
            $this->PrepareLimit();

            die( $this->mQuery );

            // $res = $db->Query( $this->mQuery );
            // return $res;
        }
        public function Search() {
            $this->Defaults();
        }
        public function GetParented() {
            $res = $this->Get();

            // do stuff
        }
    }

    class EventsSearch extends Search {
        public function SetType( $types ) {
            if ( !is_array( $types ) ) {
                $types = array( $types );
            }

            foreach ( $types as $type ) {
                switch ( $type ) { 
                    case EVENT_COMMENT:
                        $this->AddTable( 'comments' );
                        break;
                    case EVENT_PHOTOS:
                        $this->AddTable( 'images' );
                        break;
                }
            }
        }
        public function SetUser( $user ) {
            $this->UserId = $user->Id();
        }
        public function EventsSearch() {
            $this->SetTables( array(
                'comments'  => 'merlin_comments',
                'images'    => 'merlin_images'
            ) );

            $this->SetFields( array(
                'comments' => array(
                    'comment_id',
                    'comment_storyid',
                    'comment_typeid',
                    'comment_userid',
                    'comment_date'
                ),
                'images' => array(
                    'image_id',
                    'image_userid',
                    'image_date'
                )
            ) );

            $this->SetFilters( array(
                'comments' => array(
                    'comment_userid' => 'UserId',
                    'comment_delid'  => 'DelId'
                ),
                'images' => array(
                    'image_userid'  => 'UserId',
                    'image_delid'   => 'DelId'
                )
            ) );

            $this->Search();
        }
    }

    class CommentsSearch extends Search {
        public function SetPage( $item ) {
            $this->PageId = $item->Id;
        }
        public function Defaults() {
            $this->UserDelId = 0;
            $this->ImageDelId = 0;
        }
        public function CommentsSearch() {
            $this->AddTables( array(
                'comments' => 'merlin_comments',
                'users' => 'merlin_users',
                'images' => 'merlin_images'
            ) );

            $this->SetFields( array(
                'comments' => array(
                    'comment_id',
                    'comment_created',
                    'comment_parentid',
                    'comment_text',
                    'comment_textraw',
                    'comment_userip',
                    'comment_storyid'
                ),
				'users' => array( 
                    'user_id',
                    'user_name',
                    'user_rights',
                    'user_lastprofedit',
                    'user_icon',
                    'user_signature'
                ),
                'images' => array( 
                    'image_id',
                    'image_userid'
                )
            ) );

            $this->Connect( 'comments', 'right', 'users', array( 'comment_userid' => 'user_id' ) );
            $this->Connect( 'users', 'left', 'images', array( 'user_icon' => 'image_id' ) );

            $this->SetFilters( array(
                'comments' => array(
                    'comment_pageid' => 'PageId',
                    'comment_typeid' => 'TypeId',
                    'comment_delid' => 'DelId'
                ),
                'users' => array(
                    'user_delid' => 'UserDelId',
                ),
                'images' => array(
                    'image_delid' => 'ImageDelId'
                ),
                '' => array(
                    '' => ''
                )
            ) );

            $this->Search();
        }
    }

?>
