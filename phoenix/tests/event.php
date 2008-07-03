<?php

    class TestEvent extends TestCase {
        protected $mAppliesTo = 'libs/event';
        private $mEventId;
		private $mUser;
		private $mUser2;

        public function SetUp() {
            global $libs;

            $libs->Load( 'event' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testevents' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
            $user = $ufinder->FindByName( 'testevents2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

			$this->mUser = New User();
			$this->mUser->Name = 'testevents';
			$this->mUser->Subdomain = 'testevents';
			$this->mUser->Save();

			$this->mUser2 = New User();
			$this->mUser2->Name = 'testevents2';
			$this->mUser2->Subdomain = 'testevents2';
			$this->mUser2->Save();
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
            $event->Typeid = EVENT_USERPROFILE_MOOD_UPDATED;
            $event->Itemid = $this->mUser->Id;
            $event->Userid = $this->mUser->Id;
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
            } catch ( Exception $e ) {
				$exception = true;
			}
		
			$this->Assert( $exception, "No exception was thrown when tried to edit an event" );
        }
		public function TestFindByType() {
			global $water; 

			$finder = New EventFinder();
			$events = $finder->FindByType( EVENT_USERPROFILE_MOOD_UPDATED, 0, 1 );
			$this->AssertEquals( 1, count( $events ), 'Only one event should be returned by finder when called with limit 1' );
			
			$event = $events[ 0 ];
			$this->AssertEquals( $this->mEventId, $event->Id );
			$this->AssertEquals( EVENT_USERPROFILE_MOOD_UPDATED, $event->Typeid );
			$this->AssertEquals( $this->mUser->Id, $event->Itemid );
			$this->AssertEquals( $this->mUser->Id, $event->Userid );
			
			$this->Assert( 'UserProfile', get_class( $event->Item ), 'Event model should be instance of UserProfile' );
		}
		public function TestFindByUser() {
			$event = New Event();
			$event->Userid = $this->mUser->Id;
			$event->Typeid = EVENT_USERPROFILE_UPDATED;
			$event->Itemid = $this->mUser->Id;
			$event->Save();

			$event2 = New Event();
			$event2->Userid = $this->mUser->Id;
			$event2->Typeid = EVENT_USERPROFILE_VISITED;
			$event2->Itemid = $this->mUser->Id;
			$event2->Save();

			$finder = New EventFinder();
			$events = $finder->FindByUser( $this->mUser );
			$this->Assert( is_array( $events ), 'FindByUser did not return an array' );
			$this->AssertEquals( 3, count( $events ), 'FindByUser did not return right number of events' ); // 3 here

			$typeids = array( EVENT_USERPROFILE_VISITED, EVENT_USERPROFILE_UPDATED, EVENT_USERPROFILE_MOOD_UPDATED );
            $itemids = array( $this->mUser->Id, $this->mUser->Id, $this->mUser->Id, $this->mUser->EgoAlbum->Id );
			foreach ( $events as $key => $e ) {
				$this->AssertEquals( $this->mUser->Id, $e->Userid, 'Wrong event userid' );
                $this->AssertEquals( $itemids[ $key ], $e->Itemid, 'Wrong event itemid' );
                $this->AssertEquals( $typeids[ $key ], $e->Typeid, 'Wrong event typeid' );
			}

			$event->Delete();
			$event2->Delete();
		}
		public function TestFindLatest() {
			$event = New Event();
			$event->Userid = $this->mUser2->Id;
			$event->Typeid = EVENT_USERPROFILE_UPDATED;
			$event->Itemid = $this->mUser2->Id;
			$event->Save();

			$event2 = New Event();
			$event2->Userid = $this->mUser2->Id;
			$event2->Typeid = EVENT_USERPROFILE_VISITED;
			$event2->Itemid = $this->mUser->Id;
			$event2->Save();

			$finder = New EventFinder();
			$events = $finder->FindLatest( 0, 3 );

			$this->AssertEquals( 3, count( $events ), 'Wrong number of events' );

            die( print_r( $events ) );

			$typeids = array( EVENT_USERPROFILE_VISITED, EVENT_USERPROFILE_UPDATED, EVENT_USERPROFILE_MOOD_UPDATED );
			$itemids = array( $this->mUser->Id, $this->mUser2->Id, $this->mUser->Id );
            $userids = array( $this->mUser2->Id, $this->mUser2->Id, $this->mUser->Id );
            foreach ( $events as $key => $e ) {
                $this->AssertEquals( $typeids[ $key ], $e->Typeid, 'Wrong typeid' );
                $this->AssertEquals( $itemids[ $key ], $e->Itemid, 'Wrong itemid' );
                $this->AssertEquals( $userids[ $key ], $e->Userid, 'Wrong userid' );
            }

            $event->Delete();
            $event2->Delete();
		}
		public function TestDeleteEvent() {
			$event = New Event( $this->mEventId );
			$this->Assert( $event->Exists(), "Event does not appear to exist before deleting" );
			$event->Delete();
			$this->AssertFalse( $event->Exists(), "Event appears to exist after deleting" );

			$event = New Event( $this->mEventId );
			$this->AssertFalse( $event->Exists(), "Deleted event appears to exist after creating new instance" );
		}
		public function TearDown() {
			if ( is_object( $this->mUser ) ) {
				$this->mUser->Delete();
			}
			if ( is_object( $this->mUser2 ) ) {
				$this->mUser2->Delete();
			}
		}
    }

    return New TestEvent();

?>
