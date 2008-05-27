<?php

	/*
	Developer: abresas
	*/

	/*
	new comment

	$event = New Event();
	$event->Typeid = EVENT_COMMENT_CREATED;
	$event->Itemid = $comment->Id;
	$event->Created = $comment->Created;
	$event->Userid = $comment->Userid;
	$event->Save();
	*/

	function Event_Types() {
        // New events here!
        // EVENT_MODEL(_ATTRIBUTE)_ACTION
        return array(
            'EVENT_ALBUM_CREATED',
            'EVENT_ALBUM_UPDATED',
            'EVENT_ALBUM_DELETED',
            'EVENT_COMMENT_CREATED',
            'EVENT_COMMENT_UPDATED',
            'EVENT_COMMENT_DELETED',
            'EVENT_IMAGE_CREATED',
            'EVENT_IMAGE_UPDATED',
            'EVENT_IMAGE_DELETED',
            'EVENT_JOURNAL_CREATED',
            'EVENT_JOURNAL_UPDATED',
            'EVENT_JOURNAL_DELETED',
            'EVENT_POLL_CREATED',
            'EVENT_POLL_UPDATED',
            'EVENT_POLL_DELETED',
            'EVENT_POLLVOTE_CREATED',
            'EVENT_POLLOPTION_CREATED',
            'EVENT_POLLOPTION_DELETED',
            'EVENT_RELATION_CREATED',
            'EVENT_RELATION_UPDATED',
            'EVENT_SPACE_UPDATED',
            'EVENT_USERPROFILE_UPDATED',
            'EVENT_USERPROFILE_VISITED',
            'EVENT_USERPROFILE_EDUCATION_UPDATED',
            'EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED',
            'EVENT_USERPROFILE_RELIGION_UPDATED',
            'EVENT_USERPROFILE_POLITICS_UPDATED',
            'EVENT_USERPROFILE_SMOKER_UPDATED',
            'EVENT_USERPROFILE_DRINKER_UPDATED',
            'EVENT_USERPROFILE_ABOUTME_UPDATED',
            'EVENT_USERPROFILE_MOOD_UPDATED',
            'EVENT_USERPROFILE_PLACEID_UPDATED'
        );
	}

	function Event_ModelByType( $type ) {
		static $models = array();
        if ( empty( $models ) ) {
            $types = Event_Types();
            foreach ( $types as $key => $value ) {
                $after_first_underscore = strpos( $value, '_' ) + 1;
                $before_second_underscore = strpos( $value, '_', $after_first_underscore ) - 1;
                $model = substr( $value, $after_first_underscore, $before_second_underscore - $after_first_underscore + 1 );
                $models[ $key ] = $model;
            }
        }
        return $models[ $type ];
	}

	$events = Event_Types();
	for ( $i = 0; $i < count( $events ); ++$i ) {
		define( $events[ $i ], $i );
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

		public function Relations() {
			global $water;
			$model = Event_ModelByType( $this->Typeid );
			
			$this->User = $this->HasOne( 'User', 'Userid' );
			if ( $this->Exists() ) {
				$this->Object = $this->HasOne( $model, 'Itemid' );
			}
		}
        public function Save() {
			global $water;

            if ( $this->Exists() ) {
                throw New EventException( 'Events cannot be updated' );
            }

			$water->Trace( "creating event" );
        
            parent::Save();
        }
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}

?>
