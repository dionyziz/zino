<?php
function ElementShoutboxList() {
    global $user;
    global $libs;
    $libs->Load( 'shoutbox' );
    
    $finder = New ShoutboxFinder();
    $shouts = $finder->FindLatest( 0 , 20 )
    ?><div class="shoutbox">
        <h2>Συζήτηση</h2>
        <div class="comments"><?php
            foreach ( $shouts as $shout ) {
                Element( 'shoutbox/view' , $shout , false );
            }
        ?></div>
    </div><?php
}
?>
