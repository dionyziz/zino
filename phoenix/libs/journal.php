<?php
    global $libs;

    $libs->Load( 'bulk' );
    $libs->Load( 'url' );
    $libs->Load( 'user/user' );

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindById( $id ) {
            $prototype = New Journal();
            $prototype->Id = $id;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype );
        }
        public function FindByUser( $user, $offset = 0, $limit = 25 ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByUserAndUrl( $user, $url, $offset = 0, $limit = 25 ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;
            $prototype->Url = $url;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype );
        }
        public function Count() {
            return parent::Count();
        }
        public function FindAll( $offset = 0, $limit = 20 ) {
            $journal = New Journal();
            $journal->Delid = 0;

            $journals = $this->FindByPrototype( $journal, $offset, $limit, array( 'Id', 'DESC' ), true );

            for ( $i = 0; $i < count( $journals ); ++$i ) {
                $journals[ $i ]->CopyUserFrom( New User( $journals[ $i ]->Userid ) );
            }
            return $journals;
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';

        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Text':
                    return $this->Bulk->Text;
                default:
                    return parent::__get( $key );
            }
        }
        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Text':
                    $this->Bulk->Text = $value;
                    break;
                default:
                    return parent::__set( $key, $value );
            }
        }
        public function GetText( $length ) {
            global $libs;
            
            $libs->Load( 'wysiwyg' );
            
            return WYSIWYG_PresentAndSubstr( $this->Bulk->Text, $length );
        }
        public function OnBeforeCreate() {
            $url = URL_Format( $this->Title );
            $length = strlen( $url );
            $finder = New JournalFinder();
            $exists = true;
            while ( $exists ) {
                $offset = 0;
                $exists = false;
                do {
// start debugging
                    if ( $this->Exists() ) {
                    	die( 'it exists' );
                    }
                    else {
                        die( 'it doesn\'t exist' );
                    }
// end debugging
                    $someOfTheRest = $finder->FindByUser( $this->User, $offset, 100 );
                    foreach ( $someOfTheRest as $j ) {
                        if ( strtolower( $j->Url ) == strtolower( $url ) ) {
                            $exists = true;
                            if ( $length < 255 ) {
                                $url .= '_';
                                ++$length;
                            }
                            else {
                                $url[ rand( 0, $length - 1 ) ] = '_';
                            }
                            break;
                        }
                    }
                    $offset += 100;
                } while ( count( $someOfTheRest ) && !$exists );
            }
            $this->Url = $url;

            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }
        public function OnUpdate() {            
            $this->Bulk->Save();
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        protected function OnCreate() {
            global $libs;

            $this->OnUpdate();

            $libs->Load( 'event' );

            ++$this->User->Count->Journals;
            $this->User->Count->Save();

            $event = New Event();
            $event->Typeid = EVENT_JOURNAL_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            Sequence_Increment( SEQUENCE_JOURNAL );
            
        }
        protected function OnBeforeDelete() {
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();

            return false;
        }
        protected function OnDelete() {
            global $user;
            global $libs;

            $libs->Load( 'event' );
            $libs->Load( 'comment' );
            $libs->Load( 'adminpanel/adminaction' );
                        
            if ( $user->id != $this->userid ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->id, UserIp(), OPERATION_DELETE, TYPE_JOURNAL, $this->id );
            }

            --$this->User->Count->Journals;
            $this->User->Count->Save();

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );

            Sequence_Increment( SEQUENCE_JOURNAL );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
        public function IsDeleted() {
            return $this->Exists() === false;
        }
    }

?>
