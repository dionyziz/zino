<?php
    global $libs;
    
    $libs->Load( 'bulk' );
    
    class UserSpace extends Satori {
        protected $mDbTableAlias = 'userspaces';
        
		protected function __get( $key ) {
			switch ( $key ) {
				case 'Text':
					return $this->Bulk->Text;
				default:
					return parent::__get( $key );
			}
		}
		protected function __set( $key, $value ) {
			switch ( $key ) {
				case 'Text':
					$this->Bulk->Text = $value;
					break;
				default:
					return parent::__set( $key, $value );
			}
		}
        public function GetText( $length = false ) {
            $text = $this->Bulk->Text;
			$text = htmlspecialchars_decode( strip_tags( $text ) );
			$text = mb_substr( $text, 0, $length );
			return htmlspecialchars( $text );
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
