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
            $folder = New PMFolder();
            $folder->Userid = $this->mUser->Id;
            $folder->Name = 'feedback';
            $folder->Save();

            $f = New PMFolder( $folder->Id );
            $this->AssertEquals( 'feedback', $f->Name, 'Wrong folder name' );
            $this->AssertEquals( $this->mUser->Id, $f->Userid, 'Wrong folder userid' );

            $f->Delete();
        }
        public function TestCreatePM() {
            $pm = New PM();
            $pm->Senderid = $this->mUser->Id;
            $pm->AddReceiver( $this->mUser );
            $pm->AddReceiver( $this->mUser2 );
            $pm->Text = 'foo bar blah';
            $pm->Save();

            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong pm text after creating' );

            $p = New PM( $pm->Id );
            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong pm text' );
            $this->AssertEquals( $this->mUser->Id, $pm->Senderid, 'Wrong pm senderid' );
            $this->AssertEquals( 2, count( $pm->Receivers ), 'Wrong receivers count' );
            $this->AssertEquals( $this->mUser2->Id, $pm->Receivers[ 1 ], 'Wrong second receiver' );

            $p->Delete();
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
