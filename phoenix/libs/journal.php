<?php

    global $libs;
    $libs->Load( 'bulk' );

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindById( $id ) {
            $prototype = New Journal();
            $prototype->Id = $id;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByUser( $user, $offset = 0, $limit = 25 ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function Count() {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( * ) AS numjournals
                FROM
                    :journals'
            );
            $query->BindTable( 'journals' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            $numjournals = $row[ 'numjournals' ];

            return $numjournals;
        }
        public function FindAll( $offset = 0, $limit = 20 ) {
            return $this->FindByPrototype( New Journal(), $offset, $limit, array( 'Id', 'DESC' ) );
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
            w_assert( is_int( $length ) );
            $text = $this->Bulk->Text;
            $text = htmlspecialchars_decode( strip_tags( $text ) );
            $text = mb_substr( $text, 0, $length );
            return htmlspecialchars( $text );
        }
        public function OnBeforeCreate() {
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
        }
        protected function OnDelete() {
            global $libs;
            $libs->Load( 'event' );
            $libs->Load( 'comment' );

            --$this->User->Count->Journals;
            $this->User->Count->Save();

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );
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
