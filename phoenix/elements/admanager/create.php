<?php
    class ElementAdManagerCreate extends Element {
        public function Render( tInteger $adid ) {
            global $page;
            global $libs;
            
            $libs->Load( 'admanager' );

            $adid = $adid->Get();
            
            $page->AttachInlineScript( 'AdManager.Create.OnLoad();' );
            
            if ( $adid ) {
                $ad = New Ad( $adid );
                if ( !$ad->Exists() ) {
                    return;
                }
                $page->SetTitle( 'Επεξεργασία διαφήμισης' );
            }
            else {
                $page->SetTitle( 'Δημιουργία διαφήμισης' );
            }
            
            ?><div class="buyad">
                <h2 class="ad smaller"><?php
                if ( $adid ) {
                    ?>Επεξεργασία διαφήμισης<?php
                }
                else {
                    ?>Διαφήμιση στο Zino<?php
                }
                ?></h2>
                <div class="create">
                    <form action="do/admanager/new" method="post" enctype="multipart/form-data">
                        <h3><?php
                        if ( $adid ) {
                            ?>Επεξεργαστείτε<?php
                        }
                        else {
                            ?>Σχεδιάστε<?php
                        }
                        ?> τη διαφήμισή σας</h3>
                        <div class="left">
                            <div class="input">
                                <label>Τίτλος:</label>
                                <input type="text" name="title" value="<?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Title );
                                }
                                ?>" />
                            </div>
                            
                            <div class="input">
                                <label>Κείμενο:</label>
                                <textarea name="body"><?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Body );
                                }
                                ?></textarea>
                            </div>
                            
                            <div class="input">
                                <label>Εικόνα: <span>Προαιρετικά. Η εικόνα θα μικρύνει στα 200x85 pixels.</span></label>
                                <input type="file" name="uploadimage" />
                            </div>

                            <div class="input url">
                                <label>Διεύθυνση σελίδας: <span>Προαιρετικά. (π.χ. www.i-selida-sas.gr)</span></label>
                                
                                <span>http://</span>
                                <input type="text" class="url" name="url" value="<?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Url );
                                }
                                ?>" />
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
                                <h4><a href="" onclick="return false"><?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Title );
                                }
                                else {
                                    ?>Παράδειγμα διαφήμισης<?php
                                }
                                ?></a></h4>
                                <a href="" onclick="return false"><?php
                                if ( $adid ) {
                                    Element( 'image/view', $ad->Imageid, $ad->Userid, $ad->Image->Width, $ad->Image->Height, 
                                             IMAGE_FULLVIEW, '', $ad->Title, '', false, 0, 0, 0 );
                                }
                                else {
                                    ?><img src="http://static.zino.gr/phoenix/mockups/college-students-health.jpg" alt="..." /><?php
                                }
                                ?></a>
                                <p><a href="" onclick="return false"><?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Body );
                                }
                                else {
                                    ?>Αυτό είναι ένα παράδειγμα διαφήμισης. Στη θέση αυτή θα εμφανίζετε το κείμενό σας.<?php
                                }
                                ?></a></p>
                            </div>
                        </div>

                        <a href="" class="start" onclick="return false;">Αποθήκευση</a>
                        <?php
                        if ( $adid ) {
                            ?><input type="hidden" value="<?php
                            echo $adid;
                            ?>" name="adid" /><?php
                        }
                        ?>
                        <input type="submit" class="submit" value="Αποθήκευση" />
                    </form>
                </div>
            </div><?php
        }
    }
?>
