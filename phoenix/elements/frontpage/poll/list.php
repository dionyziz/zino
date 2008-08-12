<?php
    class ElementFrontpagePollList extends Element {
        public function Render() {
            $finder = New PollFinder();
            $polls = $finder->FindAll( 0 , 4 );
            ?><div class="list">
                <h2>Δημοσκοπήσεις (<a href="?p=allpolls">προβολή όλων</a>)</h2><?php
                foreach ( $polls as $poll ) {
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display' , $poll->User->Id , $poll->User->Avatar->Id , $poll->User );
                        ?> καταχώρησε
                        </div>
                        <div class="subject">
                            <a href="?p=poll&amp;id=<?php
                            echo $poll->Id;
                            ?>"><?php
                            echo htmlspecialchars( $poll->Question );
                            ?></a>
                        </div>
                    </div><?php
                }
            ?></div><?php
        }
    }
?>
