<?php
    class ElementStoreThanks extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            $libs->Load( 'user/profile' );
            
            if ( !$user->Exists() ) {
                return;
            }
            
            ?>
            <h1>
                <div class="city">
                    <div class="cityend1">
                    </div>
                </div>
                <span>
                    <a href="http://www.zino.gr/"><img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino" /></a>
                    <a href="http://store.zino.gr/"><img src="http://static.zino.gr/phoenix/store/store.png" alt="STORE" /></a>
                </span>
            </h1>
            <a class="back" href="http://www.zino.gr/">πίσω στο zino</a>
            <div class="content">
            <h3>Σ' ευχαριστούμε για την αγορά σου, <?php
            echo $user->Name;
            ?>!</h3>

            <p>Ένα e-mail έχει αποσταλεί στην διεύθυνσή σου, <?php
                        echo $user->Profile->Email;
            ?>, με τις λεπτομέρειες σχετικά με την παραγγελία σου.</p>

            <dl>
            <dt>Προϊόν:</dt><dd>Zino Necklace Φυσαλίδα</dd>
            <dt>Τιμή:</dt><dd>15€</dd>
            <dt>Μεταφορικά/έξοδα αντικαταβολής:</dt><dd>0€ (καλύπτονται από το Zino)</dd>
            <dt>Παράδοση:</dt><dd><?php
            switch ( $user->Profile->Placeid ) {
                case 1:
                case 2:
                case 102:
                case 107:
                    ?>Χέρι-με-χέρι (από αντιπρόσωπό μας)</dd></dl>
                    
                    <p>Θα επικοινωνήσουμε σύντομα μαζί σου τηλεφωνικά για την παράδοση του προϊόντος.</p><?php
                    break;
                default:
                    ?>Ταχυδρομικά (με αντικαταβολή)</dd></dl>
                    
                    <p>Τα Ελληνικά Ταχυδρομεία θα επικοινωνήσουν σύντομα μαζί σου γραπτά για την παράδοση του προϊόντος.</p><?php
            }

            ?>
            <p>Για οποιαδήποτε απορία σχετικά με την αγορά σου, επικοινώνησε μαζί μας στο <strong>info@zino.gr</strong>
            ή αφήνοντας ένα σχόλιο στο <a href="http://oniz.zino.gr/">προφίλ της ομάδας του Zino</a>.</p>

            <p>Σ' ευχαριστούμε για άλλη μία φορά για την αγορά σου! Οι αγορές βοηθούν πολύ το Zino να αναπτυχθεί και 
            να συνεχίσει να υπάρχει <span class="emoticon-smile">.</span></p>
            
            <p style="font-size: 120%">Πίσω στο <a href="store.php?p=product&amp;name=necklace">Zino Necklace Φυσαλίδα</a></p>
            </div><?php
        }
    }
?>
