<?php
    class AdminAction extends Satori {
        protected $mDbTabeAlias = 'adminactions';
        
        public function saveAdminAction ( $userid , $userip , $actiontype , $targettype , $targetid ) {
        
            $this->userid=$userid;
            $this->userip=$userip;
            $this->targetid=$targetid;
            
            $today = date( 'Y-m-d H:i:s');
            $this->date = $today;
        
            switch ( $actiontype ) {
                case "delete":
                    $this->type = 1;
                    break;
                case "edit":
                    $this->type = 2;
                    break;
                default:
                    return;
            }
        
            switch ( $targettype ) {
                case "comment":
                    $this->targettype = 1;
                    break;
                case "poll":
                    $this->targettype = 2;
                    break;
                case "journal":
                    $this->targettype = 3;
                    break;
                case "image":
                    $this->targettype = 4;
                    break;
                default:
                    return;
            }
            
            $this->Save();
            
            return;        
        }
        
    }
?>
