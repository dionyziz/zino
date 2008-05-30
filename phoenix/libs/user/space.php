<?php
    global $libs;
    
    $libs->Load( 'bulk' );
    
    class UserSpace extends Satori {
        protected $mDbTableAlias = 'userspaces';
        
        public function GetText() {
            return $this->Bulk->Text;
        }
        public function SetText( $value ) {
            $this->Bulk->Text = $value;
        }
        public function OnCreate() {
            die( 'Superdeath!' );

            $this->OnUpdate();
        }
        public function OnUpdate() {
            global $libs;
            global $water;

            $water->Trace( 'Saving bulk' );
            $this->Bulk->Save();
            $water->Trace( 'Saved bulk with id ' . $this->Bulk->Id );

            $libs->Load( 'event' );
            $event = New Event();
            $event->Typeid = EVENT_USERSPACE_UPDATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
        public function Delete() {
            throw New UserException( 'Userspaces cannot be deleted' );
        }
    }

?>
