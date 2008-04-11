<?php

    /*
        Update user activity

        $lastactive = New UserLastActive( $user->Id ); // $user: logged in user
        $lastactive->Updated = NowDate();
        $lastactive->Save();
    */

    class UserLastActive extends Satori {
        protected $mDbTableAlias = 'lastactive';
        
        public function LoadDefaults() {
            $this->Updated = NowDate();
        }
    }

?>
