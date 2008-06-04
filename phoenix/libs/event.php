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
            0 => 'EVENT_ALBUM_CREATED',
            1 => 'EVENT_ALBUM_UPDATED', // not in use
            2 => 'EVENT_ALBUM_DELETED', // not in use
            4 => 'EVENT_COMMENT_CREATED',
            5 => 'EVENT_COMMENT_UPDATED', // not in use
            6 => 'EVENT_COMMENT_DELETED', // not in use
            7 => 'EVENT_IMAGE_CREATED',
            8 => 'EVENT_IMAGE_UPDATED', // not in use
            9 => 'EVENT_IMAGE_DELETED', // not in use
            10 => 'EVENT_JOURNAL_CREATED',
            11 => 'EVENT_JOURNAL_UPDATED',
            12 => 'EVENT_JOURNAL_DELETED', // not in use
            13 => 'EVENT_POLL_CREATED',
            14 => 'EVENT_POLL_UPDATED', // not in use
            15 => 'EVENT_POLL_DELETED', // not in use
            16 => 'EVENT_POLLVOTE_CREATED', // not in use
            17 => 'EVENT_POLLOPTION_CREATED', // not in use
            18 => 'EVENT_POLLOPTION_DELETED', // not in use
            19 => 'EVENT_FRIENDRELATION_CREATED',
            20 => 'EVENT_FRIENDRELATION_UPDATED',
            21 => 'EVENT_USERSPACE_UPDATED',
            22 => 'EVENT_USERPROFILE_UPDATED', // not in use
            23 => 'EVENT_USERPROFILE_VISITED', // not in use
            24 => 'EVENT_USERPROFILE_EDUCATION_UPDATED',
            25 => 'EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED',
            26 => 'EVENT_USERPROFILE_RELIGION_UPDATED',
            27 => 'EVENT_USERPROFILE_POLITICS_UPDATED',
            28 => 'EVENT_USERPROFILE_SMOKER_UPDATED',
            29 => 'EVENT_USERPROFILE_DRINKER_UPDATED',
            30 => 'EVENT_USERPROFILE_ABOUTME_UPDATED',
            31 => 'EVENT_USERPROFILE_MOOD_UPDATED',
            32 => 'EVENT_USERPROFILE_LOCATION_UPDATED',
            33 => 'EVENT_USERPROFILE_HEIGHT_UPDATED',
            34 => 'EVENT_USERPROFILE_WEIGHT_UPDATED',
            35 => 'EVENT_USERPROFILE_HAIRCOLOR_UPDATED',
            36 => 'EVENT_USERPROFILE_EYECOLOR_UPDATED',
            37 => 'EVENT_USER_CREATED'
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
        if ( !isset( $models[ $type ] ) ) {
            throw New Exception( "Unkown event type $type" );
        }
        return $models[ $type ];
	}

	$events = Event_Types();
    foreach ( $events as $key => $event ) {
        define( $event, $key );
    }

    class EventException extends Exception {
    }

	class EventFinder extends Finder {
		protected $mModel = 'Event';

		public function FindLatest( $offset = 0, $limit = 20 ) {
			$prototype = New Event();
			return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
		}
		public function FindByUser( $user, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->Userid = $user->Id;
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByType( $typeids, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
			if ( !is_array( $typeids ) ) {
				$typeids = array( $typeids );
			}

			w_assert( $order[ 1 ] == 'DESC' || $order[ 1 ] == 'ASC', "Only 'ASC' or 'DESC' values are allowed in the order" );

			$prototype = New Event();
			$prototype->Typeid = $typeids; // Dionyziz: array allowed?
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByUserAndType( $user, $typeids, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
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
				$this->Item = $this->HasOne( $model, 'Itemid' );
			}
		}
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'notify' );

            /* notification firing */
            switch ( $this->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $notif = New Notification();
                    $notif->Eventid = $this->Id;
                    if ( $this->Item->Parentid == 0 ) {
                        $notif->Touserid = $this->Item->Parent->Userid;
                    }
                    else {
                        $notif->Touserid = $this->Item->Item->Userid;
                    }
                    $notif->Fromuserid = $this->Userid;
                    $notif->Save();
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $notif = New Notification();
                    $notif->Eventid = $this->Id;
                    $notif->Touserid = $this->Item->Userid;
                    $notif->Fromuserid = $this->Userid;
                    $notif->Save();
                    break;
            }
        }
        protected function OnBeforeUpdate() {
            throw New EventException( 'Events cannot be updated' );

            return false;
        }
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}

?>
