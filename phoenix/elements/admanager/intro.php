<?php
    class ElementAdManagerIntro extends Element {
        public function Render() {
            global $page;

            $page->SetTitle( 'Πρόγραμμα διαφημίσεων' );

            ?><div class="buyad">
                <h2 class="ad">Διαφήμιση στο Zino</h2>
                <p>κατευθύνετε αποτελεσματικά ένα ρεαλιστικό αγοραστικό κοινό</p>
                <ol>
                    <li class="l1">
                        <div class="beautiful">
                            <img src="http://static.zino.gr/phoenix/friends.jpg" alt="Πραγματικοί άνθρωποι" />
                        </div>
                        <h3>Απευθύνεστε σε πραγματικούς ανθρώπους</h3>
                        <ul>
                            <li>Δημιουργήστε ζήτηση προβάλλοντας διαφήμιση σε 
                                χρήστες που ενδιαφέρονται.</li>
                            <li>Ορίστε ηλικία, φύλο και περιοχή της Ελλάδας που θέλετε να 
                                προβληθείτε.</li>
                            <li>Απευθυνθείτε επαναλαμβανόμενα σε πιστό κοινό. <sup>+</sup></li>
                        </ul>
                    </li>
                    <li class="l3">
                        <div class="beautiful">
                            <img src="http://static.zino.gr/phoenix/gear.jpg" alt="Αλλάξτε τις" />
                        </div>
                        <h3>Βελτιστοποιήστε τις διαφημίσεις σας</h3>
                        <ul>
                            <li>Παρακολουθήστε live στατιστικά όπως αριθμός προβολών.</li>
                            <li>Μάθετε τι ποσοστά κάνουν κλικ ανάλογα με συγκεκριμένα
                                δημογραφικά στοιχεία.</li>
                            <li>Επεξεργαστείτε την διαφήμισή σας όσες φορές θέλετε.</li>
                        </ul>
                    </li>
                    <li class="l2">
                        <div class="beautiful">
                            <img src="http://static.zino.gr/phoenix/pencil.jpg" alt="Η δική σας διαφήμιση" />
                        </div>
                        <h3>Δημιουργήστε την διαφήμισή σας</h3>
                        <ul>
                            <li>Φτιάξτε εύκολα διαφημίσεις κειμένου ή εικόνων.</li>
                            <li>Επιλέξτε το budget που θέλετε να διαθέσετε.</li>
                            <li>Πληρώστε με πιστωτική κάρτα, PayPal, ή με κατάθεση σε τραπεζικό 
                                λογαριασμό.</li>
                        </ul>
                    </li>
                </ol>
                <div class="eof"></div>
                <a href="" class="start">Δημιουργία διαφήμισης</a>
                <ol class="footnotes">
                    <li><sup>+</sup> Τουλάχιστον 40 προβολές ανά επίσκεψη και 80,000 προβολές ημερισίως <cite>[πηγή: <a href="http://www.google.com/analytics/">Google</a>, <a href="http://www.alexa.com">Alexa</a>]</cite></li>
                    <li class="creativecommons">Φωτογραφίες των καλλιτεχνών
                        <a href="http://goodmusicgoodpeople.deviantart.com/">Kim</a>,
                        <a href="http://www.flickr.com/people/nikonvscanon/">David Blaikie</a>, και
                        <a href="http://www.flickr.com/people/tallkev/">Kevin Utting</a>.
                    </li>
            </div><?php
        }
    }
?>
