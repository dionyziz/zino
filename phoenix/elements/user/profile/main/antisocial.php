<?php
    class ElementUserProfileMainAntisocial extends Element {
        public function Render( User $theuser ) {
            ?>
            <div id="antisocial">
            <?php
            if ( $theuser->Gender == 'f' ) {
                ?>Η<?php
            }
            else {
                ?>Ο<?php
            }
            ?> <?php
            echo $theuser->Name;
            ?> σε έχει προσθέσει στους φίλους, αλλά εσυ οχι.
            <div><a href="" onclick="Profile.AntisocialAddFriend( <?php
            echo $theuser->Id;
            ?> ); return false">Πρόσθεσέ <?php
            if ( $theuser->Gender == 'f' ) {
                ?>την<?php
            }
            else {
                ?>τον<?php
            }
            ?> στους φίλους</a></div>
            </div>
            <?php
        }
    }
?>
