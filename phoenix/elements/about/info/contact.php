<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->SetTitle( 'Επικοινωνία' );
            
            ?><form>
                <div>
                   <label>Το e-mail σου:</label>
                   <input type="text" name="email" /> 
                </div>
                <div>
                   <label>Επικοινωνώ επειδή:</label>
                   <select name="reason">
                    <option value="support">Έχω τεχνικό πρόβλημα στο Zino</option>
                    <option value="feature">Έχω μία ιδέα για το Zino</option>
                    <option value="abuse">Αναφέρω παραβίαση των Όρων Χρήσης</option>
                    <option value="biz">Θα ήθελα να συνεργαστούμε</option>
                    <option value="press">Είμαι δημοσιογράφος</option>
                    <option value="purge">Θέλω να διαγράψω το λογαριασμό μου</option>
                   </select>
                </div>
            </form><?php
        }
    }
?>
