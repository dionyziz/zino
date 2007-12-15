<?php

    class CommentPrototype extends SearchPrototype {
        public function SetTypeId( $typeid ) {
            switch( $typeid ) {
                case 0:
                    $class = 'Journal';
                    break;
                case 1:
                    $class = 'User';
                    break;
                case 2:
                    $class = 'Image';
                    break;
                default:
                    w_assert( false );        
            }
            
            $this->mReferences[ 'TypeId' ] = array( $class, 'Id' );
        }
        public function CommentPrototype() {
            $this->mClass = 'Comment';
            $this->mTable = 'merlin_comments';

            $this->mReferences = array(
                'User' => array( array( 'UserId', 'Id' ) ),
                'Bulk' => array( array( 'BulkId', 'Id' ) )
            );

            $this->SetFields( array(
                'comment_id'        => 'Id',
                'comment_userid'    => 'UserId',
                'comment_created'   => 'Created',
                'comment_userip'    => 'UserIp',
                'comment_itemid'    => 'ItemId',
                'comment_typeid'    => 'TypeId',
                'comment_parentid'  => 'ParentId',
                'comment_delid'     => 'DelId',
                'comment_bulkid'    => 'BulkId'
            ) );

            parent::SearchPrototype();
        }
    }

?>
