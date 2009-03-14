<?php
    class ElementAdManagerCreate extends Element {
        public function Render() {
            global $page;
            
            $page->AttachInlineScript( 'AdManager.Create.OnLoad();' );
            
            ?><div class="buyad">
                <h2 class="ad smaller">Διαφήμιση στο Zino</h2>
                <div class="create">
                    <form action="do/admanager/new" method="post">
                        <h3>Σχεδιάστε τη διαφήμισή σας</h3>
                        <div class="left">
                            <div class="input">
                                <label>Τίτλος:</label>
                                <input type="text" name="title" />
                            </div>
                            
                            <div class="input">
                                <label>Κείμενο:</label>
                                <textarea name="body"></textarea>
                            </div>
                            
                            <div class="input">
                                <label>Εικόνα: <span>Προαιρετικά. Η εικόνα θα μικρύνει στα 200x85 pixels.</span></label>
                                <input type="file" name="uploadimage" />
                            </div>

                            <div class="input url">
                                <label>Διεύθυνση σελίδας: <span>Προαιρετικά. (π.χ. www.i-selida-sas.gr)</span></label>
                                
                                <span>http://</span>
                                <input type="text" class="url" name="url" />
                            </div>
                        </div>
                        <div class="right">
                            <p>Οι διαφημίσεις ελέγχονται για να σιγουρευτούμε ότι 
                            ικανοποιούν τις προϋποθέσεις μας. Σας συνιστούμε να 
                            διαβάσετε τον <a href="">σύντομο οδηγό για διαφημιζόμενους</a>.</p>
                        </div>
                        <div class="eof"></div>
                        <h3 class="preview">Προεπισκόπηση</h3>
                        <div class="ads adspreview">
                            <div class="ad">
                                <h4><a href="" onclick="return false">Φοιτητική ταυτότητα ISIC</a></h4>
                                <a href="" onclick="return false"><img src="http://static.zino.gr/phoenix/mockups/college-students-health.jpg" alt="..." /></a>
                                <p><a href="" onclick="return false">Διεθνής Φοιτητική Ταυτότητα. Μοναδικά προνόμια και εκπτώσεις. Φέτος η ISIC κάνει έκπτωση και στον εαυτό της!
                                   Βγάλε ISIC με 9 ευρώ!</a></p>
                            </div>
                        </div>

                        <a href="" class="start" onclick="return false;">Αποθήκευση</a>
                        
                        <input type="submit" class="submit" value="Αποθήκευση" />
                    </form>
                </div>
            </div><?php
        }
    }
?>
