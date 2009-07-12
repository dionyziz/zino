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
                        ?><li><div class="appbubble">
                            <span class="banner"><img src="<?php echo htmlspecialchars( $app->Logo ); ?>" /></span>
                            <span class="name"><h2><?php echo htmlspecialchars( $app->Name ); ?></h2></span><br />
                            <span class="description"><h3>Περιγραφή: </h3><?php echo htmlspecialchars( $app->Description ); ?></span><br />
                            <span class="links">
                                <h3>Διεύθυνση Εφαρμογής:</h3> <a href="<?php echo htmlspecialchars( $app->Url ); ?>"><?php echo htmlspecialchars( $app->Url ); ?></a>
                            </span>
                            <span class="key"><h3>Κλειδί Εφαρμογής: </h3><?php echo $app->GetToken(); ?></span>
                        </div></li><?php
                    }
                    ?></ul></div><?php
                }
                else {
                    ?>Δεν έχεις καταχωρήσει καμία εφαρμογή. Μπορείς να το κάνεις <a href="/?p=newapp">εδώ</a><?php
                }
            }
        }
    }
?>