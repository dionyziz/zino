<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            global $user;
            
            $page->SetTitle( 'Επικοινωνία' );
            
            ?><form>
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
                   <select name="reason">
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
                        <input type="text" name="url" style="width:100%" />
                    </div>
                    <div>
                        <label>Τι ακριβώς συνέβη; Περιέγραψε με όσες λεπτομέρειες μπορείς.</label>
                        <textarea cols="70" rows="10" name="description" style="width:100%"></textarea>
                    </div>
                    <div>
                        <label>Τι συσκευή χρησιμοποιείς;</label>
                        <select name="device">
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
                        <select name="os">
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
                        <select name="winversion">
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
                        <select name="linuxdistro">
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
                        <select name="browser">
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
                        <select name="ieversion">
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
                        <select name="ffversion">
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
                        <select name="operaversion">
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
                        <select name="chromeversion">
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
                        <select name="safariversion">
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
                <div id="contact_feature">
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
            </form><?php
        }
    }
?>
