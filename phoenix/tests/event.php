<?php

    class TestEvent extends TestCase {
        protected $mAppliesTo = 'libs/event';
        private $mEventId;

        public function SetUp() {
            global $libs;
            
            $libs->Load( 'event' );
        }
        public function TestFunctionsExist() {
            $this->Assert( class_exists( 'Event' ), 'Event class does not exist' );
            $this->Assert( class_exists( 'EventFinder' ), 'EventFinder class does not exist' );
        }
        public function TestMethodsExist() {
            $finder = New EventFinder();

            $this->Assert( method_exists( $finder, 'FindByUser' ), 'EventFinder::FindByUser method does not exist' );
            $this->Assert( method_exists( $finder, 'FindByType' ), 'EventFinder::FindByType method does not exist' );
            $this->Assert( method_exists( $finder, 'FindByUserAndType' ), 'EventFinder::FindByUserAndType method does not exist' );

            $event = New Event();
        
            $this->Assert( method_exists( $event, 'Save' ), 'Event::Save method does not exist' );
            $this->Assert( method_exists( $event, 'Delete' ), 'Event::Delete method does not exist' );
            $this->Assert( method_exists( $event, 'Exists' ), 'Event::Exists method does not exist' );
        }
        public function TestCreateEvent() {
            $event = New Event();
            $event->Typeid = EVENT_USER_PROFILE_VISITED;
            $event->Itemid = 1;
            $event->Userid = 3;
            $this->AssertFalse( $event->Exists(), 'Event appears to exist before saving' );
            $event->Save();
            $this->Assert( $event->Exists(), 'Event does not exist after creating' );
            $this->mEventId = $event->Id;

            $event2 = New Event( $this->mEventId );
            $this->AssertEquals( $event->Typeid, $event2->Typeid, 'Different event typeid after creating new instance' );
            $this->AssertEquals( $event->Itemid, $event2->Itemid, 'Different event itemid after creating new instance' );
            $this->AssertEquals( $event->Userid, $event2->Userid, 'Different event userid after creating new instance' );
        }
        public function TestEditEvent() {
            $event = New Event( $this->mEventId );
			$exception = false;
            try {
                $event->Userid = 5;
                $event->Save();
            } catch ( EventException $e ) {
				$exception = true;
			}
		
			$this->Assert( $exception, "No exception was thrown when tried to edit an event" );
        }
		public function TestFindByType() {
			$events = Event_FindByType( EVENT_USER_PROFILE_VISITED, 0, 1 );
			$this->AssertEquals( 1, count( $events ), 'Only one event should be returned by finder when called with limit 1' );
			
			$event = $events[ 0 ];
			$this->AssertEquals( $this->mEventId, $event->Id );
			$this->AssertEquals( EVENT_USER_PROFILE_VISITED, $event->Typeid );
			$this->AssertEquals( 1, $event->Itemid );
			$this->AssertEquals( 3, $event->Userid );
			$this->Assert( $event->Model instanceof User );
		}
		public function TestFindByUser() {
		}
		public function TestFindByUserAndType() {
		}
		public function TestDeleteEvent() {
			$event = New Event( $this->mEventId );
			$this->Assert( $event->Exists(), "Event does not appear to exist before deleting" );
			$event->Delete();
			$this->AssertFalse( $event->Exists(), "Event appears to exist after deleting" );

			$event = New Event( $this->mEventId );
			$this->AssertFalse( $event->Exists(), "Deleted event appears to exist after creating new instance" );
		}
    }

    return New TestEvent();

?>
