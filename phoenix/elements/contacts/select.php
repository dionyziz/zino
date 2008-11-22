<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $finder = new ContactFinder();
            $res = $finder->FindByUseridAndMail( $user->Id, $email );
            
            if( count( $res ) == 0 ) {
                ?><p>Παρουσιάστηκε κάποιο πρόβλημα.</p><?php
                return;
            }
            
            ?><p>Επαφές : </p><?php
            echo count( $res );
            foreach ( $res as $sample ) {
                ?><p><?php 
                $contact = new Contact( $sample->Id );
                echo $sample->Id;
                echo $contact->Mail;
                ?></p><?php
            }
        }
    }
?>
