<?php
    class ElementContactsMailtosend extends Element{
        function Render(){
            ?><p><b>Από:</b> inviter@zino.gr</p>
            <p><b>Θέμα:</b><?php
            Element( 'contacts/email/subject' );
            ?></p><p><?php
            Element( 'contacts/email/message' );
            ?></p><?php
            return array( 'tiny' => true );
        }
    }
?>
