<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            $libs->Load( 'user/profile' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $finder = new ContactFinder();
            $res = $finder->FindByUseridAndMail( $user->Id, $email );
            
            if( count( $res ) == 0 ) {
                ?><p>Παρουσιάστηκε κάποιο πρόβλημα.</p><?php
                return;
            }
            
            $emails = array();
            foreach ( $res as $sample ) {
                $emails[] = $sample->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $mails = $mailfinder->FindAllUsersByEmails( $emails );
            
            ?><p>Επέλεξε τους φίλους σου που θες να σταλεί πρόσκληση: </p><?php
            ?><form method="post" action=""><?php
            echo count( $res );
            foreach ( $res as $sample ) {
                ?><p><?php
                ?><input type="checkbox" name="approved" /> <?php 
                $contact = new Contact( $sample->Id );                              
                echo $contact->Mail;
                if ( $mails[ $contact->Mail ] !== NULL  ) {
                    ?> ---- Ηδη μέλος στο Zino <?php
                }   
                ?></p><?php
            }
            ?></form><?php
        }
    }
?>
