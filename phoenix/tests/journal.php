<?php

    class TestJournal extends Testcase {
        protected $mAppliesTo = 'libs/journal';
        private $mUser;

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Journal' ), 'Class Journal does not exist' );
            $this->Assert( class_exists( 'JournalFinder' ), 'Class JournalFinder does not exist' );
        }
        public function SetUp() {
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( "testjournals" );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $user = New User();
            $user->Name = 'testjournals';
            $user->Subdomain = 'testjournals';
            $user->Profile->Email = 'bitbucket@kamibu.com';
            $user->Save();

            $this->mUser = $user;
        }
        public function TestCreateJournal() {
            $journal = New Journal();
            $journal->Text = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cras rhoncus. Nam aliquam. Donec augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur pulvinar mollis nisi. Vivamus euismod vehicula turpis. Morbi turpis sapien, vestibulum sit amet, luctus ac, tincidunt imperdiet, lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec scelerisque est fermentum ante. In nec enim sit amet dui consectetuer auctor. Quisque sagittis nunc vel lorem. Curabitur id leo.";
            $journal->Userid = $this->mUser->Id;
            $this->AssertEquals( "Lorem ipsum dolor sit amet,...", $journal->GetText( 26 ) );
            $journal->Save();

            $journal2 = New Journal( $journal->Id );
            $this->AssertEquals( "Lorem ipsum dolor sit amet,...", $journal2->GetText( 26 ) );

            $journal2->Delete();
        }
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
        }
    }

    return New TestJournal();

?>
