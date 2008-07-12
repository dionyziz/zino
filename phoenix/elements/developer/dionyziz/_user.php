<?php
    class ElementDeveloperDionyzizUser extends Element {
        public function Render() {
            global $user;

            echo $user->Profile->Dob;
            ?><br /><?php
            echo $user->Profile->BirthDay;
            ?><br /><?php
            echo $user->Profile->BirthMonth;
            ?><br /><?php
            echo $user->Profile->BirthYear;
        }
    }
?>
