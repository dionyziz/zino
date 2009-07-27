<?php 
    class ElementAlbumManager extends Element {
        public function Render () { 
            global $page;
            global $user;
            global $rabbit_settings;
            global $xc_settings;
            global $libs;
            
            $page->AttachScript( 'js/ui.base.js' );
            $page->AttachScript( 'js/ui.draggable.js' );
            $page->AttachScript( 'js/ui.droppable.js' );
            $page->AttachScript( 'js/jquery.pagination.js' );
            
            $page->SetTitle( "Διαχείριση Φωτογραφιών" );
            
            if ( !$user->Exists() ) { 
                ?>Πρέπει να είσαι συνδεδεμένος για να χρησιμοποιήσεις αυτήν την λειτουργία<?php
                return;
            }
            
            $libs->Load( 'album' );
            
            ?><div class="photomanager" id="photomanager">
                <h2>Διαχείριση Φωτογραφιών</h2>
                <div class="manager" id="manager">
                    <div class="albums" id="albums">
                        <h2>Albums</h2>
                        <div class="fadetop" />
                        <div class="albumlist">
                            <ul style="height: 400px; position: relative;"><?php
                            $finder = New AlbumFinder();
                            $albums = $finder->FindByUser( $user, 0, 100 );
                            Element( 'album/row', $user->EgoAlbum );
                            foreach ( $albums as $album ) {
                                if ( $album->Id != $user->Egoalbumid ) {
                                    Element( 'album/row', $album );
                                }
                            }
                            ?></ul>
                        </div>
                        <div class="fadebot" />
                    </div>
                    <div class="photos" id="photos">
                        <div id="pages" class="pagination" />
                        <ul class="photolist" id="photolist" />
                    </div>
                </div>
            </div>
            <div class="eof"/><?php
            $page->AttachInlineScript( 'PhotoManager.OnLoad();' );
        }
    }
?>