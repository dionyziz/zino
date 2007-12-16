<?php

    global $libs;
    $libs->Load( 'search' );

    /*
        $comment = New CommentPrototype();
        $comment->TypeId = 1;
        $comment->ItemId = $userid->Get();
        $comment->DelId = 0;

        $user = New UserPrototype();

        $image = New ImagePrototype();

        $search = new Search();
        $search->AddPrototype( $comment );
        $search->AddPrototype( $user );
        $search->AddPrototype( $image );

        $search->Connect( $comment, $user, $connectiontype = 'right' );
        $search->Connect( $user, $image, $connectiontype = 'left' );

        $search->SetOrderBy( $comment, 'Created', 'DESC' );
        $search->SetGroupBy( $user, 'Id' );

        $search->Limit = 20;
    */

    final class TestSearch extends Testcase {
        public function TestClassesExist() {
            global $libs;

            $this->Assert( class_exists( 'Search' ) );
            $this->Assert( class_exists( 'SearchPrototype' ) );

            $libs->Load( 'prototype/user' );
            $this->Assert( class_exists( 'UserPrototype' ) );

            $libs->Load( 'prototype/comment' );
            $this->Assert( class_exists( 'CommentPrototype' ) );

            $libs->Load( 'prototype/image' );
            $this->Assert( class_exists( 'ImagePrototype' ) );
        }
        public function TestMethodsExist() {
            $search = New Search();
            $this->Assert( method_exists( $search, 'AddPrototype' ) );
            $this->Assert( method_exists( $search, 'Connect' ) );
            $this->Assert( method_exists( $search, 'SetOrderBy' ) );
            $this->Assert( method_exists( $search, 'SetGroupBy' ) );
            $this->Assert( method_exists( $search, 'Get' ) );
            $this->Assert( method_exists( $search, 'Results' ) );
        }
        public function TestEmpty() {
            $search = New Search();
            $ret = $search->Get();

            $this->Assert( is_array( $ret ), 'Empty search did not return an array' );
            $this->Assert( empty( $ret ), 'Empty search should return an empty array' );
            $this->AssertEquals( $search->Results(), 0, 'Empty search should have 0 results' );
        }
        public function TestSimple() {
            die( "starting simple" );
            $user = New UserPrototype();

            $search = New Search();
            $search->AddPrototype( $user );
            $ret = $search->Get();

            $this->Assert( is_array( $ret ), 'Simple search did not return an array' );
            $this->AssertFalse( !empty( $ret ), 'Simple search returned an empty array' );
            $this->AssertEquals( count( $ret ), count( ListAllUsers() ), 'Simple search did not return the right number of rows' );
            $this->AssertEquals( $search->Results(), count( $ret ), 'Number of results differs from the count of the rows returned' );
            
            $rightClass = true;
            foreach ( $ret as $user ) {
                if ( !$user instanceof User ) {
                    $rightClass = false;
                    break;
                }
            }

            $this->Assert( $rightClass, 'Every row returned by searched should be of the specified prototype' );
        }
        public function TestSimpleFilter() {
            $commentNew = New Comment();
            $commentNew->Text = "foo";
            $commentNew->ParentId = 1;
            $commentNew->Save();

            $commentPrototype = New CommentPrototype();
            $commentPrototype->ParentId = 1;

            $search = New Search();
            $search->AddPrototype( $commentPrototype );
            $comments = $search->Get();

            $this->Assert( is_array( $comments ), 'Search with simple filter did not return an array' );
            $this->Assert( count( $comments ) > 0, 'Search with simple filter did not return comments, while there is at least one' );

            $property = true;
            $class = true;
            $found = false;
            foreach ( $comments as $comment ) {
                if ( !$class instanceof Comment ) {
                    $class = false;
                }
                if ( !$comment->ParentId == 1 ) {
                    $property = false;
                }
                if ( $comment->Id == $commentNew->Id ) {
                    $found = true;
                    $this->AssertEqual( $comment, $commentNew, 'New comment found, but it is not equal' );
                }
            }
            
            $this->Assert( $property, 'Every comment returned should have the properties of the prototype' );
            $this->Assert( $found, 'Search did not return all the comments it should' );
        }
        public function TestMultipleFilters() {
        }
        public function TestOrderBy() {
        }
        public function TestGroupBy() {
        }
        public function TestOnePrototypeFull() {
        }
        public function TestConnect() {
        }
        public function TestComplex() {
        }
        public function TestRealWorld() {
        }
        public function TestUserPrototype() {
        }
        public function TestCommentPrototype() {
        }
        public function TestImagePrototype() {
        }
        // ... add simple tests for any other prototype created
    }
    
    return new TestSearch();
?>
