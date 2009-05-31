<?php
    class ElementContactsMailtosend extends Element{
        function Render(){
            ?><p><b>Από:</b> inviter@zino.gr</p>
            <p><b>Θέμα:</b> <?php
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
