<?php
    class ElementContactsMailtosend extends Element{
        function Render(){
            global $user;
            if ( !$user->Exists() ){
                return Redirect( './' );
            }
            ?><p><strong>Από:</strong> inviter@zino.gr</p>
            <p><strong>Θέμα:</strong> <?php
            Element( 'contacts/email/subject' );
            ?></p><p><?php
            ob_start();
            Element( 'contacts/email/message' );
            echo nl2br( ob_get_clean() );
            ?></p><?php
            return array( 'tiny' => true );
        }
    }
?>
