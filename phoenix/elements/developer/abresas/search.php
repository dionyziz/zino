<?php

    class ElementDeveloperAbresasSearch extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'search' );

            $user = New User();

            $search = New Search( $user );
            $users = $search->Get();

            print_r( $users );
        }

    }
?>
