<?php
    class ElementAboutInfoTeam extends Element {
        public function Render() {
            static $team = array(
                array(
                    'nickname' => 'dionyziz',
                    'name'    => 'Διονύσης Ζήνδρος',
                    'of'      => 'Διονύση',
                    'byday'   => 'Φοιτητής ΗΜΜΥ, Εθνικό Μετσόβιο Πολυτεχνείο',
                    'bynight' => 'Managing Director, Kamibu',
                    'image'   => 'http://static.zino.gr/phoenix/about/dionyziz.png',
                    'thumbnail' => 'http://images2.zino.gr/media/1/178164/178164_100.jpg',
                    'about'   => array(
                        'O Διονύσης είναι φοιτητής στη σχολή Ηλεκτρολόγων Μηχανικών στο Εθνικό Μετσόβιο Πολυτεχνείο.
                        Ίδρυσε την Kamibu το 2007 και το Zino με την μορφή που έχει σήμερα
                        το καλοκαίρι του 2008 μαζί με τον Χρήστο και τον Αλέξη.',
                        
                        'Στο παρελθόν, έχει εργαστεί στην διεύθυνση της ομάδας πίσω από το BlogCube 
                        και πίσω από το IRC client ανοιχτού λογισμικού Node, όπως επίσης και στην 
                        τεχνική ομάδα που δημιούργησε το deviantART. Σήμερα είναι ο Managing 
                        Director στην Kamibu.',
                        
                        'Λατρεύει το γάλα και την σοκολάτα.'
                    )
                ),
                array(
                    'nickname' => 'izual',
                    'name'    => 'Xρήστος Παππάς',
                    'of'      => 'Chris',
                    'byday'   => 'Φοιτητής ΗΜΜΥ στο Εθνικό Μετσόβιο Πολυτεχνείο',
                    'bynight' => 'Chief Front-end Engineer, Kamibu',
                    'image'   => 'http://static.zino.gr/phoenix/about/izual.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/58/141855/141855_100.jpg',
                    'about'   => array(
                        'Ο  Χρήστος είναι προπτυχιακός φοιτητής Ηλεκτρολόγος Μηχανικός και Μηχανικός Υπολογιστών 
                        στο Εθνικό Μετσόβιο Πολυτεχνείο.',
                        
                        'Ίδρυσε την Kamibu και το Zino μαζί με τον Διονύση και τον Αλέξη και παλιότερα έχει δουλέψει
                        στο BlogCube. Σήμερα εργάζεται στην Kamibu για τον σχεδιασμό της εμφάνισης και της
                        διεπαφής χρήστη του Zino, και αναπτύσσει την συνολική αρχιτεκτονική του front-end μας.
                        Όσοι είχαν την ατυχία να βρεθούν στο αυτοκίνητο με οδηγό τον Χρήστο, κατάλαβαν
                        ότι δεν ήξεραν τίποτα μέχρι στιγμής για το τι θα πει "τελική".',
                        
                        'Λατρεύει να ταξιδεύει, την ταχύτητα και τα γρήγορα αυτοκίνητα.'
                    )
                ),
                array(
                    'nickname' => 'finlandos',
                    'name' => 'Αλέξης Μπρέσας',
                    'of' => 'Αλέξη',
                    'byday' => 'Technical Director, Kamibu',
                    'image' => 'http://static.zino.gr/phoenix/about/abresas.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/658/71860/71860_100.jpg',
                    'about' => array(
                        'Ο Αλέξης ίδρυσε την Kamibu και το Zino με τον Διονύση και τον Χρήστο.',
                        
                        'Στο παρελθόν είχε εργαστεί στην Black Box Studios ως software developer για 
                        την ανάπτυξη του παιχνιδιού Rise of Orion. Σήμερα εργάζεται ως Technical 
                        Director της ομάδας ανάπτυξης. Περνάει τις ώρες του λύνοντας τον κύβο του 
                        Rubik ξανά και ξανά, και απομνημονεύοντας όλα τα ψηφία του <strong>π</strong>.',
                        
                        'Λατρεύει το vim, την πανσέληνο, το περπάτημα, και την Φινλανδία.'
                    )
                ),
                array(
                    'nickname' => 'romeo',
                    'name' => 'Δημήτρης Χαυλίδης',
                    'of' => 'Δημήτρη',
                    'byday' => 'CEO, Black Box Studios',
                    'bynight' => 'Brand Manager, Kamibu',
                    'image' => 'http://static.zino.gr/phoenix/about/romeo.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/800/102278/102278_100.jpg',
                    'about' => array(
                        'Ο Δημήτρης είναι ο ιδρυτής και CEO της Black Box Studios στο Derby της Αγγλίας.
                        Σπούδασε Visual Communication στο University of Derby και Social Psychology 
                        στο Open University του Λονδίνου.',
                        
                        'Σήμερα εργάζεται παράλληλα ως πρόεδρος της Black Box Studios και ως διευθυντής
                        branding στην Kamibu όπου καθορίζει την πολιτική σχετκά με το brand του Zino.
                        Είναι ο σχεδιαστής του σημερινού λογότυπου του Zino. Συχνά αρχίζει να μιλά με
                        ιστορίες για περίεργους μακρινούς κόσμους, μέχρι να σαγηνεύσει όλη την ομάδα
                        μας ή να μας τραβά φωτογραφίες με υπερβολική συχνότητα έτσι ώστε να 
                        σταματήσουμε να μιλάμε για δουλειές.',
                        
                        'Λατρεύει τα ταξίδα, τις πολεμικές τέχνες, να λέει ιστορίες, και την φωτογραφία.'
                    )
                ),
                array(
                    'nickname' => 'sbartsa',
                    'name' => 'Στέφανος Βαρσάνης',
                    'of' => 'Στέφανου',
                    'byday' =>' Φοιτητής, Οικονομικό Πανεπιστήμιο Αθηνών',
                    'bynight' => 'Marketing Director, Kamibu',
                    'image' => 'http://static.zino.gr/phoenix/about/sbartsa.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/10205/180947/180947_100.jpg',
                    'about' => array(
                        'Ο Στέφανος σπουδάζει στο Οικονομικό Πανεπιστήμιο Αθηνών.',
                        
                        'Στο παρελθόν έχει εργαστεί ως Product manager στην Grebooca και στην 
                         SecondHandPixels. Σήμερα είναι Marketing Director στην Kamibu όπου ασχολείται
                         με την προώθηση του Zino, την διοργάνωση events, και την διαφήμιση. Πάντα
                         έχει τρελές και υπέροχες ιδέες.',
                         
                        'Λατρεύει τα παιχνίδια στρατηγικής, το τέννις, και τις οικονομικές αναλύσεις.'
                    )
                ),
                array(
                    'nickname' => 'ted',
                    'name' => 'Θεοδόσης Σουργκούνης',
                    'of' => 'Θεοδόση',
                    'byday' => 'Φοιτητής ΗΜΜΥ, ΑΠΘ',
                    'bynight' => 'Software Engineer, Kamibu',
                    'image'   => 'http://static.zino.gr/phoenix/about/ted.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/3890/140401/140401_100.jpg',
                    'about' => array(
                        'Ο Θεοδόσης σπουδάζει Ηλεκτρολόγος Μηχανικός στο Αριστοτέλειο Πανεπιστήμιο
                        Θεσσαλονίκης.',
                        
                        'Στην Kamibu και στο Zino ασχολείται με την ανάπτυξη τεχνολογιών front-end,
                        και την διεπαφή χρήστη. Ξοδεύει πολύ περισσότερο χρόνο απ\' όσο θα έπρεπε να
                        παρακολουθεί τα animations του να τρέχουν σε υπερβολικά αργή ταχύτητα.',
                        
                        'Λατρεύει την θάλασσα και το camping, την οδήγηση, και τα ταξίδια.'
                    )
                ),
                array(
                    'nickname' => 'petrosagg18',
                    'name' => 'Πέτρος Αγγελάτος',
                    'of' => 'Πέτρου',
                    'byday' => 'Φοιτητής ΗΜΜΥ, Εθνικό Μετσόβιο Πολυτεχνείο',
                    'bynight' => 'Software Engineer, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/5181/176638/176638_100.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/petros.jpg',
                    'about' => array(
                        'Ο Πέτρος σπουδάζει Ηλεκτρολόγος Μηχανικός στο Εθνικό Μετσόβιο Πολυτεχνείο.',
                        
                        'Στην Kamibu και στο Zino ασχολείται με την ανάπτυξη κάθε είδους τεχνολογιών.
                        Όταν πρέπει να δουλέψει, συνήθως προτιμά να κοιμάται.',
                        
                        'Λατρεύει την σοκολάτα, την οδήγηση, τις ωραίες γυναίκες, και όσους κάθονται
                        και διαβάζουν τις σελίδες με πληροφορίες για το Zino.'
                    )
                ),
                array(
                    'nickname' => 'indy',
                    'name' => 'Αριστοτέλης Μικρόπουλος',
                    'of' => 'Άρη',
                    'byday' => 'Φοιτητής ΗΜΜΥ, ΑΠΘ',
                    'bynight' => 'Software Engineer, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/4047/183422/183422_100.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/indy.jpg',
                    'about' => array(
                        'Ο Άρης είναι προπτυχιακός φοιτητής στη σχολή Ηλετρολόγων Μηχανικών του Αριστοτέλειο
                        Πανεπιστήμιο Θεσσαλονίκης.',
                        
                        'Στο παρελθόν έχει εργαστεί ως μηχανικός λογισμικού στο BlogCube. Είναι μέλος
                        της Kamibu από την ίδρυσή της. Δεν χάνει ευκαιρία για clubbing με τους φίλους του.',
                        
                       'Του αρέσει το ανοιχτό λογισμικό και η trance.'
                    )
                ),
                array(
                    'nickname' => 'd3nnn1z',
                    'name' => 'Διονύσης Πανταζόπουλος',
                    'of' => 'Διονύση',
                    'byday' => 'Φοιτητής Ηλεκτρονική, ΤΕΙ Αθηνών',
                    'bynight' => 'Project Leader, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/39/101492/101492_150.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/d3nnn1z.jpg',
                    'about' => array(
                        'Ο Διονύσης σπουδάζει Ηλεκτρονική στο ΤΕΙ Αθηνών.',
                        
                       'Εργάζεται για την Kamibu ως project leader από την Δεκέμβριο του 2007. Πάντα
                       φέρνει την κουβέντα στο μπουζούκι και τελικά καταλήγουμε να διασκεδάζουμε αντί
                       να δουλεύουμε.',
                       
                       'Λατρεύει το μπουζούκι και το καλό φαγητό.'
                   )
                ),
                array(
                    'nickname' => 'rhapsody',
                    'name' => 'Ελένη Ληξουριώτη',
                    'gender' => 'f',
                    'of' => 'Ελένης',
                    'byday' => 'Φοιτήτρια Web-Based Systems, University of Derby',
                    'bynight' => 'Coding Monkey, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/1023/174561/174561_100.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/rhapsody.jpg',
                    'about' => array(
                        'Η Ελένη σπουδάζει Web-Based Systems και Computing Management στο πανεπιστήμιο
                        του Derby της Αγγλίας.',
                        
                        'Εργάζεται στην Kamibu ως software developer. Πιστεύει ότι είναι ψάρι.',
                        
                        'Λατρεύει το vim, το guitar hero, τα ψάρια και τις γάτες.'
                    )
                ),
                array(
                    'nickname' => 'ch-world',
                    'name' => 'Christian Herrmann',
                    'of' => 'Christian',
                    'byday' => 'Systems Administrator, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/5619/129121/129121_100.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/ch-world.jpg',
                    'about' => array(
                        'Ο Christian σύντομα θα ξεκινήσει τις σπουδές του στην πληροφορική.',
                        
                        'Στο παρελθόν είχε εργαστεί στο Node και στο BlogCube. Σήμερα εργάζεται στην
                        Kamibu ως systems administrator. Αυτός φταίει όταν το Zino εμφανίζει περίεργα
                        σφάλματα τύπου Server Error 500.',
                        
                        'Λατρεύει τις συζητήσεις για την πολιτική, τα λειτουργικά συστήματα, και να
                        περνάει χρόνο με την οικογένειά του.'
                    )
                ),
                array(
                    'nickname' => 'chorvus',
                    'name' => 'Άλεξ Τζο',
                    'of' => 'Άλεξ',
                    'byday' => 'Φοιτητής Πληροφορικής',
                    'bynight' => 'Software engineer, Kamibu',
                    'image' => 'http://static.zino.gr/phoenix/about/chorvus.jpg',
                    'thumbnail' => 'http://images2.zino.gr/media/5104/162838/162838_100.jpg',
                    'about' => array(
                        'Ο Αλεξ σπουδάζει πληροφορική στην Θεσσαλονίκη.',
                        
                        'Στην Kamibu εργάζεται για την βελτίωση κάθε είδους τεχνολογίας, 
                        επικεντρωμένος στο να αναπτύσσει λειτουργίες που κάνουν το Zino πιο ανοιχτό.
                        Μοιάζει με εξωγήινο.',
                        
                        'Λατρεύει να ταξιδεύει, να βλέπει anime, και να παίζει βιντεοπαιχνίδια.'
                    )
                ),
                array(
                    'nickname' => 'pagio',
                    'name' => 'Γιώργος Παναγιωτάκος',
                    'of' => 'Γιώργου',
                    'byday' => 'Researcher, Kamibu',
                    'thumbnail' => 'http://images2.zino.gr/media/4005/101270/101270_100.jpg',
                    'image' => 'http://static.zino.gr/phoenix/about/pagio.jpg',
                    'about' => array(
                        'Ο Γιώργος σύντομα θα ξεκινήσει τις σπουδές του στην πληροφορική.',
                        
                        'Στο παρελθόν έχει τιμηθεί με διακρίσεις στον χώρο της αλγοριθμικής και του 
                        προγραμματισμού. Εργάζεται στην Kamibu ως ερευνητής στον τομέα της τεχνητής
                        νοημοσύνης, της υπολογιστικής διαφήμισης και των αλγορίθμων εξόρυξης 
                        δεδομένων. Δεν ξεχνάει ποτέ να μαθηματικοποιήσει κάθε κατάσταση, ιδιαίτερα
                        εκεί που δεν χρειάζεται.',
                        
                        'Λατρεύει το μπάσκετ, το καλό φαγητό, και τα μαθηματικά.'
                    )
                ),
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
                            ενθαρρυντικό σχόλιο — αυτά είναι που μας κάνουν να έχουμε πάθος και αγάπη
                            για την δουλειά μας. Θα χαρούμε πολύ να μας προσθέσεις στους φίλους 
                            <span class="emoticon-smile">.</span>
                        </p>
                    </div><?php
                    foreach ( $team as $member ) {
                        ?><div class="aboutoneperson aboutonepersonslide" id="iam<?php
                        echo $member[ 'nickname' ];
                        ?>">
                        <img src="<?php
                        echo $member[ 'image' ];
                        ?>" alt="<?php
                        echo $member[ 'nickname' ];
                        ?>" />
                        
                        <h3><?php
                        echo $member[ 'name' ];
                        ?></h3>
                        <ul>
                            <li>
                                <strong>Την ημέρα<?php
                                if ( !isset( $member[ 'bynight' ] ) ) {
                                    ?> και την νύχτα<?php
                                }
                                ?>: </strong>
                                <?php
                                echo $member[ 'byday' ];
                                ?>
                            </li><?php
                            if ( isset( $member[ 'bynight' ] ) ) {
                                ?><li>
                                    <strong>Τη νύχτα: </strong>
                                    <?php
                                    echo $member[ 'bynight' ];
                                    ?>
                                </li><?php
                            }
                        ?></ul><?php
                        $i = 0;
                        foreach ( $member[ 'about' ] as $paragraph ) {
                            ++$i;
                            ?><p><?php
                            echo $paragraph;
                            if ( $i == count( $member[ 'about' ] ) ) { // add a link to the last paragraph
                                ?><a href="http://<?php
                                echo $member[ 'nickname' ];
                                ?>.zino.gr/">Πήγαινε στο Zino <?php
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
                </div>
                <div class="eof"></div>
            </div>
                <ul id="aboutpeople"><?php
                    shuffle( $team );
                    foreach ( $team as $member ) {
                        ?><li style="width:<?php
                        echo round( 100 / count( $team ), 2 );
                        ?>%"><a href=""><img src="<?php
                        echo $member[ 'thumbnail' ];
                        ?>" alt="<?php
                        echo $member[ 'nickname' ];
                        ?>" /></a></li><?php
                    }
                    ?>
                </ul>
                <div class="eof"></div><?php
        }
    }
?>
