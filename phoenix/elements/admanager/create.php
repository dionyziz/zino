<?php
    class ElementAdManagerCreate extends Element {
        public function Render( tInteger $adid ) {
            global $page;
            global $libs;
            global $user;
            
            $libs->Load( 'admanager' );

            $adid = $adid->Get();
            
            $page->AttachInlineScript( 'AdManager.Create.OnLoad();' );
            
            if ( $adid ) {
                $ad = New Ad( $adid );
                if ( !$ad->Exists() ) {
                    return Redirect( '?p=ads&error=notexist' );
                }
                if ( !$user->Exists() || $ad->Userid != $user->Id ) {
                    return Redirect( '?p=ads&error=notowner' );
                }
                if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
                    return Redirect( '?p=ads&error=nopermission' );
                }
                $page->SetTitle( 'Επεξεργασία διαφήμισης' );
            }
            else {
                $page->SetTitle( 'Δημιουργία διαφήμισης' );
                if ( !$user->HasPermission( PERMISSION_AD_CREATE ) ) {
                    return Redirect( '?p=join&returnto=' . urlencode( '?p=admanager' ) );
                }
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
                                <label for="adtitle">Τίτλος:</label>
                                <input type="text" name="title" id="adtitle" value="<?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Title );
                                }
                                ?>" />
                            </div>
                            
                            <div class="input">
                                <label for="adbody">Κείμενο:</label>
                                <textarea name="body" id="adbody"><?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Body );
                                }
                                ?></textarea>
                            </div>
                            
                            <div class="input">
                                <?php
                                if ( $adid && $ad->Imageid ) {
                                    ?><label>Εικόνα: </label><?php
                                    Element( 'image/view', $ad->Imageid, $ad->Userid, $ad->Image->Width, $ad->Image->Height, 
                                             IMAGE_FULLVIEW, '', $ad->Title, '', false, 0, 0, 0 );
                                    ?><label for="aduploadimage">Αλλαγή εικόνας: <span>Προαιρετικά. Η εικόνα θα μικρύνει στα 200x85 pixels.</span></label><?php
                                }
                                else {
                                    ?><label for="aduploadimage">Εικόνα: <span>Προαιρετικά. Η εικόνα θα μικρύνει στα 200x85 pixels.</span></label><?php
                                }
                                ?>
                                <input type="file" name="uploadimage" id="aduploadimage" />
                            </div>

                            <div class="input url">
                                <label for="adurl">Διεύθυνση σελίδας: <span>Προαιρετικά. (π.χ. www.i-selida-sas.gr)</span></label>
                                
                                <span>http://</span>
                                <input type="text" class="url" name="url" id="adurl" value="<?php
                                if ( $adid ) {
                                    echo htmlspecialchars( $ad->Url );
                                }
                                ?>" />
                            </div>
                        </div>
                        <div class="right">
                            <p>Οι διαφημίσεις ελέγχονται για να σιγουρευτούμε ότι 
                            ικανοποιούν τις προϋποθέσεις μας. Σας συνιστούμε να 
                            διαβάσετε τον <a href="?p=admanager/tips" onclick="window.open('?p=admanager/tips');return false;" target="_blank">σύντομο οδηγό για διαφημιζόμενους</a>.</p>
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
                        
                        <?php
                        if ( $adid ) {
                            ?>
                            <input type="hidden" value="<?php
                            echo $adid;
                            ?>" name="adid" />
                            <div class="buttons">
                                <?php
                        }
                        ?>
                        <input type="submit" class="submit start left" value="Αποθήκευση" />
                        <?php
                        if ( $adid ) {
                            ?>
                            <a href="?p=admanager/list" class="start right">Ακύρωση</a>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="eof"></div>
                    </form>
                </div>
            </div><?php
        }
    }
?>
