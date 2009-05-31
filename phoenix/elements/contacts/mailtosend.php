<?php
    class ElementContactsMailtosend extends Element{
        function Render(){
            ?>Από: inviter@zino.gr
Θέμα: <?php
            Element( 'contacts/email/subject' );
            ?>

<?php
            Element( 'contacts/email/message' );
            return array( 'tiny' => true );
        }
    }
?>
