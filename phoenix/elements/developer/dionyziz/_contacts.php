<?php
    function ElementDeveloperDionyzizContacts( tString $username, tString $password ) {
        global $libs;

        $username = $username->Get();
        $password = $password->Get();

        $libs->Load();

        $gmail = New ContactsGmail();

        if ( $gmail->Login( $username, $password ) ) {
            $contacts = $gmail->Retrieve();
            ?><p><?php
            echo count( $contacts );
            ?> contacts found.</p>
            <ul><?php
            foreach ( $contacts as $email => $name ) {
                ?><li><?php
                echo htmlspecialchars( $email );
                ?></li><?php
            }
            ?></ul><?php
        }
        else {
            ?>Invalid login details provided.<?php
        }
    }
?>

