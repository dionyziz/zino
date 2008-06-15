<?php
function ElementShoutboxList( tInteger $offset ) {
    global $user;
    global $libs;

    $offset = $offset->Get();

    $libs->Load( 'shoutbox' );

    $finder = New ShoutboxFinder();
    $shouts = $finder->FindLatest( $offset , 20 )
    ?><div class="shoutbox">
        <h2>Συζήτηση</h2>
        <div class="comments"><?php
            foreach ( $shouts as $shout ) {
                Element( 'shoutbox/view' , $shout , false );
            }
        ?></div>
    </div>
    <div class="eof"></div><?php
    Element( 'pagify', $offset, '?p=shoutbox&offset=', $finder->Count() );
}
?>
