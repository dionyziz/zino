<?php
    /// Content-type: text/plain ///
    
    class ElementStoreMailPurchased extends Element {
        public function Render( StorePurchase $purchase ) {
            global $user;
            
            ?>Σ' ευχαριστούμε για την αγορά σου, <?php
            echo $user->Name;
            ?>!

Ακολουθούν οι λεπτομέρειες της παραγγελίας σου.

Προϊόν: <?php
	echo $purchase->Item->Friendlyname;
?> 
Τιμή: <?php
	echo $purchase->Item->Price;
?>€
Παράδοση: <?php
            switch ( $user->Profile->Placeid ) {
                case 1:
                case 2:
                case 102:
                case 107:
                case 139:
                case 164:
                    ?>Χέρι-με-χέρι (από αντιπρόσωπό μας)
                    
Θα επικοινωνήσουμε σύντομα μαζί σου τηλεφωνικά για την παράδοση του προϊόντος. <?php
                    break;
                default:
                    ?>Ταχυδρομικά (με αντικαταβολή)
                    
Τα Ελληνικά Ταχυδρομεία θα επικοινωνήσουν σύντομα μαζί σου γραπτά για την παράδοση του προϊόντος. <?php
            }

?>

Για οποιαδήποτε απορία σχετικά με την αγορά σου, επικοινώνησε μαζί μας στο info@zino.gr 
και ανάφερε τον αριθμό της παραγγελίας: <?php
            echo $purchase->Userid;
            ?>/<?php
            echo $purchase->Id;
            ?>.

Σ' ευχαριστούμε για άλλη μία φορά για την αγορά σου! Οι αγορές βοηθούν πολύ το Zino να αναπτυχθεί και 
να συνεχίσει να υπάρχει :-)<?php

            Element( 'email/footer' );
            
            return 'Η παραγγελία σου από το ZinoSTORE!';
        }
    }
?>
