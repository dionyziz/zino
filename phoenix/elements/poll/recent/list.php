<?php
class ElementPollRecentList extends Element {
        public function Render( tInteger $pageno ) {
        global $libs;
        global $xc_settings;

        $pageno = $pageno->Get();

        if ( $pageno <= 0 ) {
            $pageno = 1;
        }
        $libs->Load( 'poll/poll' );
        $finder = New PollFinder();
        $polls = $finder->FindAll( 20 * ( $pageno - 1 ), 20 )
        ?><div class="polls">
            <h2>Δημοσκοπήσεις</h2>
            <div class="list"><?php
                foreach ( $polls as $poll ) {
                    $domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                    $url = $domain . 'polls/' . $poll->Url;
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display' , $poll->User->Id , $poll->User->Avatar->Id , $poll->User );
                        ?> δημιούργησε τη δημοσκόπηση
                        </div>
                        <div class="subject">
                            <a href="<?php
                            echo $url;
                            ?>"><?php
                            echo htmlspecialchars( $poll->Question );
                            ?></a>
                        </div>
                    </div><?php
                }
            ?></div>
        </div>
        <div class="eof"></div><?php
        Element( 'pagify', $pageno, 'polls?pageno=', ceil( $finder->Count() / 20 ) );
    }
}
?>
