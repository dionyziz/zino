<?php

	global $libs;
	
	// $libs->Load( 'article' );
	$libs->Load( 'image/image' );
	$libs->Load( 'search' );
    $libs->Load( 'poll' );
	
    class Comment extends Satori {
        protected $mId;
        protected $mCreated;
        protected $mUserIp;
        protected $mText;
        protected $mParentId;
        protected $mParent;
        protected $mDate;
        protected $mDelId;
        protected $mSinceDate;
        protected $mTypeId;
        protected $mPage;
        protected $mPageId;
        protected $mUser;
        protected $mHasChilder;
        protected $mBulkId;

        public function IsDeleted() {
            return $this->DelId > 0;
        }
        public function GetParent() {
            if ( $this->mParent === false ) {
                $this->mParent = new Comment( $this->ParentId );
            }
            return $this->mParent;
        }
        public function GetPage() {
            if ( $this->mPage === false ) {
                switch ( $this->TypeId ) {
                    case 0: // journal post
                        // TODO
                        break;
                    case 1:
                        $this->mPage = New User( $this->PageId );
                        break;
                    case 2:
                        $this->mPage = New Image( $this->PageId );
                        break;
                    case 3:
                        $this->mPage = New Poll( $this->PageId );
                        break;
                }
            }
            
            return $this->mPage;
        }
        public function SetPage( $page ) {
            $this->mPage = $page;
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
            $sql = "SELECT
                        *
                    FROM
                        `" . $this->mDbTable. "`
                    WHERE
                        `comment_created` > ( NOW() - INTERVAL 15 SECOND ) AND
                        `comment_userip` = '" . UserIp() . "'
                    ;";

            if ( $this->mDb->Query( $sql ) ) {
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
            if ( $this->Exists() && !$this->IsEditableBy( $user ) || $this->UserIsSpamBot() ) {
                return false;
            }
            $existed = $this->Exists();
            if ( !parent::Save() ) {
                return false;
            }
            else if ( !$existed ) {
                $this->Page->CommentAdded();
                $mc->delete( 'latestcomments' );
                $user->AddContrib();
                if ( $this->Parent->Exists() ) {
                    $libs->Load( 'notify' );

                    Notify_Create( $this->User->Id(), $this->Parent->User->Id(), $this->Id, $this->TypeId );
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
                $this->Page->CommentAdded();
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
				$this->Page()->CommentAdded();
                $this->User->AddContrib();
                return true;
            }

            return false;
        }
        public function HasChildren() {
           if ( $this->mHasChildren === false ) {
				$sql = "SELECT `comment_id` FROM `" . $this->mDbTable . "` WHERE `comment_parentid` = '" . $this->Id . "' AND `comment_delid` = '0' LIMIT 1;";
                $this->mHasChildren = $this->mDb->Query( $sql )->Results();
           }

           return $this->mHasChildren;
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
                'comment_text'      => 'Text',
                'comment_pageid'    => 'PageId',
                'comment_typeid'    => 'TypeId',
                'comment_parentid'  => 'ParentId',
                'comment_delid'     => 'DelId',
                'comment_bulkid'    => 'BulkId' /* does this really exist? */
            ) );

            $this->Satori( $construct );
			
            if ( $this->mSubmitDate ) {
				ParseDate( $this->mSubmitDate , 
							$this->mCreateYear , $this->mCreateMonth , $this->mCreateDay ,
							$this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond );
				
				$dateTimestamp = gmmktime( $this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond ,
										   $this->mCreateMonth , $this->mCreateDay , $this->mCreateYear );
				
				$this->mDate = MakeDate( $this->mSubmitDate );
				$this->mSinceDate = dateDiff( $this->mSubmitDate , NowDate() );
			}
            
			$this->mUser    = isset( $construct[ "user_id" ] )  ? New User( $construct ) : false;
            $this->mPage    = false;
            $this->mParent  = false;
        }
    }
	
?>
