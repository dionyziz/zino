<?php
    class ElementAdManagerList extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            if ( !$user->Exists() ) {
                return Redirect( '?p=ads' );
            }
            
            $libs->Load( 'admanager' );
            
            $adfinder = New AdFinder();
            $ads = $adfinder->FindByUser( $user );
            if ( empty( $ads ) ) {
                return Redirect( '?p=admanager/create' );
            }
            
            ?><ul><?php
            foreach ( $ads as $ad ) {
                ?><?php
            }
            ?></ul><?php
        }
    }
?>
