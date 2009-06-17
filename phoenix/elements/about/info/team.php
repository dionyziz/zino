<?php
    class ElementAboutInfoTeam extends Element {
        public function Render() {
            static $team = array(
                'dionyziz' => array(
                    'name'    => 'Διονύσης Ζήνδρος',
                    'gender'  => 'm',
                    'of'      => 'Διονύση',
                    'byday'   => 'Φοιτητής ΗΜΜΥ στο Εθνικό Μετσόβιο Πολυτεχνείο',
                    'bynight' => 'Managing Director στην Kamibu και το Zino',
                    'image'   => 'http://static.zino.gr/phoenix/about/dionyziz.png',
                    'thumbnail' => 'http://images2.zino.gr/media/1/178164/178164_100.jpg',
                    'about'   => array(
                        'O Διονύσης είναι φοιτητής στη σχολή Ηλεκτρολόγων Μηχανικών στο Εθνικό Μετσόβιο Πολυτεχνείο.
                        Ίδρυσε την Kamibu το 2007 και το Zino με την μορφή που έχει σήμερα
                        το καλοκαίρι του 2008 μαζί με τον Χρήστο και τον Αλέξη.',
                        
                        'Στο παρελθόν, 
                        έχει εργαστεί στην διεύθυνση της ομάδας
                        πίσω από το BlogCube και πίσω από το IRC client ανοιχτού λογισμικού Node,
                        όπως επίσης και στην τεχνική ομάδα που δημιούργησε το deviantART.',
                        
                        'Λατρεύει το γάλα και την σοκολάτα.'
                    )
                ),
                'izual' => array(
                    'name'    => 'Xρήστος Παππάς',
                    'gender'  => 'm',
                    'of'      => 'Χρήστου',
                    'byday'   => 'Φοιτητής ΗΜΜΥ στο Εθνικό Μετσόβιο Πολυτεχνείο',
                    'bynight' => 'Chief Front-end Engineer στο Zino',
                    'image'   => 'http://static.zino.gr/phoenix/about/izual.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/58/141855/141855_100.jpg',
                    'about'   => array(
                        'Ο  Χρήστος είναι προπτυχιακός φοιτητής Ηλεκτρολόγος Μηχανικός και Μηχανικός Υπολογιστών 
                        στο Εθνικό Μετσόβιο Πολυτεχνείο.
                        Εργάζεται στην ομάδα ανάπτυξης του Zino από την ίδρυσή του και παλιότερα έχει δουλέψει
                        στο BlogCube.',
                        'Λατρεύει να ταξιδεύει, την ταχύτητα και τα γρήγορα αυτοκίνητα.'
                    )
                )
            );
            ?><h2 class="sweet">Ποιοι εργάζονται πίσω απ' το Zino?</h2>
            
            <div class="info">
                <div id="aboutperson">
                    <div class="aboutoneperson" id="iamnoone">
                        <img src="http://static.zino.gr/phoenix/about/team.jpg" alt="noone" />
                        <h3>Η ομάδα του Zino</h3>
                        <p>
                            Είμαστε μία καταρτισμένη ομάδα σε όλους τους τομείς
                            από θέματα τεχνικά μέχρι το business, το marketing, και το design. Κάθε
                            μέλος της ομάδας μας είναι μία πολύπλευρη προσωπικότητα με μοναδικά 
                            ενδιαφέροντα και ασχολίες πέρα από την δουλειά του στο Zino.
                        </p>
                        <p>
                            Για να μας γνωρίσεις διάλεξε μία από τις φάτσες που σου αρέσει. Μην 
                            διστάσεις να επισκευθείς το προφίλ του καθενός και να μας αφήσεις ένα
                            ενθαρρυντικό σχόλιο — αυτά είναι που μας κάνουμε να έχουμε πάθος και αγάπη
                            για την δουλειά μας. Θα χαρούμε πολύ να μας προσθέσεις στους φίλους 
                            <span class="emoticon-smile">.</span>
                        </p>
                    </div><?php
                    foreach ( $team as $nickname => $member ) {
                        ?><div class="aboutoneperosn aboutonepersonslide" id="iam<?php
                        echo $nickname;
                        ?>">
                        <img src="<?php
                        echo $member[ 'image' ];
                        ?>" alt="<?php
                        echo $nickname;
                        ?>" />
                        
                        <h3><?php
                        echo $member[ 'name' ];
                        ?></h3>
                        <ul>
                            <li>
                                <strong>Την ημέρα: </strong>
                                <?php
                                echo $member[ 'byday' ];
                                ?>
                            </li>
                            <li>
                                <strong>Τη νύχτα: </strong>
                                <?php
                                echo $member[ 'bynight' ];
                                ?>
                            </li>
                        </ul><?php
                        foreach ( $member[ 'about' ] as $paragraph ) {
                            ?><p><?php
                            echo $paragraph;
                            if ( $i == count( $member[ 'about' ] ) ) { // add a link to the last paragraph
                                ?><a href="http://<?php
                                echo $nickname;
                                ?>">Πήγαινε στο Zino <?php
                                switch ( $member[ 'gender' ] ) {
                                    case 'f':
                                        ?>της <?php
                                        break;
                                    default:
                                        ?>του <?php
                                }
                                echo $member[ 'of' ];
                                ?></a><?php
                            }
                            ?></p><?php
                        }
                        ?></div><?php
                    }
                    ?>
                    <!-- <div class="aboutoneperson aboutonepersonslide" id="iamdionyziz">
                        <img src="http://static.zino.gr/phoenix/about/dionyziz.png" alt="dionyziz" />
                        <h3>Διονύσης Ζήνδρος</h3>
                        <ul>
                            <li>
                                <strong>Την ημέρα: </strong>
                                Φοιτητής ΗΜΜΥ στο Εθνικό Μετσόβιο Πολυτεχνείο
                            </li>
                            <li style="padding-top:5px">
                                <strong>Την νύχτα: </strong>
                                Managing Director στην Kamibu και το Zino
                            </li>
                        </ul>
                        <p>
                            O Διονύσης είναι φοιτητής στη σχολή Ηλεκτρολόγων Μηχανικών στο Εθνικό Μετσόβιο Πολυτεχνείο.
                            Ίδρυσε την Kamibu το 2007 και το Zino με την μορφή που έχει σήμερα
                            το καλοκαίρι του 2008 μαζί με τον Χρήστο και τον Αλέξη. 
                        </p>
                        <p>
                            Στο παρελθόν, 
                            έχει εργαστεί στην διεύθυνση της ομάδας
                            πίσω από το BlogCube και πίσω από το IRC client ανοιχτού λογισμικού Node,
                            όπως επίσης και στην τεχνική ομάδα που δημιούργησε το deviantART.
                        </p>
                        <p>
                            Λατρεύει το γάλα και την σοκολάτα.
                            
                            <a href="http://dionyziz.zino.gr/">Πήγαινε στο Zino του Διονύση &raquo;</a>
                        </p>
                    </div>
                    <div class="aboutoneperson aboutonepersonslide" id="iamizual">
                        <img src="http://static.zino.gr/phoenix/about/izual.jpg" alt="izual" />
                        <h3>Xρήστος Παππάς</h3>
                        <ul>
                            <li>
                                <strong>Την ημέρα: </strong>
                                Φοιτητής ΗΜΜΥ στο Εθνικό Μετσόβιο Πολυτεχνείο
                            </li>
                            <li style="padding-top:5px">
                                <strong>Την νύχτα: </strong>
                                Chief Front-end Engineer στο Zino
                            </li>
                        </ul>
                        <p>
                            Ο Χρήστος είναι προπτυχιακός φοιτητής Ηλεκτρολόγος Μηχανικός και Μηχανικός Υπολογιστών 
                            στο Εθνικό Μετσόβιο Πολυτεχνείο.
                            Εργάζεται στην ομάδα ανάπτυξης του Zino από την ίδρυσή του και παλιότερα έχει δουλέψει
                            στο BlogCube.
                        </p>
                        <p>
                            Λατρεύει να ταξιδεύει, την ταχύτητα και τα γρήγορα αυτοκίνητα.
                            
                            <a href="http://izual.zino.gr/">Πήγαινε στο Zino του Χρήστου &raquo;</a>
                        </p>
                    </div> -->
                </div>
                <div class="eof"></div>
            </div>
                <ul id="aboutpeople">
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/1/178164/178164_100.jpg" alt="dionyziz" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/58/141855/141855_100.jpg" alt="izual" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/658/71860/71860_100.jpg" alt="abresas" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/800/102278/102278_100.jpg" alt="romeo" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/10205/180947/180947_100.jpg" alt="steve" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/3890/140401/140401_100.jpg" alt="ted" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/5181/176638/176638_100.jpg" alt="petros" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/5104/162838/162838_100.jpg" alt="chorvus" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/1023/174561/174561_100.jpg" alt="rhapsody" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/4005/101270/101270_100.jpg" alt="pagio" /></a>
                    </li>
                    <li>
                        <a href=""><img src="http://images2.zino.gr/media/5619/129121/129121_100.jpg" alt="ch-world" /></a>
                    </li>
                </ul>
                <div class="eof"></div><?php
        }
    }
?>
