<?php

    class TestRelation extends Testcase {
        protected $mAppliesTo = 'libs/relation/relation';
        private $mUser;
        private $mUser2;

        private function CreateType( $text ) {
            $typefinder = New RelationTypeFinder();
            $type = $typefinder->FindByText( $text );
            if ( !is_object( $type ) ) {
                $type = New RelationType();
                $type->Text = $text;
                $type->Save();
            }
        }
        private function DeleteType( $text ) {
            $typefinder = New RelationTypeFinder();
            $type = $typefinder->FindByText( $text );
            if ( is_object( $type ) ) {
                $type->Delete();
            }
        }
        public function SetUp() {
            global $libs;
            $libs->Load( 'relation/relation' );
            
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testrelations' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $user = $ufinder->FindByName( 'testrelations2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testrelations';
            $this->mUser->Subdomain = 'testrelations';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testrelations2';
            $this->mUser2->Subdomain = 'testrelations2';
            $this->mUser2->Save();

            $typefinder = New RelationTypeFinder();
            $this->CreateType( 'lover' );
            $this->CreateType( 'friend' );
            $this->CreateType( 'unknown' );
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'FriendRelation' ), 'Relation class does not exist' );
            $this->Assert( class_exists( 'FriendRelationFinder' ), 'RelationFinder class does not exist' );
        }
        public function TestMethodsExist() {
            $finder = New FriendRelationFinder();
            $this->Assert( method_exists( $finder, 'FindByUser' ), 'RelationFinder::FindByUser method does not exist' );
            $this->Assert( method_exists( $finder, 'FindByFriend' ), 'RelationFinder::FindByFriend method does not exist' );
        }
        public function TestCreate() {
            $typefinder = New RelationTypeFinder();
            $lover_type = $typefinder->FindByText( 'lover' );

            if ( !is_object( $lover_type ) || $lover_type->Id <= 0 ) {
                $lover_type = New RelationType();
                $lover_type->Text = 'lover';
                $lover_type->Save();
            }

            $ufinder = New UserFinder();
            $abresas = $ufinder->FindByName( 'abresas' );
            
            $relation = New FriendRelation();
            $relation->Userid = $this->mUser->Id;
            $relation->Friendid = $abresas->Id;
            $relation->Typeid = $lover_type->Id;
            $relation->Save();

            $r = New FriendRelation( $relation->Id );
            $this->AssertEquals( $this->mUser->Id, $r->Userid, 'Wrong userid' );
            $this->AssertEquals( $abresas->Id, $r->Friendid, 'Wrong friendid' );
            $this->AssertEquals( $lover_type->Id, $r->Typeid, 'Wrong typeid' );
            $this->AssertEquals( 'lover', $r->Type, 'Wrong type' );

            $relation->Delete();
        }
        public function TestFindByUser() {
            $ufinder = New UserFinder();
            $abresas = $ufinder->FindByName( 'abresas' );

            $typefinder = New RelationTypeFinder();
            $lover_type = $typefinder->FindByText( 'lover' );
            $friend_type = $typefinder->FindByText( 'friend' );
            $unknown_type = $typefinder->FindByText( 'unknown' );

            $relation1 = New FriendRelation();
            $relation1->Userid = $this->mUser->Id;
            $relation1->Friendid = $this->mUser2->Id;
            $relation1->Typeid = $friend_type->Id;
            $relation1->Save();
            
            $relation2 = New FriendRelation();
            $relation2->Userid = $this->mUser2->Id;
            $relation2->Friendid = $abresas->Id;
            $relation2->Typeid = $unknown_type->Id;
            $relation2->Save();

            $relation3 = New FriendRelation();
            $relation3->Userid = $this->mUser->Id;
            $relation3->Friendid = $abresas->Id;
            $relation3->Typeid = $lover_type->Id;
            $relation3->Save();

            $finder = New FriendRelationFinder();
            $relations = $finder->FindByUser( $this->mUser );
            $this->AssertEquals( 2, count( $relations ), 'Wrong number of relations' );
           
            $friendids = array( $this->mUser2->Id, $abresas->Id );
            $typeids = array( $friend_type->Id, $lover_type->Id );
            foreach ( $relations as $relation ) {
                $this->AssertEquals( $this->mUser->Id, $relation->Userid, 'Wrong userid' );
                $this->Assert( in_array( $relation->Friendid, $friendids ), 'Wrong friendid' );
                $this->Assert( in_array( $relation->Typeid, $typeids ), 'Wrong typeid' );
            }

            $relation1->Delete();
            $relation2->Delete();
            $relation3->Delete();
        }
        public function TestFindByFriend() {
            $ufinder = New UserFinder();
            $abresas = $ufinder->FindByName( 'abresas' );

            $typefinder = New RelationTypeFinder();
            $lover_type = $typefinder->FindByText( 'lover' );
            $friend_type = $typefinder->FindByText( 'friend' );
            $unknown_type = $typefinder->FindByText( 'unknown' );

            $relation1 = New FriendRelation();
            $relation1->Userid = $this->mUser->Id;
            $relation1->Friendid = $this->mUser2->Id;
            $relation1->Typeid = $friend_type->Id;
            $relation1->Save();
            
            $relation2 = New FriendRelation();
            $relation2->Userid = $this->mUser2->Id;
            $relation2->Friendid = $abresas->Id;
            $relation2->Typeid = $unknown_type->Id;
            $relation2->Save();

            $relation3 = New FriendRelation();
            $relation3->Userid = $this->mUser->Id;
            $relation3->Friendid = $abresas->Id;
            $relation3->Typeid = $lover_type->Id;
            $relation3->Save();

            $finder = New FriendRelationFinder();
            $relations = $finder->FindByFriend( $abresas, 0, 2 );
            $this->AssertEquals( 2, count( $relations ), 'Wrong number of relations' );

            $userids = array( $this->mUser->Id, $this->mUser2->Id );
            $typeids = array( $unknown_type->Id, $lover_type->Id );
            foreach ( $relations as $relation ) {
                $this->AssertEquals( $abresas->Id, $relation->Friendid, 'Wrong friendid' );
                $this->Assert( in_array( $relation->Userid, $userids ), 'Wrong userid' );
                $this->Assert( in_array( $relation->Typeid, $typeids ), 'Wrong typeid' );
            }

            $relation = $finder->IsFriend( $this->mUser, $this->mUser2 );
            $this->Assert( $relation instanceof FriendRelation, 'IsFriend did not return a FriendRelation instance' );
            
            $relation1->Delete();
            $relation2->Delete();
            $relation3->Delete();
        }
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
            if ( is_object( $this->mUser2 ) ) {
                $this->mUser2->Delete();
            }
            $this->DeleteType( 'lover' );
            $this->DeleteType( 'friend' );
            $this->DeleteType( 'unknown' );
        }
    }

    return New TestRelation();

?>
