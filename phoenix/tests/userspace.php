<?php
    class TestUserspace extends Testcase {
        protected $mAppliesTo = 'libs/user/space';
        protected $mUser;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Name = 'testspace';
            $this->mUser->Subdomain = 'testspace';
            $this->mUser->Save();
        }
        public function TestText() {
            $this->mUser->Space->Text = 'Hello, world!';
            $this->AssertEquals( 'Hello, world!', $this->mUser->Space->Text, 'Could not retrieve the userspace text we just set' );
            $this->mUser->Space->Save();

            $theuser = New User( $this->mUser->Id );

            $this->AssertEquals( 'Hello, world!', $theuser->Space->Text, 'Could not retrieve the userspace text we set when re-querying for user' );
        }
        public function TearDown() {
            $this->mUser->Delete();
        }
    }
    
    return New TestUserspace();
?>
