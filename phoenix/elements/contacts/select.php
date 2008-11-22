<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $finder = new ContactsFinder();
            $res = $finder->FindByUseridAndMail( $user->Id, $email );
            
            if( count( $res ) == 0 ) {
                ?><p>Παρουσιάστηκε κάποιο πρόβλημα.<p><?php
                return;
            }
            
            foreach ( $res as $sample ) {
                ?><p><?php 
                echo $sampe->Mail;
                ?></p><?php
            }
        }
    }
?>
