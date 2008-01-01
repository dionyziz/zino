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
            $this->Assert( method_exists( $search, 'NumRows' ) );
        }
        public function TestEmpty() {
            $search = New Search();
            $ret = $search->Get();

            $this->Assert( is_array( $ret ), 'Empty search did not return an array' );
            $this->Assert( empty( $ret ), 'Empty search should return an empty array' );
            $this->AssertEquals( false, $search->Results(), 'Empty search should not have results' );
            $this->AssertEquals( 0, $search->NumRows(), 'Empty search should have 0 NumRows' );
        }
        public function TestSimple() {
            $user = New UserPrototype();

            $search = New Search();
            $search->AddPrototype( $user );
            $ret = $search->Get();

            $this->Assert( is_array( $ret ), 'Simple search did not return an array' );
            $this->AssertFalse( empty( $ret ), 'Simple search returned an empty array' );

            $usercount = count( ListAllUsers() );
            if ( $usercount <= 100 ) {
                $this->AssertEquals( $search->NumRows(), count( $ret ), 'Number of results differs from the count of the rows returned' );
                $this->AssertEquals( $usercount, count( $ret ), 'Simple search did not return the right number of rows' );
            }
            else {
                $this->AssertEquals( 100, count( $ret ), 'Simple search did not apply a limit of 100 rows' );
                $this->AssertEquals( 100, $search->NumRows(), 'Search NumRows should be 100 due to applied limit' );
            }

            
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
            $commentNew->BulkId = 1;
            $commentNew->ParentId = 1;
            w_assert( $commentNew->Save() );

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
                    $this->AssertEquals( $comment->BulkId, $commentNew->BulkId, 'New comment found, but it is not the same' );
                }
            }
            
            $this->Assert( $property, 'Every comment returned should have the properties of the prototype' );
            $this->Assert( $found, 'Search did not return all the comments it should' );

            $commentNew->Delete();
        }
        public function TestMultipleFilters() {
            $commentNew = New Comment();
            $commentNew->BulkId = 1;
            $commentNew->ParentId = 1;
            $commentNew->UserId = 1;
            $commentNew->ItemId = 10;
            $commentNew->TypeId = 2;
            $commentNew->DelId  = 2;
            w_assert( $commentNew->Save() );

            $commentPrototype = New CommentPrototype();
            $commentPrototype->ParentId = 1;
            $commentPrototype->BulkId   = 1;
            $commentPrototype->UserId   = 1;
            $commentPrototype->ItemId   = 10;
            $commentPrototype->TypeId   = 2;

            $search = New Search();
            $search->AddPrototype( $commentPrototype );
            $comments = $search->Get();

            $this->Assert( is_array( $comments ), 'Search with multiple filters did not return an array' );
            $this->Assert( count( $comments ) > 0, 'Search with multiple filters did not return comments, while there is at least one' );

            $property = true;
            $class = true;
            $found = false;
            foreach ( $comments as $comment ) {
                if ( !$class instanceof Comment ) {
                    $class = false;
                }
                if ( !$comment->ParentId == 1 && !$comment->BulkId == 1 && !$comment->UserId == 1 && !$comment->ItemId == 10 && !$comment->TypeId == 2 ) {
                    $property = false;
                }
                if ( $comment->Id == $commentNew->Id ) {
                    $found = true;
                    $this->AssertEquals( $comment->DelId, $commentNew->DelId, 'New comment found, but it is not the same' );
                }
            }
            
            $this->Assert( $property, 'Every comment returned should have the properties of the prototype' );
            $this->Assert( $found, 'Search did not return all the comments it should' );

            $commentNew->Delete();
        }
        public function TestOrderBy() {
            $user = New UserPrototype();

            $search = New Search();
            $search->AddPrototype( $user );
            $search->SetOrderBy( $user, 'Id', 'ASC' );

            $users = $search->Get();

            $usercount = count( ListAllUsers() );
            if ( $usercount > 100 ) {
                $this->AssertEquals( 100, count( $users ), "Wrong number of users returned by search after setting order by" );
            }
            else {
                $this->AssertEquals( $usercount, count( $users ), "Wrong number of users returned by search after setting order by" );
            }

            $order = true;

            $previd = -1;
            foreach ( $users as $user ) {
                if ( $user->Id() < $previd ) {
                    $order = false;
                    break;
                }
            }

            $this->AssertTrue( $order, "The order of the users returned by search was not the one requested" );
        }
        public function TestGroupBy() {
            $comment = New CommentPrototype();

            $search = New Search();
            $search->AddPrototype( $comment );
            $search->SetGroupBy( $comment, 'ParentId' );

            $comments = $search->Get();

            $this->Assert( count( $comments ) > 0, "Search with group by did not return any results" );

            $parentids = array();
            $groupby = true;
            foreach ( $comments as $comment ) {
                if ( in_array( $comment->ParentId, $parentids ) ) {
                    $groupby = false;
                    break;
                }
                $parentids[] = $comment->ParentId;
            }

            $this->AssertTrue( $groupby, "Search did not group the results as requested" );
        }
        public function TestOnePrototypeFull() {
            $commentNew = New Comment();
            $commentNew->UserId = 1;
            $commentNew->BulkId = 1;
            $commentNew->ParentId = 21;
            $commentNew->Save();
            $commentNew = New Comment( $commentNew->Id ); // refresh properties

            $comment = New CommentPrototype();
            $comment->UserId = 1;
            $comment->BulkId = 1;

            $search = New Search();
            $search->AddPrototype( $comment );
            $search->SetGroupBy( $comment, 'ParentId' ); 
            $search->SetOrderBy( $comment, 'Id', 'DESC' );

            $comments = $search->Get();

            // die( print_r( $comments ) );

            $this->Assert( is_array( $comments ), "Search did not return an array" );
            $this->Assert( $search->NumRows(), "Search did not return any results" );
            $this->AssertEquals( $search->NumRows(), count( $comments ), "Search NumRows not equal to count of objects returned" );
            
            $properties = true;
            $order = true;
            $group = true;
            $found = false;
            $double = false;

            $previd = null;
            $parentids = array();
            $ids = array();
            foreach ( $comments as $comment ) {
                if ( in_array( $comment->Id, $ids ) ) {
                    $double = true; 
                }
                if ( $comment->UserId != 1 || $comment->BulkId != 1 || $comment->Created != '0000-00-00 00:00:00' ) {
                    $properties = false;
                }
                if ( $comment->Id > $previd && $previd != null ) {
                    $order = false; 
                }
                if ( in_array( $comment->ParentId, $parentids ) ) {
                    $group = false;
                }
                if ( $comment->Id == $commentNew->Id ) {
                    $found = true;
                    $this->AssertEquals( $commentNew, $comment, 'Comment returned by search is not equal to the one created' );
                }

                $previd = $comment->Id;
                $parentids[] = $comment->ParentId;
                $ids[] = $comment->Id;
            }

            $this->AssertTrue( $properties, "Properties of all comments should be same with the prototype" );
            $this->AssertTrue( $order, "Order of the comments returned is not as requested" );
            $this->AssertTrue( $group, "Comments are not grouped as requested" );
            $this->AssertTrue( $found, "A comment matching the prototype was not on the list returned" );
            $this->AssertFalse( $double, "Same comment returned twice" );

            $commentNew->Delete();
        }
        public function TestConnect() {
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
