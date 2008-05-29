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
            $this->Bulk->Save();
        }
        public function OnUpdate() {
            $this->Bulk->Save();
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
