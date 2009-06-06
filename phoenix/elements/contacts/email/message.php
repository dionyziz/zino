<?php
    class ElementContactsEmailMessage extends Element{
        function Render( $toname = '', $contact = null ){
            global $user;
            global $rabbit_settings;
            if ( !$user->Exists() ){
                return false;
            }
            if ( $toname == '' ){
                $toname = '(όνομα φίλου)';
            }

            ?>Γεια σου <?php
            echo $toname;
            ?>,

Σε έχω προσθέσει στους φίλους μου στο Zino. Γίνε μέλος στο Zino για να δεις τα προφίλ των φίλων σου, να φτιάξεις το δικό σου, και να μοιραστείς τις φωτογραφίες και τα νέα σου.

Για να δεις το προφίλ <?php
            if ( $user->Gender == 'f' ) {
                ?>της <?php
            }
            else {
                ?>του <?php
            }
            echo $user->Name;
            ?> στο Zino, πήγαινε στο:
<?php
            echo $rabbit_settings[ 'webaddress' ];
            if ( $contact == null ){
                ?>/join <?php
            }
            else{
                ?>/join?id=<?php
                echo $contact->Id;
                ?>&validtoken=<?php
                echo $contact->Validtoken;
            }
            ?> 

Ευχαριστώ,
<?php
            echo $user->Name;
        }
    }
?>
