<?php

    global $libs;
    $libs->Load( 'user' ); // just in case
    
    class UserPrototype extends SearchPrototype {
        public function UserPrototype() {
            $this->mClass = 'User';
            $this->mTable = 'merlin_users';

            $this->SetReferences( array(
                'Image' => array( array( 'Avatar', 'Id', 'left' ) )
            ) );

            $this->SetFields( array(
                'user_id'       => 'Id',
                'user_name'     => 'Name',
                'user_password' => 'Password',
                'user_icon'     => 'Avatar',
                'user_locked'   => 'DelId'
            ) );

            parent::SearchPrototype();
        }
    }

?>
