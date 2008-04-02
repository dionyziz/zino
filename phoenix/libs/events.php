<?php

	/*
	Developer: abresas
	*/

	/*
	new comment

	$event = New Event();
	$event->TypeId = COMMENT_CREATED;
	$event->ItemId = $comment->Id;
	$event->Created = $comment->Created;
	$event->UserId = $comment->UserId;
	$event->Save();
	/*

	/*
	mood changed

	$old_mood = $user->Mood;
	$user->Mood = $mood;
	$user->Save();
	
	$event = New Event();
	$event->TypeId = USER_MOOD_CHANGED;
	$event->ItemId = $user->Id;
	$event->UserId = $user->Id;
	$event->Save();
	*/	
	
	// TABLE(_FIELD)_ACTION
	define( 'COMMENT_CREATED', 0 );
	define( 'USER_MOOD_CHANGED', 1 );


	class EventFinder extends Finder {
		protected $mModel = 'Event';

		public function FindByUser( $user, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->UserId = $user->Id;
			$this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByType( $typeids, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->TypeId = $typeids; // Dionyziz: array allowed?
			$this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByUserAndType( $user, $typeids, $offset = 0, $limit = 20, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->UserId = $user->Id;
			$prototype->TypeId = $typeids;
			$this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
	}

	class Event extends Satori {
		protected $mDbTableAlias = 'events';

		public function LoadDefaults() {
			$this->Created = Now();
		}
	}

?>
