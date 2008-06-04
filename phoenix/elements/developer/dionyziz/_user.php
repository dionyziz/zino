<?php
    function ElementDeveloperDionyzizUser() {
        global $user;

        echo $user->Profile->Dob;
        ?><br /><?php
        echo $user->BirthDay;
        ?><br /><?php
        echo $user->BirthMonth;
        ?><br /><?php
        echo $user->BirthYear;
    }
?>
