<?php
    class ElementJournalRecentList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;
            global $xc_settings;

            $pageno = $pageno->Get();

            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            $finder = New JournalFinder();
            $journals = $finder->FindAll( 20 * ( $pageno - 1 ), 20 )
            ?><div class="journals">
                <h2>Ημερολόγια</h2>
                <div class="list"><?php
                    foreach ( $journals as $journal ) {
                        ?><div class="event">
                            <div class="who"><?php
                                Element( 'user/display' , $journal->User->Id , $journal->User->Avatar->Id , $journal->User, true );
                                $domain = str_replace( '*', urlencode( $journal->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                                $url = $domain . 'journals/' . $journal->Url;
                            ?> καταχώρησε
                            </div>
                            <div class="subject">
                                <a href="<?php
                                echo $url;
                                ?>"><?php
                                echo htmlspecialchars( $journal->Title );
                                ?></a>
                            </div>
                        </div><?php
                    }
                ?></div>
            </div>
            <div class="eof"></div><?php
            Element( 'pagify', $pageno, 'journals?pageno=', ceil( $journals->TotalCount() / 20 ) );
        }
    }
?>
