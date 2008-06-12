<?php

    class TestNotifications extends Testcase {
        protected $mAppliesTo = 'libs/notify';
        private $mUser;
        private $mUser2;

        public function SetUp() {
            global $libs;
            $libs->Load( 'notify' );

            /* standar stuff */
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testnotify' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
            $user = $ufinder->FindByName( 'testnotify2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testnotify';
            $this->mUser->Subdomain = 'testnotify';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testnotify2';
            $this->mUser2->Subdomain = 'testnotify2';
            $this->mUser2->Save();
        }
        public function TestCreate() {
            $notif = New Notification();
            $notif->Fromuserid = $this->mUser->Id;
            $notif->Touserid = $this->mUser2->Id;
            $notif->Eventid = 4; // random
            $notif->Save();

            $n = New Notification( $notif->Id );
            $this->AssertEquals( $this->mUser->Name, $n->FromUser->Name, 'Wrong notification fromuser name' );
            $this->AssertEquals( $this->mUser2->Name, $n->ToUser->Name, 'Wrong notification touser name' );
            $this->AssertEquals( 4, $n->Eventid, 'Wrong eventid' );
            
            $n->Delete();
        }
        public function TestFiring() {
        }
        public function TestFindByUser() {
        }
        public function TestFindByUserAndComment() {
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

?>
