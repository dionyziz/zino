<?php
    define( 'COMMENT_JOURNAL', 0 );
    define( 'COMMENT_PROFILE', 1 );
    define( 'COMMENT_IMAGE',   2 );
    define( 'COMMENT_POLL',    3 );
    
	global $libs;
	
	// $libs->Load( 'article' );
    $libs->Load( 'bulk' );
	$libs->Load( 'image/image' );
	$libs->Load( 'search' );
    $libs->Load( 'poll' );

    function Comment_MakeTree( $comments, $reverse = false ) {
        $parented = array();
        if ( !is_array( $comments ) ) {
            return $parented;
        }

        foreach( $comments as $comment ) {
            if ( $reverse ) {
                array_push( $parented[ $comment->ParentId ], $comment );
            }
            else {
                $parented[ $comment->ParentId ][] = $comment;
            }
        }
        
        return $parented;
    }
	
    class Comment extends Satori {
        protected $mId;
        protected $mCreated; // mysql datetime
        protected $mDate; // day _greekmonth_ year
        public $Since; // e.g. 5 hours ago
        protected $mUserId;
        protected $mUser;
        protected $mUserIp;
        protected $mText;
        protected $mParentId;
        protected $mParent;
        protected $mDelId;
        protected $mTypeId;
        protected $mItem;
        protected $mItemId;
        protected $mBulkId;
        protected $mCreateYear;
        protected $mCreateMonth;
        protected $mCreateDay;
        protected $mCreateHour;
        protected $mCreateMinute;
        protected $mCreateSecond;

        public function GetText() {
            global $blk;

            if ( $this->mText === false ) {
                if ( $this->BulkId === false ) {
                    return "";
                }
                $this->mText = $blk->Get( $this->BulkId ); 
            }

            return $this->mText;
        }
        public function GetURL() {
            return $this->Item->Url . "#comment_" . $this->Id;
        }
        public function IsDeleted() {
            return $this->DelId > 0;
        }
        public function GetParent() {
            if ( $this->mParent === false ) {
                $this->mParent = new Comment( $this->ParentId );
            }
            return $this->mParent;
        }
        public function GetItem() {
            if ( $this->mItem === false ) {
                switch ( $this->TypeId ) {
                    case COMMENT_JOURNAL:
                        // TODO
                        break;
                    case COMMENT_PROFILE:
                        $this->mItem = New User( $this->ItemId );
                        break;
                    case COMMENT_IMAGE:
                        $this->mItem = New Image( $this->ItemId );
                        break;
                    case COMMENT_POLL:
                        $this->mItem = New Poll( $this->ItemId );
                        break;
                }
            }
            
            return $this->mItem;
        }
        public function SetItem( $item ) {
            $this->mItem = $item;
        }
		public function GetUser() {
            if ( $this->mUser === false ) {
                $this->mUser = New User( $this->UserId );
            }
            return $this->mUser;
		}
        public function IsEditableBy( User $user ) {
            if ( !$user->Exists() ) {
                return false;
            }
    		return $user->CanModifyCategories() || ( $user->Exists() && $this->User()->Id() == $user->Id() && daysDistance( $this->SQLDate() ) < 1 );
        }
        public function UserIsSpamBot() {
            // Prepared query

			$query = $this->mDb->Prepare("
				SELECT
                    *
                FROM
                    `" . $this->mDbTable. "`
                WHERE
                    `comment_created` > ( NOW() - INTERVAL 15 SECOND ) AND
                    `comment_userip` = :UserIp
                ;
			");
			
			// Assign values to query
			$query->Bind( 'UserIp', UserIp() );
			
			// Execute query
            if ( $query->Execute() ) {
                // email dio
                $subject = "WARNING! Comment spambot detected!";
                $message = "Text submitted: " . $this->Text . "\n\n SpamBot Ip: " . UserIp();

                mail( 'dionyziz@gmail.com', $subject, $message );

                return true;
            }

            return false;
        }
        public function Save( $user = false ) {
            global $mc;
            global $libs;

            if ( $user === false ) {
                $user = $this->User;
            }
            if ( $this->Exists() && ( !$this->IsEditableBy( $user ) || $this->UserIsSpamBot() ) ) {
                return false;
            }
            $existed = $this->Exists();
            if ( !parent::Save() ) {
                die( "parent failed" );
                return false;
            }
            else if ( !$existed ) {
                $this->Item->CommentAdded();
                $mc->delete( 'latestcomments' );
                $user->AddContrib();
                if ( $this->Parent->Exists() ) {
                    $libs->Load( 'notify' );

                    $notify = New Notify();
                    $notify->FromUserId   = $this->User->Id();
                    $notify->ToUserId     = $this->Parent->User->Id();
                    $notify->ItemId       = $this->Id;
                    $notify->TypeId       = $this->TypeId;
                    if ( !$notify->Save() ) {
                        return false;
                    }
                    
                    Notify_CommentRead( $user->Id(), $this->Parent, $this->TypeId );
                }
            }
            return true;
        }
        public function Delete( $user = false ) {
            if ( $this->HasChildren() || $this->IsDeleted() ) {
                return false;
            }

            $this->DelId = 1;
            if ( $this->Save( $user ) ) {
                $this->Item->CommentAdded();
                $this->User->RemoveContrib();

                return true;
            }

            return false;
        }
        public function UndoDelete( $user ) {
            if ( !$this->IsDeleted() || $parent->IsDeleted() ) {
                return false;
            }

            $this->DelId = 0;
            if ( $this->Save() ) {
				$this->Item()->CommentAdded();
                $this->User->AddContrib();
                return true;
            }

            return false;
        }
        public function SetDefaults() {
			$this->Created  = NowDate();
		    $this->UserIp   = UserIp();
        }
        public function Comment( $construct = false ) {
            global $db;
            global $comments;

            $this->mDb      = $db;
            $this->mDbTable = $comments;

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

            $this->Satori( $construct );
			
            if ( $this->mCreated ) {
				ParseDate( $this->mCreated , 
							$this->mCreateYear , $this->mCreateMonth , $this->mCreateDay ,
							$this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond );
				
				$dateTimestamp = gmmktime( $this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond ,
										   $this->mCreateMonth , $this->mCreateDay , $this->mCreateYear );
				
				$this->mDate = MakeDate( $this->mCreated );
				$this->Since = dateDiff( $this->mCreated, NowDate() );
			}
           
			$this->mUser    = isset( $construct[ "user_id" ] )  ? New User( $construct ) : false;
            $this->mText    = false;
            $this->mItem    = false;
            $this->mParent  = false;
        }
    }
	
?>
