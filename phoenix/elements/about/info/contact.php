<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            global $user;
            
            $page->SetTitle( 'Επικοινωνία' );
            
            ?><form id="aboutcontact">
                <div><?php
                    if ( $user->Exists() ) {
                        ?><label>Το ψευδώνυμό σου:</label>
                        <strong><?php
                        echo $user->Name;
                        ?></strong><?php
                    }
                    else {
                       ?><label>Το e-mail σου:</label>
                       <input type="text" name="email" value="" /><?php
                    }
                    ?>
                </div>
                <p>Όλα τα μηνύματα που λαμβάνουμε διαβάζονται προσεκτικά και με επιμέλεια από κάποιον της ομάδας ανάπτυξης του Zino.
                   Θα προσπαθήσουμε να σου απαντήσουμε στο μήνυμά σου, αλλά αυτό δυστυχώς δεν είναι πάντα δυνατό λόγω του πλήθους των
                   μηνυμάτων που παίρνουμε.
               </p>
                <div>
                   <label>Επικοινωνώ επειδή:</label>
                   <select name="reason" id="reason">
                    <option></option>
                    <option value="support">Έχω τεχνικό πρόβλημα στο Zino</option>
                    <option value="feature">Έχω μία ιδέα για το Zino</option>
                    <option value="abuse">Αναφέρω παραβίαση των Όρων Χρήσης</option>
                    <option value="biz">Θα ήθελα να συνεργαστούμε</option>
                    <option value="press">Είμαι δημοσιογράφος</option>
                    <option value="purge">Θέλω να διαγράψω το λογαριασμό μου</option>
                   </select>
                </div>
                <div id="contact_support" style="display:none">
                    <div>
                        <label>Σε ποια σελίδα συνέβη το πρόβλημα; (διεύθυνση)</label>
                        <input type="text" name="bugurl" style="width:100%" />
                    </div>
                    <div>
                        <label>Τι ακριβώς συνέβη; Περιέγραψε με όσες λεπτομέρειες μπορείς.</label>
                        <textarea cols="70" rows="10" name="bugdescription" style="width:100%"></textarea>
                    </div>
                    <div>
                        <label>Τι συσκευή χρησιμοποιείς;</label>
                        <select name="bugdevice">
                         <option></option>
                         <option>Υπολογιστή Desktop</option>
                         <option>Υπολογιστή Laptop</option>
                         <option>Palmtop</option>
                         <option>Κινητό τηλέφωνο</option>
                         <option>Παιχνιδομηχανή</option>
                        </select>
                    </div>
                    <div id="computeros">
                        <label>Τι λειτουργικό σύστημα χρησιμοποιείς;</label>
                        <select name="bugos">
                         <option></option>
                         <option value="windows">Windows</option>
                         <option value="linux">Linux</option>
                         <option value="mac">Mac OS</option>
                         <option value="bsd">BSD</option>
                         <option value="other">Κάποιο άλλο</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="winversion" style="display:none">
                        <label>Ποια έκδοση των Windows χρησιμοποιείς;</label>
                        <select name="bugwinversion">
                         <option></option>
                         <option value="98">Windows 98</option>
                         <option value="me">Windows Millenium</option>
                         <option value="2000">Windows 2000</option>
                         <option value="xp">Windows XP</option>
                         <option value="vista">Windows Vista</option>
                         <option value="7">Windows 7</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="linuxdistro" style="display:none">
                        <label>Ποια διανομή του Linux χρησιμοποιείς;</label>
                        <select name="buglinuxdistro">
                         <option></option>
                         <option value="ubuntu">Ubuntu</option>
                         <option value="opensuse">OpenSUSE</option>
                         <option value="fedora">Fedora</option>
                         <option value="debian">Debian</option>
                         <option value="mandriva">Mandriva</option>
                         <option value="linuxmint">LinuxMint</option>
                         <option value="pclinuxos">PCLinuxOS</option>
                         <option value="slackware">Slackware</option>
                         <option value="gentoo">Gentoo</option>
                         <option value="centos">CentOS</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div>
                        <label>Ποιο browser χρησιμοποιείς;</label>
                        <select name="bugbrowser">
                         <option></option>
                         <option value="ie">Internet Explorer</option>
                         <option value="ff">Mozilla Firefox</option>
                         <option value="chrome">Google Chrome</option>
                         <option value="opera">Opera</option>
                         <option value="safari">Safari</option>
                         <option value="other">Κάποιο άλλο</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="ieversion" style="display:none">
                        <label>Ποια έκδοση του Internet Explorer χρησιμοποιείς;</label>
                        <select name="bugieversion">
                         <option></option>
                         <option value="6">Internet Explorer 6</option>
                         <option value="7">Internet Explorer 7</option>
                         <option value="8">Internet Explorer 8</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="ffversion" style="display:none">
                        <label>Ποια έκδοση του Mozilla Firefox χρησιμοποιείς;</label>
                        <select name="bugffversion">
                         <option></option>
                         <option value="1">Firefox 1</option>
                         <option value="1.5">Firefox 1.5</option>
                         <option value="2">Firefox 2</option>
                         <option value="3">Firefox 3</option>
                         <option value="3.5">Firefox 3.5</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="operaversion" style="display:none">
                        <label>Ποια έκδοση του Opera χρησιμοποιείς;</label>
                        <select name="bugoperaversion">
                         <option></option>
                         <option value="8.5">Opera 8.5</option>
                         <option value="9.0">Opera 9.0</option>
                         <option value="9.1">Opera 9.1</option>
                         <option value="9.2">Opera 9.2</option>
                         <option value="9.5">Opera 9.5</option>
                         <option value="9.6">Opera 9.6</option>
                         <option value="10">Opera 10</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="chromeversion" style="display:none">
                        <label>Ποια έκδοση του Google Chrome χρησιμοποιείς;</label>
                        <select name="bugchromeversion">
                         <option></option>
                         <option value="1.0">Chrome 1.0</option>
                         <option value="2.0">Chrome 2.0</option>
                         <option value="3.0">Chrome 3.0</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                    <div id="safariversion" style="display:none">
                        <label>Ποια έκδοση του Safari χρησιμοποιείς;</label>
                        <select name="bugsafariversion">
                         <option></option>
                         <option value="2.0">Safari 2.0</option>
                         <option value="3.0">Safari 3.0</option>
                         <option value="3.1">Safari 3.1</option>
                         <option value="4.0">Safari 4.0</option>
                         <option value="other">Κάποια άλλη</option>
                         <option value="dontknow">Δεν ξέρω</option>
                        </select>
                    </div>
                </div>
                <div id="contact_feature" style="display:none">
                    <p>Ευχαριστούμε που θέλεις να μοιραστείς την ιδέα σου μαζί μας!</p>
                    <div>
                        <label>Τι είναι αυτό που θα σου άρεσε να γίνει στο Zino?</label>
                        <select name="featurechoice">
                            <option></option>
                            <option value="customization">Χρωματικοί συνδιασμοί στο προφίλ μου</option>
                            <option value="sms">Ενημέρωση μέσω SMS</option>
                            <option value="music">Μουσική στο προφίλ μου</option>
                            <option value="purge">Δυνατότητα διαγραφής προφίλ</option>
                            <option value="rename">Δυνατότητα αλλαγής ονόματος</option>
                            <option value="newidea">Κάποια άλλη ιδέα (προσδιόρισε)</option>
                        </select>
                    </div>
                    <div>
                        <label>Γράψε μας την ιδέα σου που θα ήθελες να δεις στο Zino:</label>
                        <textarea cols="70" rows="10" name="featuredescription" style="width:100%"></textarea>
                    </div>
                </div>
                <div id="contact_abuse" style="display:none">
                    <p>
                        Σ' ευχαριστούμε για το ενδιαφέρον σου να αναφέρεις αυτό το πρόβλημα.<br />
                        Τα στοιχεία του ατόμου που αναφέρει την παραβίαση των όρων χρήσης παραμένουν εμπιστευτικά.<br />
                        Θα εξετάσουμε κάθε αναφορά παραβίασης όρων. Η αναφορά παραβίασης όρων χρήσης δεν σημαίνει ότι θα
                        υπάρξει αυτόματα και δράση από πλευράς μας, αν δεν κρίνουμε ότι είναι απαραίτητη.
                    </p>
                    <div>
                        <label>Τι είδους παραβίαση των όρων χρήσης έγινε;</label>
                        <select name="abusetype">
                            <option></option>
                            <option name="porn">Πορνογραφικό υλικό</option>
                            <option name="imitation">Χρήση φωτογραφίας μου χωρίς να το θέλω</option>
                            <option name="fake">Fake λογαριασμός</option>
                            <option name="spam">Spam</option>
                            <option name="racism">Ρατσιστικό περιεχόμενο</option>
                            <option name="copyright">Παραβίαση πνευματικών δικαιωμάτων</option>
                            <option name="drugs">Απαγορευμένες ουσίες</option>
                        </select>
                    </div>
                    <div>
                        <label>Ποιο είναι το ψευδώνυμο του χρήστη που το έκανε;</label>
                        <input type="text" name="abuseusername" />
                    </div>
                    <div>
                        <label>Τι ακριβώς συνέβη;</label>
                        <textarea cols="70" rows="10" name="abusedescription" style="width:100%"></textarea>
                    </div>
                </div>
                <div id="contact_press" style="display:none">
                    <p>
                        Ευχαριστούμε για το ενδιαφέρον σας για την δημοσιογραφική κάλυψη του Zino.
                        Συμπλήρωσε τα παρακάτω στοιχεία, και θα έρθουμε σε επαφή μαζί σου.
                    </p>
                    <div>
                        <label>Όνομα και επώνυμο:</label>
                        <input type="text" name="pressfullname" />
                    </div>
                    <div>
                        <label>Είδος μέσου:</label>
                        <select name="presstype">
                            <option></option>
                            <option>Τηλεόραση</option>
                            <option>Ραδιόφωνο</option>
                            <option>Τύπος</option>
                            <option>Blog / Ιστοσελίδα</option>
                            <option>Άλλο</option>
                        </select>
                    </div>
                    <div>
                        <label>Επωνυμία:</label>
                        <input type="text" name="presscompany" />
                    </div>
                    <div>
                        <label>Τηλέφωνο:</label>
                        <input type="text" name="pressphone" />
                    </div>
                    <div>
                        <label>Λίγα λόγια για το ποιοι είστε και τι ενδιαφέρεστε να κάνουμε μαζί:</label>
                        <textarea cols="70" rows="10" name="pressdescription" style="width:100%"></textarea>
                    </div>
                </div>
                <div id="contact_biz" style="display:none">
                    <div>
                        <label>Όνομα και επώνυμο:</label>
                        <input type="text" name="bizfullname" />
                    </div>
                    <div>
                        <label>Εταιρία:</label>
                        <input type="text" name="bizcompany" />
                    </div>
                    <div>
                        <label>Θέση στην εταιρία:</label>
                        <input type="text" name="bizposition" />
                    </div>
                    <div>
                        <label>Τηλέφωνο:</label>
                        <input type="text" name="bizphone" />
                    </div>
                    <div>
                        <label>Πώς πιστεύετε ότι θα μπορούσαμε να συνεργαστούμε;</label>
                        <textarea cols="70" rows="10" name="bizdescription" style="width:100%"></textarea>
                    </div>
                </div>
                <div id="contact_purge" style="display:none">
                    <p>
                        Λυπούμαστε πολύ, αλλά δυστυχώς δεν είναι δυνατή η διαγραφή του προφίλ στο Zino. Μπορείς,
                        όμως, αν το επιθυμείς, να διαγράψεις το περιεχόμενο που έχεις
                        αναρτήσει από το λογαριασμό σου συμπεριλαμβανομένων και των φωτογραφιών σου και να μην ξαναεισέλθεις
                        χρησιμοποιώντας τον.
                    </p>

                    <p>
                        Η διαγραφή λογαριασμού είναι δυστυχώς τεχνικά αδύνατη λόγω αντιγράφων
                        ασφαλείας και πολλών ειδών περιεχομένου που συνδέονται με τον
                        λογαριασμό. Η ομάδα μας εργάζεται σκληρά για την δημιουργία της
                        δυνατότητας απενεργοποίησης αλλά και πλήρους διαγραφής λογαριασμών,
                        κάτι το οποίο ελπίζουμε να είναι διαθέσιμο το συντομότερο μέσα στις
                        επόμενες εβδομάδες. Για περισσότερες λεπτομέρειες μπορείς να
                        διαβάσεις και <a href="tos">τους όρους χρήσης</a>.
                    </p>

                    <p>
                        Λυπούμαστε που δεν επιθυμείς να παραμείνεις μέλος της παρέας του Zino.
                        Θα μας ενδιέφερε πολύ να μοιραστείς μαζί μας τους λόγους που θέλεις να
                        αποχωρήσεις, και θα προσπαθήσουμε να κάνουμε ό,τι καλύτερο για να διορθωθούμε.
                        Μην διστάσεις να μας προτείνεις κάποια ιδέα, ή να αναφέρεις οποιοδήποτε τεχνικό
                        πρόβλημα αντιμετώπισες.
                    </p>
                </div>
            </form><?php
        }
    }
?>
