<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email, tInteger $step ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            $libs->Load( 'user/profile' );
            $libs->Load( 'relation/relation' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $email = $email->Get();
            $email = urldecode( $email );
            $step = $step->Get();            
            if ( $step != 1 && $step != 2 ) {
                $step = 0;
            }
            
            $finder = new ContactFinder();
            $contactsLoaded = $finder->FindByUseridAndMail( $user->Id, $email );
            
            if( count( $contactsLoaded ) == 0 ) {
                ?><p>Παρουσιάστηκε κάποιο πρόβλημα.</p><?php
                return;
            }
            
            
            $findemall  = new ContactFinder();
            $asxeto = $findemall->FindAllZinoMembersByUseridAndMail($user->Id,$email);
            echo count( $asxeto );
            foreach ( $asxeto as $key=>$val ) {
                ?><p><?php
                echo $key . " " . $val;
                ?></p><?php
            }
            
            $all_emails = array();
            foreach ( $contactsLoaded as $sample ) {
                $all_emails[] = $sample->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $zino_emails = $mailfinder->FindAllUsersByEmails( $all_emails );
            
            if ( $step == 1 ) { //step 1:send invites to user that are already in zino
            
                $relationfinder = new FriendRelationFinder();//find already zino friends
                $userRelations = $relationfinder->FindByUser( $user );
                $zino_friends = array();
                foreach ( $userRelations as $relation ) {
                    $zino_friends[ $relation->Friend->Id ] = true;
                }
                       
                ?><h3>Αυτοί οι φίλοι σου είναι ήδη στο zino!</h3><?php
                ?><form method="post" action="do/contacts/addfriends"><?php
                //echo count( $zino_emails );
                foreach ( $zino_emails as $key=>$val ) {
                    $friend = new User( $val ); 
                    if ( $zino_friends[ $friend->Id ] == NULL ) {
                        ?><p><?php
                        ?><input type="checkbox" name="approved[]" value="<?php echo $friend->Id; ?>" /> <?php 
                        echo $friend->Name . " - " . $friend->Profile->Email;                    
                        ?></p><?php
                    }
                }
                ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                    <input type="hidden" name="email" value="<?php echo $email; ?>" />
                    </form>
                <?php    
            }
            
            if ( $step == 2 ) { //step 2 - send invites to non zino users      
                ?><h3>Στείλε προσκλήσεις στους φίλους σου που δεν είναι μέλη στο Ζινο.</h3><?php
                ?><form method="post" action="do/contacts/invite"><?php
                //echo count( $res );
                foreach ( $contactsLoaded as $sample ) {
                    if ( $zino_emails[ $sample->Mail ] == NULL  ) {
                        ?><p><?php
                        ?><input type="checkbox" name="approved[]" value="<?php echo $sample->Mail; ?>" /> <?php 
                        echo $sample->Mail;                        
                        ?></p><?php
                    }
                }
                ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                  <input type="hidden" name="email" value="<?php echo $email; ?>" />
                  </form><?php
            }
            return;
        }
    }
?>
