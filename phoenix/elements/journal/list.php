<?php
    
    class ElementJournalList extends Element {
        public function Render( tText $username , tText $subdomain , tInteger $pageno ) {
            global $page;
            global $rabbit_settings;
            global $xc_settings;
            global $user;
            global $libs;
            
            $libs->Load( 'journal/journal' );
            
            Element( 'user/subdomainmatch' );
            
            $username = $username->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();    
            if ( $username != '' ) {
                if ( strtolower( $username ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $username );
                }
            }
            else if ( $subdomain != '' ) {
                if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindBySubdomain( $subdomain );
                }
            }
            if ( !isset( $theuser ) || $theuser === false ) {
                $page->SetTitle( "Δε βρέθηκε ο χρήστης" );
                ?>Ο χρήστης δεν υπάρχει<?php
                return;
            }

            if ( $theuser->Deleted ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/deleted' );
            }
            if ( Ban::isBannedUser( $theuser->Id ) ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/banned' );
            }

            if ( ctype_upper( substr( $theuser->Name, 0, 1 ) ) ) {
                $page->SetTitle( $theuser->Name . " Ημερολόγιο" );
            }
            else {
                $page->SetTitle( $theuser->Name . " ημερολόγιο" );
            }
            
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            $finder = New JournalFinder();
            $journals = $finder->FindByUser( $theuser, ( $pageno - 1 ) * 5, 5 );
            
            Element( 'user/sections' , 'journal' , $theuser );
            ?><div id="jlist">
                <div class="ads"></div>
                <ul><?php
                    if ( $theuser->Id == $user->Id ) {
                        ?><li class="create">
                            <a href="?p=addjournal" class="new"><span class="s1_0048">Δημιουργία καταχώρησης</span></a>
                        </li><?php
                    }
                    if ( !empty( $journals ) ) {
                        foreach ( $journals as $journal ) {
                            ?><li><?php
                                Element( 'journal/small' , $journal );
                                ?><div class="barfade">
                                    <div class="s1_0070 leftbar"></div>
                                    <div class="s1_0071 rightbar"></div>
                                </div>
                            </li><?php
                        }
                    }
                    else {
                        if ( $theuser->Id != $user->Id ) {
                            ?>Δεν υπάρχουν καταχωρήσεις<?php
                        }
                    }
                ?></ul>
                <div class="pagifyjournals"><?php
                $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . 'journals?pageno=';
                $total_journals = $theuser->Count->Journals;
                $total_pages = ceil( $total_journals / 5 );
                Element( 'pagify', $pageno, $link, $total_pages, "( " . $total_journals . " Καταχωρήσεις )" );
                ?></div>
            </div>
            <div class="eof"></div><img src="<?php
            echo $rabbit_settings[ 'imagesurl' ];
            ?>heart.png" style="display:none" /><?php
            $page->AttachInlineScript( 'JournalList.OnLoad();' );
        }
    }
?>
