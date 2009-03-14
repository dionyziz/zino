<?php
    class ElementAdManagerCreate extends Element {
        public function Render() {
            ?><div class="buyad">
                <h2 class="ad">Διαφήμιση στο Zino</h2>
                <div class="create">
                    <h3>Σχεδιάστε τη διαφήμισή σας</h3>
                    <div class="left">
                        <div class="input">
                            <label>Τίτλος:</label>
                            <input type="text" />
                        </div>
                        
                        <div class="input">
                            <label>Κείμενο:</label>
                            <textarea></textarea>
                        </div>
                        
                        <div class="input">
                            <label>Εικόνα: <span>Προαιρετικά. Η εικόνα θα μικρύνει στα 200x85 pixels.</span></label>
                            <input type="file" />
                        </div>

                        <div class="input url">
                            <label>Διεύθυνση σελίδας: <span>Προαιρετικά. (π.χ. www.i-selida-sas.gr)</span></label>
                            
                            <span>http://</span>
                            <input type="text" class="url" />
                        </div>
                    </div>
                    <div class="right">
                        <p>Οι διαφημίσεις ελέγχονται για να σιγουρευτούμε ότι 
                        ικανοποιούν τις προϋποθέσεις μας. Σας συνιστούμε να 
                        διαβάσετε τον <a href="">σύντομο οδηγό για διαφημιζόμενους</a>.</p>
                    </div>
                    <div class="eof"></div>
                    <h3 style="margin-bottom:0">Προεπισκόπηση</h3>
                    <div class="ads" style="font-size: 90%;background-color:white;border-bottom:1px solid transparent;padding: 10px 0 10px 0;margin:0 10px 0 10px">
                        <div class="ad" style="width:200px;border:1px solid #ddd;padding: 5px;margin: auto;">
                            <h4 style="margin: 5px 0 5px 0"><a href="" style="color: #357;">Φοιτητική ταυτότητα ISIC</a></h4>
                            <a href=""><img src="http://static.zino.gr/phoenix/mockups/college-students-health.jpg" alt="..." style="display: block; margin: auto" /></a>
                            <p><a href="" style="color: black">Διεθνής Φοιτητική Ταυτότητα. Μοναδικά προνόμια και εκπτώσεις. Φέτος η ISIC κάνει έκπτωση και στον εαυτό της!
                               Βγάλε ISIC με 9 ευρώ!</a></p>
                        </div>
                    </div>

                    <a href="" class="start">Αποθήκευση</a>
                </div>
            </div><?php
        }
    }
?>
