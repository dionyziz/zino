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
            
            /*
            $findemall  = new ContactFinder();
            $NotZinoFriends = $findemall->FindNotZinoFriendMembersByUseridAndMail($user->Id,$email);
            $NotZinoMembers = $findemall->FindNotZinoMembersByUseridAndMail($user->Id,$email);
            echo count( $NotZinoMembers );
            foreach ( $NotZinoMembers as $key=>$val ) {
                ?><p><?php
                echo $key . " " . $val;
                ?></p><?php
            }
            echo count( $NotZinoFriends );
            foreach ( $NotZinoFriends as $key=>$val ) {
                ?><p><?php
                echo $key . " " . $val;
                ?></p><?php
            }
            */
            $all_emails = array();
            foreach ( $contactsLoaded as $sample ) {
                $all_emails[] = $sample->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $zino_emails = $mailfinder->FindAllUsersByEmails( $all_emails );
            
            if ( $step == 1 ) { //step 1:send invites to user that are already in zino
                $findemall  = new ContactFinder();//<-TODO check if none
                $NotZinoFriends = $findemall->FindNotZinoFriendMembersByUseridAndMail( $user->Id, $email );
                       
                ?><h3>Αυτοί οι φίλοι σου είναι ήδη στο zino!</h3><?php
                ?><form method="post" action="do/contacts/addfriends"><?php
                foreach ( $NotZinoFriends as $key=>$val ) {
                    $friend = new User( $val );                 
                    ?><p><?php
                    ?><input type="checkbox" name="approved[]" value="<?php echo $friend->Id; ?>" /> <?php 
                    echo $friend->Name . " - " . $friend->Profile->Email;                    
                    ?></p><?php                    
                }
                ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                    <input type="hidden" name="email" value="<?php echo $email; ?>" />
                    </form>
                <?php    
            }
            
            if ( $step == 2 ) { //step 2 - send invites to non zino users    
                $findemall  = new ContactFinder();
                $NotZinoMembers = $findemall->FindNotZinoMembersByUseridAndMail( $user->Id, $email );
                
                ?><h3>Στείλε προσκλήσεις στους φίλους σου που δεν είναι μέλη στο Ζινο.</h3><?php
                ?><form method="post" action="do/contacts/invite"><?php
                foreach ( $NotZinoMembers as $key=>$val ) {
                    ?><p><?php
                    ?><input type="checkbox" name="approved[]" value="<?php echo $key; ?>" /> <?php 
                    echo $key;                        
                    ?></p><?php
                }
                ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                  <input type="hidden" name="email" value="<?php echo $email; ?>" />
                  </form><?php
            }
            return;
        }
    }
?>
