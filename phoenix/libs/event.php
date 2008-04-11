<?php

	/*
	Developer: abresas
	*/

	/*
	new comment

	$event = New Event();
	$event->Typeid = COMMENT_CREATED;
	$event->Itemid = $comment->Id;
	$event->Created = $comment->Created;
	$event->Userid = $comment->Userid;
	$event->Save();
	*/
	
	function Event_Types() {
		return array(
			// TABLE(_FIELD)_ACTION, MODEL
			array( 'EVENT_COMMENT_CREATED', 'Comment' ),
			array( 'EVENT_FRIEND_ADDED', 'Relation' ),
			array( 'EVENT_USER_MOOD_CHANGED', 'User' ),
			array( 'EVENT_USER_PROFILE_VISITED', 'UserProfile' )
		);
	}

	function Event_ModelByType( $type ) {
		$events = Event_Types();
		return $events[ $type ][ 1 ];
	}

	$events = Event_Types();
	for ( $i = 0; $i < count( $events ); ++$i ) {
		define( $events[ $i ][ 0 ], $i );
	}

    class EventException extends Exception {
    }

	class EventFinder extends Finder {
		protected $mModel = 'Event';

		public function FindByUser( $user, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->Userid = $user->Id;
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByType( $typeids, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			if ( !is_array( $typeids ) ) {
				$typeids = array( $typeids );
			}

			w_assert( $order[ 1 ] == 'DESC' || $order[ 1 ] == 'ASC', "Only 'ASC' or 'DESC' values are allowed in the order" );
/*
			if ( count( $typeids ) == 1 ) {
				$type = $typeids[ 0 ];
				$model = Event_ModelByType( $type );

				$obj = New $model();
				$primarykeys = $obj->PrimaryKeyFields();
				$primarykey = $primarykeys[ 0 ]; // axiom: there's only one primary key field

				$query = $db->Prepare( "SELECT
					*
				FROM
					:events LEFT JOIN :$typetable
						ON ( `$primarykey` = `event_itemid` )
				WHERE
					`event_type` = :type
				ORDER BY
					`" . $order[ 0 ] . "` " . $order[ 1 ] . "
				LIMIT
					$limit, $offset
				;" );

				$query->BindTable( 'events' );
				$query->BindTable( $typetable );
				$query->Bind( 'type', $typeids[ 0 ] );
				
				$res = $query->Execute();
				$ret = array();
				while ( $row = $res->FetchArray() ) {
					$event = New Event( $row );	
					$event->Model = New $model( $row );
					$ret[] = $event;
				}

				return $ret;
			}
*/

			$prototype = New Event();
			$prototype->Typeid = $typeids; // Dionyziz: array allowed?
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByUserAndType( $user, $typeids, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->Userid = $user->Id;
			$prototype->Typeid = $typeids;
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
	}

	class Event extends Satori {
		protected $mDbTableAlias = 'events';

		/*
		public function SetModel( $value ) {
			if ( $value instanceof Relation ) {
				return parent::__set( 'Model', $value );
			}
			$this->Model = $value;
		}
		*/
		public function Relations() {
			global $water;
			$model = Event_ModelByType( $this->Typeid );
			
			$this->User = $this->HasOne( 'User', 'Userid' );
			if ( $this->Exists() ) {
				$this->Model = $this->HasOne( $model, 'Itemid' );
			}
		}
        public function Save() {
            if ( $this->Exists() ) {
                throw New EventException( 'Events cannot be updated' );
            }
        
            parent::Save();
        }
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}

?>
