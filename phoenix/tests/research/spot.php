<?php

    class TestResearchSpot extends Testcase {
        protected $mAppliesTo = 'libs/research/spot';
        
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Spot' ), 'Spot class does not exist' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( 'Spot', 'CommentCreated' ) );
            $this->Assert( method_exists( 'Spot', 'FavouriteCreated' ) );
            $this->Assert( method_exists( 'Spot', 'VoteCreated' ) );
            $this->Assert( method_exists( 'Spot', 'GetContent' ) );
            $this->Assert( method_exists( 'Spot', 'GetSamecom' ) );
        }
        public function TestCommentCreated() {
            $john = New User();
            $john->Name = "JohnTester";
            $john->Password = "travolta";
            $john->Subdomain = "travolta";
            $john->Save();

            $george = New User();
            $george->Name = "GeorgeTester";
            $george->Password = "washington";
            $george->Subdomain = "washington";
            $george->Save();


            $samecom = Spot::GetSamecom( $john, $george );
            $this->AssertEquals( 0, $samecom, 'Samecom should be zero for two new users' );

            $comment = New Comment();
            $comment->Userid = $george->Id;
            $comment->Itemid = $john->Id;
            $comment->Typeid = TYPE_USERPROFILE;
            $comment->Text = "Hey John!";
            $comment->Save();

            $samecom = Spot::GetSamecom( $john, $george );
            $this->AssertEquals( 1, $samecom, 'Samecom has wrong value for 1 samecom' );

            $comment->Delete();
            $john->Delete();
            $george->Delete();
        }
    }

    return New TestResearchSpot();

?>
