<?php
class ElementPollRecentList extends Element {
        public function Render( tInteger $pageno ) {
        global $libs;

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
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display' , $poll->User->Id , $poll->User->Avatar->Id , $poll->User, true );
                        ?> δημιούργησε τη δημοσκόπηση
                        </div>
                        <div class="subject">
                            <a href="<?php
                            Element( 'url', $poll );
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
