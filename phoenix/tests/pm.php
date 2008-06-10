<?php
    
    class TestPMs extends Testcase {
        protected $mAppliesTo = 'libs/pm/pm';
        private $mUser;
        private $mUser2;
        
        public function SetUp() {
            global $libs;

            $libs->Load( 'pm/pm' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testpms' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
            $user = $ufinder->FindByName( 'testpms2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testpms';
            $this->mUser->Subdomain = 'testpms';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testpms2';
            $this->mUser2->Subdomain = 'testpms2';
            $this->mUser2->Save();
        }
        public function TestCreateFolder() {
        }
        public function TestCreatePM() {
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

    return New TestPMs();

?>
