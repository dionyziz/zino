<?php

    /* new Search Library proposal 
        not ready yet
        developer: abresas
    */


    $events = new EventsSearch;
    $events->Type = array( EVENTS_COMMENTS, EVENTS_PHOTOS );
    $events->User = $user;
    $events->DelId = 0;
    $events->Offset = 40;
    $events->Limit = 20;
    
    $events = $events->Get();

    // ===

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

    // ===

    class Search {
        protected $mLimit;
        protected $mOffset;
        protected $mFilters;
        protected $mFields;
        protected $mTables;
        protected $mOrderBy;
        protected $mConnections;

        protected function Connect( $table1, $type, $table2, $fields ) {
           $this->mConnections[ $table1 ][] = array( 
                'type' => strtoupper( $type ), 
                'table' => $table2,
                'fields' => $fields 
            );
        }
        protected function SetTables( $tables ) {
            $this->mTables = $tables;
        }
        protected function SetFields( $fields ) {
            $this->mFields = $fields;
        }
        protected function SetFilters( $filters, $table = false ) {
            foreach ( $filters as $field => $property ) {
                if ( is_array( $property ) && $table == false ) {
                    $this->SetFilters( $property, $field );
                }
                $this->mFilters[ $property ][ $array ] = $field;
            }
        }
        public function __set( $name, $value ) {
            $methodname = 'Set' . $name;
            if ( $method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
                /* else fallthru */
            }

            if ( in_array( $name, $this->mFilters ) ) {
                $tables = $this->mFilters[ $name ];
                foreach ( $tables as $table ) {
                    $this->mFilters[ $name ][ $table ] = $value;
                }
                return;
            }
        }
        public function Get() {
            $sql = "SELECT ";
            
            foreach ( $this->mFields as $table => $fields ) {
                while ( $field = array_shift( $fields ) ) {
                    $sql .= "`$table`.`$field`";
                    if ( count( $fields ) ) {
                        $sql .= ", ";
                    }
                }
            }

            $sql .= " FROM ";

            $i = 0;
            $connected = array();
            foreach ( $this->mTables as $alias => $table ) {
                if ( isset( $connected[ $alias ] ) ) {
                    continue;
                }
                $sql .= "`$table` AS $alias";
                foreach ( $this->mConnections[ $alias ] as $join ) {
                    $sql .= " " . $join[ 'type' ] . " JOIN ";
                    $sql .= $join[ 'table' ];
                    $connected[] = $join[ 'table' ];
                    if ( count( $join[ 'fields' ] ) ) {
                        $sql .= " ON ";
                        $j = 0;
                        foreach ( $join[ 'fields' ] AS $f1 => $f2 ) {
                            if ( $j > 0 ) {
                                $sql .= "AND ";
                            }
                            $sql .= "$f1 = $f2 ";
                            ++$j;
                        }
                    }
                }
                if ( $i < count( $this->mTables ) - 1 ) {
                    $sql .= ", ";
                }
                ++$i;
            }
        }
        public function GetParented() {
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
                        $this->AddTable( 'photos' );
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
        public function SetBody( $value ) {
            $this->AddFilter( 'Body', $value, SEARCH_LIKE );
        }
        public function Defaults() {
            $this->UserDelId = 0;
            $this->ImageDelId = 0;
        }
        public function CommentsSearch() {
            $this->SetTables( array(
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
                )
            ) );

            $this->Search();
        }
    }

?>
