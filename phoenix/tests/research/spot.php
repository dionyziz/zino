<?php

    class TestResearchSpot extends Testcase {
        protected $mAppliesTo = 'libs/research/spot';
        protected $john;
        protected $george;

        public function SetUp() {
            global $libs;
            $libs->Load( 'comment' );
            $libs->Load( 'journal/journal' );

            $userfinder = New UserFinder();
            $john = $userfinder->FindByName( "JohnTester" );
            if ( $john !== false ) {
                $john->Delete();
            }

            $george = $userfinder->FindByName( "GeorgeTester" );
            if ( $george !== false ) {
                $george->Delete();
            }

            $this->john = New User();
            $this->john->Name = "JohnTester";
            $this->john->Password = "travolta";
            $this->john->Subdomain = "travolta";
            $this->john->Save();

            $this->george = New User();
            $this->george->Name = "GeorgeTester";
            $this->george->Password = "washington";
            $this->george->Subdomain = "washington";
            $this->george->Save();
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Spot' ), 'Spot class does not exist' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( 'Spot', 'CommentCreated' ) );
            $this->Assert( method_exists( 'Spot', 'FavouriteCreated' ) );
            $this->Assert( method_exists( 'Spot', 'VoteCreated' ) );
            $this->Assert( method_exists( 'Spot', 'GetContent' ) );
            $this->Assert( method_exists( 'Spot', 'GetJournals' ) );
            $this->Assert( method_exists( 'Spot', 'GetSamecom' ) );
        }
        public function TestCommentCreated() {
            $samecom = Spot::GetSamecom( $this->john, $this->george );
            $this->AssertEquals( 0, $samecom, 'Samecom should be zero for two new users' );

            $comment = New Comment();
            $comment->Userid = $this->george->Id;
            $comment->Itemid = $this->john->Id;
            $comment->Typeid = TYPE_USERPROFILE;
            $comment->Text = "Hey John!";
            $comment->Save();

            $samecom = Spot::GetSamecom( $this->john, $this->george );
            $this->AssertEquals( 0, $samecom, 'No samecom for one comment on a profile' );

            $comment1 = New Comment();
            $comment1->Userid = $this->john->Id;
            $comment1->Itemid = $this->john->Id;
            $comment1->Typeid = TYPE_USERPROFILE;
            $comment1->Text = ":dance: :dance:";
            $comment1->Save();

            $samecom = Spot::GetSamecom( $this->john, $this->george );
            $this->AssertEquals( 1, $samecom, 'Samecom has wrong value for 1 samecom' );

            $comment->Delete();
            $comment1->Delete();
        }
        public function TestGetJournals() {
            $journals = Spot::GetJournals( $this->john );
            $this->Assert( is_array( $journals ), 'Spot::GetJournals should return array' );
            $this->Assert( !empty( $journals ), 'Spot::GetJournals returned empty array' );
            $are_journals = true;
            foreach ( $journals as $journal ) {
                if ( !$journal instanceof Journal ) {
                    $are_journals = false;
                }
            }
            $this->Assert( $are_journals, 'Spot::GetJournals should return array of Journals' );
        }
        public function TestGetImages() {
            // TODO
            $images = Spot::GetImages( $this->john );
            $this->Assert( is_array( $images ), 'Spot::GetImages should return array' );
            $this->Assert( !empty( $images ), 'Spot::GetImages returned empty array' );
            $are_imagess = true;
            foreach ( $images as $image ) {
                if ( !$image instanceof Image ) {
                    $are_images = false;
                }
            }
            $this->Assert( $are_images, 'Spot::GetImages should return array of Images' );
        }
        public function TestGetVotes() {
            // TODO
            $votes = Spot::GetVotes( $this->john );
            $this->Assert( is_array( $votes ), 'Spot::GetVotes should return array' );
            $this->Assert( !empty( $votes ), 'Spot::GetVotes returned empty array' );
            $are_votes = true;
            foreach ( $votes as $vote ) {
                if ( !$vote instanceof Vote ) {
                    $are_votes = false;
                }
            }
            $this->Assert( $are_votes, 'Spot::GetVotes should return array of Votes' );
        }
        public function TearDown() {
            $this->john->Delete();
            $this->george->Delete();
        }
    }

    return New TestResearchSpot();

?>
