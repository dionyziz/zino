<?php
    global $libs;
    
    $libs->Load( 'bulk' );
    
    class UserSpace extends Satori {
        protected $mDbTableAlias = 'userspaces';
        
        public function GetText( $length = false ) {
            $text = $this->Bulk->Text;

            if ( $length == false ) {
                return $text;
            }
            else {
                $text = preg_replace( "#<[^>]*?>#", "", $text ); // strip all tags
                return mb_substr( $text, 0, $length );
            }
        }
        public function SetText( $value ) {
            $this->Bulk->Text = $value;
        }
        public function OnBeforeCreate() {
            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }
        public function OnCreate() {
            $this->OnUpdate();
        }
        public function OnUpdate() {
            global $libs;

            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;

            /*
            $libs->Load( 'event' );
            $event = New Event();
            $event->Typeid = EVENT_USERSPACE_UPDATED;
            $event->Itemid = $this->Userid;
            $event->Userid = $this->Userid;
            $event->Save();
            */
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
    }
?>
