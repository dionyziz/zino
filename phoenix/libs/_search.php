<?php

    /* new Search Library proposal 
        not ready yet
        developer: abresas
    */


    abstract class SearchPrototype {
        protected $mValues;
        protected $mObject;
        protected $mClass;
        protected $mReferences;
        protected $mFields;
        protected $mTable;

        public function __set( $key, $value ) {
            $methodname = 'Set' . $key;
            if ( method_exists( $this, $methodname ) ) {
                $this->$methodname( $value ); // MAGIC!
            }
            $this->mValues[ $key ] = $value;
        }
        public function GetValues() {
            return $this->mValues;
        }
        public function GetFields() {
            return $this->mFields;
        }
        public function GetTable() {
            return $this->mTable;
        }
        public function GetClass() {
            return $this->mClass;
        }
        public function GetReferences() {
            return $this->mReferences;
        }
        protected function SetFields( $fields ) {
            w_assert( is_array( $fields ) );

            $this->mFields = array();
            foreach ( $fields as $field => $property ) {
                $this->mFields[ $property ] = $field;
            }
        }
        protected function SetReferences( $refs ) {
            w_assert( is_array( $refs ) );

            $this->mReferences = $refs;
        }
        public function SearchPrototype() {
            $this->mObject = new $this->mClass; // MAGIC!

            if ( !is_array( $this->mReferences ) ) {
                $this->mReferences = array();
            }
        }
    }

    class CommentPrototype extends SearchPrototype {
        public function SetTypeId( $typeid ) {
            switch( $itemid ) {
                case 0:
                    $class = 'Journal';
                    break;
                case 1:
                    $class = 'User';
                    break;
                case 2:
                    $class = 'Image';
                    break;
                default:
                    w_assert( false );        
            }
            
            $this->mReferences[ 'TypeId' ] = array( $class, 'Id' );
        }
        public function CommentPrototype() {
            $this->mClass = 'Comment';
            $this->mTable = 'merlin_comments';

            $this->mReferences = array(
                'User' => array( array( 'UserId', 'Id' ) ),
                'Bulk' => array( array( 'BulkId', 'Id' ) )
            );

            $this->SetFields( array(
                'comment_id'        => 'Id',
                'comment_userid'    => 'UserId',
                'comment_created'   => 'Created',
                'comment_userip'    => 'UserIp',
                'comment_itemid'    => 'ItemId',
                'comment_typeid'    => 'TypeId',
                'comment_parentid'  => 'ParentId',
                'comment_delid'     => 'DelId',
                'comment_bulkid'    => 'BulkId'
            ) );

            parent::SearchPrototype();
        }
    }

    class UserPrototype extends SearchPrototype {
        public function UserPrototype() {
            $this->mClass = 'User';
            $this->mTable = 'merlin_users';

            $this->SetReferences( array(
                'Image' => array( array( 'Avatar', 'Id', 'left' ) )
            ) );

            $this->SetFields( array(
                'user_id'       => 'Id',
                'user_name'     => 'Name',
                'user_password' => 'Password',
                'user_icon'     => 'Avatar',
                'user_locked'   => 'DelId'
            ) );

            parent::SearchPrototype();
        }
    }

    class ImagePrototype extends SearchPrototype {
        public function ImagePrototype() {
            $this->mClass = 'Image';

            $this->mTable = 'merlin_images';

            $this->SetFields( array(
                'image_id'          => 'Id',
                'image_userid'      => 'UserId',
                'image_created'     => 'Date',
                'image_userip'      => 'UserIp',
                'image_name'        => 'Name',
                'image_description' => 'Description',
                'image_width'       => 'Width',
                'image_height'      => 'Height',
                'image_size'        => 'Size',
                'image_mime'        => 'Mime',
                'image_albumid'     => 'AlbumId',
                'image_numcomments' => 'CommentsNum',
                'image_pageviews'   => 'Pageviews'
            ) );

            parent::SearchPrototype();
        }
    }

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

        public function Connect( $prototype1, $prototype2, $type = 'right' ) {
            w_assert( $prototype1 instanceof SearchPrototype );
            w_assert( $prototype2 instanceof SearchPrototype );

            $table1 = $prototype1->GetTable();
            $table2 = $prototype2->GetTable();

            $this->mConnections[ $table1 ][] = array( $table2, strtoupper( $type ) );
        }
        public function AddPrototype( $prototype, $connectto = false, $connecttype = 'right' ) {
            $this->mPrototypes[ $prototype->GetClass() ] = $prototype;

            if ( is_object( $connectto ) ) {
                $this->Connect( $prototype, $connectto, $connecttype );
            }
        }
        private function PrepareSelect() {
            $this->mQuery .= "SELECT";
            $first = true;
            foreach ( $this->mPrototypes as $prototype ) {
                $table = $prototype->GetTable();
                $fields = $prototype->GetFields();
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
        }
        private function PrepareTableRefs() {
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
                $this->mConnected = array();
                $this->PrepareConnections( $prototype );
            }
        }
        private function PrepareConnections( $prototype1 ) {
            $references1 = $prototype1->GetReferences();
            $fields1 = $prototype1->GetFields();
            $table1 = $prototype1->GetTable();
            
            foreach ( $this->mConnections[ $prototype1->GetClass() ] as $join ) {
                w_assert( isset( $this->mPrototypes[ $join[ 0 ] ] ), $join[ 0 ] );

                $prototype2 = $this->mPrototypes[ $join[ 0 ] ];
                $table2 = $prototype2->GetTable();
                $class2 = $prototype2->GetClass();
                $fields2 = $prototype2->GetFields();

                $this->mQuery .= $join[ 1 ] . " JOIN ";
                $this->mQuery .= "`$table2` ";

                $this->mConnected[] = $class2;
               
                if ( count( $references1[ $class2 ] ) ) {
                    $this->mQuery .= "ON ";
                    $first = true;
                    foreach ( $references1[ $class2 ] as $ref ) {
                        $type = $ref[ 3 ];
                        $field1 = $fields1[ $ref[ 0 ] ];
                        $field2 = $fields2[ $ref[ 1 ] ];

                        if ( !$first ) {
                            $this->mQuery .= " AND ";
                        }
                        $this->mQuery .= "`$table1`.`$field1` = `$table2`.`$field2` ";
                    }
                }
                $this->PrepareConnections( $prototype2 );
            }
        }
        private function PrepareWhere() {
            $this->mQuery .= "WHERE ";

            $first = true;
            foreach ( $this->mPrototypes as $prototype ) {
                $table =  $prototype->GetTable();
                $values = $prototype->GetValues();
                $fields = $prototype->GetFields();

                foreach ( $values as $property => $value ) {
                    $field = $fields[ $property ];

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
            if ( empty( $this->GroupByField ) ) {
                return;
            }

            $this->mQuery .= "GROUP BY `" . $this->GroupByTable . "`.`" . $this->GroupByField . "` ";
        }
        private function PrepareOrderBy() {
            if ( empty( $this->SortField ) ) {
                return;
            }

            $this->mQuery .= "ORDER BY `" . $this->SortTable . "`.`" . $this->SortField . "` " . $this->SortOrder . " ";
        }
        private function PrepareLimit() {
            if ( empty( $this->Limit ) ) {
                return;
            }

            $this->mQuery .= "LIMIT ";

            if ( !empty( $this->mOffset ) ) {
                $this->mQuery .= $this->mOffset . ",";
            }

            $this->mQuery .= $this->Limit;
        }
        public function SetSortMethod( $prototype, $property, $order = 'DESC' ) {
            $fields = $prototype->GetFields();

            $this->SortTable = $prototype->GetTable();
            $this->SortField = $fields[ $property ];
            $this->SortOrder = strtoupper( $order );
        }
        public function SetGroupBy( $prototype, $property ) {
            $fields = $prototype->GetFields();

            $this->GroupByTable = $prototype->GetTable();
            $this->GroupByField = $fields[ $property ];
        }
        public function Get( $prototype ) {
            if ( !$prototype instanceof SearchPrototype ) {
                var_dump( $prototype );
                die();
            }
            $this->PrepareSelect();
            $this->PrepareTableRefs();
            $this->PrepareWhere();
            $this->PrepareGroupBy();
            $this->PrepareOrderBy();
            $this->PrepareLimit();

            $res = $db->Prepare( $this->mQuery )->Execute()->ToObjectArray( $prototype->GetClass() );
        }
        public function Search() {
            $this->mConnections = array();
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



    class Search {
        protected $mLimit;
        protected $mOffset;
        protected $mFilters;
        protected $mFields;
        protected $mTables;
        protected $mSortBy;
        protected $mSortOrder;
        protected $mGroupBy;
        protected $mConnections;
        protected $mQuery;
        protected $mClass;
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
        protected function AddTable( $alias, $table = false ) {
            if ( !$table && isset( $this->mTables[ $alias ] ) ) {
                // add table to needed tables
                $this->mTables[ $alias ][ "needed" ] = true;
            }
            $this->mTables[ $alias ] = array( "name" => $table, "needed" => true );
        }
        protected function SetFields( $fields ) {
            $this->mFields = $fields;
        }
        protected function AddField( $field, $table ) { // add field to already setted fields
            
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
            // w_assert( property_exists( get_class( $this ), $varname ), $varname );
            return $this->$varname; // MAGIC!
        }
        public function __set( $name, $value ) {
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
            }
            
            $varname = 'm' . $name;
            // w_assert( property_exists( get_class( $this ), $varname ), $varname );
            $this->$varname = $value;
        }
        private function SetOrderBy() {
        }
        private function PrepareSelectExpression() {
            $this->mQuery .= "SELECT ";

            $first = true;
            foreach ( $this->mFields as $table => $fields ) {
                w_assert( isset( $this->mTables[ $table ] ) );
                
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
            if ( !isset( $this->mConnections[ $alias ] ) ) {
                return true;
            }

            foreach ( $this->mConnections[ $alias ] as $join ) {
                $this->mQuery .= " " . $join[ 'type' ] . " JOIN ";
                $calias = $join[ 'table' ];
                $ctable = $this->mTables[ $calias ][ "name" ];
                $this->mQuery .= "`$ctable` AS $calias";
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
                $value = $this->$property; // MAGIC!
                if ( $value === null ) {
                    continue;
                }
                else {
                    // die( "value $value" );
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
            if ( empty( $this->mGroupBy ) ) {
                return false;
            }
            $table = $this->FirstTable( $this->mGroupBy );
            $field = $this->mFilters[ $this->mGroupBy ][ $table ]; // field of the first filter

            $this->mQuery .= " GROUP BY `$table`.`$field` ";
        }
        private function PrepareOrderBy() {
            if ( empty( $this->mSortBy ) ) {
                return false;
            }
            if ( empty( $this->mSortOrder ) ) {
                $this->mSortOrder = "DESC";
            }
            $table = $this->FirstTable( $this->mSortBy );
            $field = $this->mFilters[ $this->mSortBy ][ $table ]; // field of the first filter

            $this->mQuery .= " ORDER BY $table.`$field` " . $this->mSortOrder . " ";
        }
        private function PrepareLimit() {
            if ( !empty( $this->mOffset ) || !empty( $this->mLimit ) ) {
                $this->mQuery .= " LIMIT ";
                if ( !empty( $this->mOffset ) && !empty( $this->mLimit ) ) {
                    $this->mQuery .= $this->mOffset . " , ";
                }
                if ( !empty( $this->mLimit ) ) {
                    $this->mQuery .= $this->mLimit;
                }
                $this->mQuery .= " ";
            }
        }
        private function FirstTable( $property ) {
            w_assert( isset( $this->mFilters[ $property ] ), "property $property not in filters" );

            $tables = array_keys( $this->mFilters[ $property ] );
            
            return $tables[ 0 ];
        }
        public function Get() {
            global $water;
            global $db;

            $this->mQuery = "";
            $this->PrepareSelectExpression();
            $water->Trace( "query select: " . $this->mQuery . "." );
            $this->PrepareTableReferences();
            $water->Trace( "query ref: " . $this->mQuery . "." );
            $this->PrepareWhereCondition();
            $water->Trace( "query cond: " . $this->mQuery . "." );
            $this->PrepareGroupBy();
            $water->Trace( "query group: " . $this->mQuery . "." );
            $this->PrepareOrderBy();
            $water->Trace( "query order: " . $this->mQuery . "." );
            $this->PrepareLimit();
            $water->Trace( "query limit: " . $this->mQuery . "." );

            $res = $db->Query( $this->mQuery );

            return $res->ToObjectsArray( $this->mClass );
        }
        public function Search() {
            $this->mConnected = array();
            $this->Defaults();
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
        protected $mItemId;
        protected $mTypeId;
        protected $mDelId;
        protected $mCreated;
        protected $mUserDelId;

        public function SetPage( $item ) {
            $this->PageId = $item->Id;
        }
        public function Defaults() {
            $this->mLimit = 20;
            $this->UserDelId = 0;
        }
        public function CommentsSearch() {
            $this->mClass = 'Comment';

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
                    'comment_bulkid',
                    'comment_userip',
                    'comment_itemid',
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
                    'comment_itemid' => 'ItemId',
                    'comment_typeid' => 'TypeId',
                    'comment_delid' => 'DelId',
                    'comment_date'  => 'Created'
                ),
                'users' => array(
                    // 'user_delid' => 'UserDelId',
                ),
                'images' => array(
                )
            ) );

            $this->Search();
        }
        public function GetParented( $reverse = false ) {
            $comments = $this->Get();

			$parented = array();
            if ( !is_array( $comments ) ) {
                return $parented;
            }

			foreach( $comments as $comment ) {
				if ( !isset( $parented[ $comment->ParentId ] ) || empty( $parented[ $comment->ParentId ] ) ) {
					$parented[ $comment->ParentId ] = array( $comment );
				}
				else {
					if ( $reverse ) {
						array_push( $parented[ $comment->ParentId ], $comment );
					}
					else {
						array_unshift( $parented[ $comment->ParentId ], $comment );
					}
				}
			}
			
			return $parented;
        }
    }

    */

?>
