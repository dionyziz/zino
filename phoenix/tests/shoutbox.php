<?php

    class TestShoutbox extends Testcase {
        protected $mAppliesTo = 'libs/shoutbox';
        private $mUsers;

        public function SetUp() {
            global $libs;
            $libs->Load( 'shoutbox' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testshoutbox' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $user = New User();
            $user->Name = 'testshoutbox';
            $user->Subdomain = 'testshoutbox';
            $user->Rights = PERMISSION_SHOUTBOX_EDIT_ALL;
            $user->Profile->Email = 'bitbucket@kamibu.com';
            $user->Save();

            $this->mUsers[] = $user;
        }
        public function TestCreation() {
            $shout = New Shout();
            $shout->Userid = $this->mUsers[ 0 ]->Id;
            $shout->Text = "foo bar blah";
            $shout->Save();

            $s = New Shout( $shout->Id );
            $this->AssertEquals( $this->mUsers[ 0 ]->Id, $s->Userid, 'Wrong userid' );
            $this->AssertEquals( "foo bar blah", $s->Text, 'Wrong text' );

            $shout->Delete();
        }
        public function TestCount() {
        }
        public function TestFindLatest() {
        }
        public function TearDown() {
            if ( is_array( $this->mUsers ) ) {
                foreach ( $this->mUsers as $user ) {
                    if ( is_object( $user ) ) {
                        $user->Delete();
                    }
                }
            }
        }
    }

    return New TestShoutbox();

?>
