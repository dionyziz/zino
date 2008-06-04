<?php
    function ElementDeveloperDionyzizUser() {
        global $user;

        echo $user->Profile->Dob;
        ?><br /><?php
        echo $user->Profile->BirthDay;
        ?><br /><?php
        echo $user->Profile->BirthMonth;
        ?><br /><?php
        echo $user->Profile->BirthYear;
    }
?>
