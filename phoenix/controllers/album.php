<?php
    class ControllerAlbum extends ControllerZino {
        public function View( tInteger $id , tInteger $pageno ) {
            global $libs;
            
            $libs->Load( 'album' );
            
            $album = New Album( $id->Get( array( 'min' => 1 ) ) );
            
            if ( !$album->Exists() ) {
                return Element( '404', 'To album δεν υπάρχει' );
            }

            if ( $album->Ownertype == TYPE_USERPROFILE ) {
                if ( $album->Owner->Deleted ) {
                    $this->Redirect( 'http://static.zino.gr/phoenix/deleted' );
                    return;
                }
                if ( Ban::isBannedUser( $album->Owner->Id ) ) {
                    $this->Redirect( 'http://static.zino.gr/phoenix/banned' );
                    return;
                }
            }

            $pageno = $pageno->Get( array( 'min' => 1 ) );

            $finder = New ImageFinder();
            $images = $finder->FindByAlbum( $album , ( $pageno - 1 ) * 20 , 20 );
            $this->mPage->SetTitle( $album->Name );

            Element( 'developer/album/view', $album, $images, $pageno );
        }
    }
?>
