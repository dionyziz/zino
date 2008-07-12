<?php
	class ElementUserSettingsSettings extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            
            ?><div><label><a class="changepwdlink" href="">Αλλαγή κωδικού πρόσβασης</a></label></div>
            <span class="notifyme">Να λαμβάνω ειδοποιήσεις</span>
            <div class="setting">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Μέσω e-mail</th>
                            <th>Μέσα στο site</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Σχόλια στο προφίλ μου:</th>
                            <td><input id="emailprofilecomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailprofilecomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyprofilecomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyprofilecomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στις εικόνες μου:</th>
                            <td><input id="emailphotocomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailphotocomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyphotocomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyphotocomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στις δημοσκοπήσεις μου:</th>
                            <td><input id="emailpollcomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailpollcomment == 'yes' ) {
                                ?>checked="true"<?php
                            } 
                            ?>/></td>
                            <td><input id="notifypollcomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifypollcomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στα ημερολόγιά μου:</th>
                            <td><input id="emailjournalcomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailjournalcomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyjournalcomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyjournalcomment == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>						
                        </tr>
                        <tr>
                            <th>Απαντήσεις στα σχόλιά μου:</th>
                            <td><input id="emailreply" type="checkbox" <?php
                            if ( $user->Preferences->Emailreply == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyreply" type="checkbox" <?php
                            if ( $user->Preferences->Notifyreply == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Νέοι φίλοι:</th>
                            <td><input id="emailfriendaddition" type="checkbox" <?php
                            if ( $user->Preferences->Emailfriendaddition == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyfriendaddition" type="checkbox" <?php
                            if ( $user->Preferences->Notifyfriendaddition == 'yes' ) {
                                ?>checked="true"<?php
                            }
                            ?>/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="changepwd">
                <h3>Αλλαγή κωδικού πρόσβασης</h3>
                <div class="oldpassword">
                    <span>Κωδικός πρόσβασης:</span>
                    <div>
                        <input type="password" />
                        <div class="wrongpwd">
                        <span>
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Ο κωδικός πρόσβασης δεν είναι σωστός
                        </span>
                        </div>
                    </div>
                </div>
                <div class="newpassword">
                    <span>Νέος κωδικός πρόσβασης:</span>
                    <div>
                        <input type="password" />
                        <div class="shortpwd">
                        <span>
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!
                        </span>
                        </div>
                    </div>
                </div>
                <div class="renewpassword">
                    <span>Επιβεβαίωση νέου κωδικού:</span>
                    <div>
                        <input type="password" />
                        <div class="wrongrepwd">
                        <span><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!
                        </span>
                        </div>
                    </div>
                </div>
                <div class="save">
                    <a href="" class="button save">Αποθήκευση</a>
                    <a href="" class="button cancel">Ακύρωση</a>
                </div>
            </div><?php
        }
    }
?>
