<?php
    class ElementApplicationList extends Element {
    
        public function Render() {
            global $libs;
            global $user;
            
            $libs->Load( 'application' );
            
            $testers = Array(
                                4499, //ch0rvus-beta
                                5104, //chorvus-live
                            );
            
            if ( !in_array( $user->Id, $testers ) ) {
                ?>Δεν έχεις πρόσβαση σε αυτήν την σελίδα.<?
            }
            else {
                ?><div id="appmanager">
                    <ul id="applist"><?php
                    $finder = New ApplicationFinder();
                    $apps = $finder->FindByUser( $user );
                    if ( $apps ) {
                        foreach ( $apps as $app ) {
                            Element( 'application/view', $app );
                        }
                    }
                    else {
                        ?>Δεν έχεις καταχωρήσει καμία εφαρμογή.<?php
                    }
                    ?><li><div class="appbubble" id="newappbubble"><form method="POST" action="do/application/new">
                        <span id="edittitle"><h3>Δημιουργία</h3></span>
                        <table border="0" padding="0">
                            <tr><td>Όνομα:</td><td><input type="text" name="name" /></td></tr>
                            <tr><td>Διέυθυνση:</td><td><input type="text" name="url" /></td></tr>
                            <tr><td>Περιγραφή:</td><td><input type="textarea" name="description" /></td></tr>
                            <tr><td>Λογότυπο:</td><td><input type="text" name="logo" /></td></tr>
                            </table>
                            <input type="submit" name="newapplication" onclick="Applications.checkValidity()" value="Δημιουργία" />
                        </form></div></li>
                    </ul>
                </div>
                <?php
            }
        }
    }
?>