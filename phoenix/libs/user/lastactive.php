<?php

    /*
        Update user activity

        $lastactive = New UserLastActive( $user->Id ); // $user: logged in user
        $lastactive->Updated = NowDate();
        $lastactive->Save();
    */

    class UserLastActive extends Satori {
        protected $mDbTableAlias = 'lastactive';
        
        public function OnBeforeUpdate() {
            $this->Updated = NowDate();
        }
        public function LoadDefaults() {
            $this->Updated = NowDate();
        }
        public function IsOnline() {
            return time() - strtotime( $this->Updated ) < 60 * 6;
        }
    }

?>
