<?php
    class ElementContactsEmailSubject extends Element{
        function Render(){
            global $user;
            ?>Πρόσκληση απο <?php
            if ( $user->Gender == 'f' ) {
                ?>την <?php
            }
            else {
                ?>τον <?php
            }
            echo $user->Name;
            ?> στο Zino<?php
        }
    }
?>
