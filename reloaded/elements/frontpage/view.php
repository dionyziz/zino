<?php
    function ElementFrontpageView() {
        global $page;
        global $rabbit_settings;
        global $user;
        
        $page->SetTitle( "Είσαι μέσα?" );
        $page->AttachStylesheet( 'css/sidebar.css' );
        $page->AttachScript( 'js/animations.js' );

        if ( $user->Username() == 'finlandos' || $user->Username() == 'd3nnn1z' ) {
            ?><div id="phoenixrelease">Μεγάλες αλλαγές σήμερα στο Zino... προετοιμάσου! Όταν ξεκινήσει η αναβάθμιση για λίγο το Zino δε θα λειτουργεί.</div><?php
        }
        
        Element( "photo/latest" );
        Element( "frontpage/leftbar" );

        $latestids = Element( "article/latest" );
        
        ?><div style="clear:both"></div><?php
        
        Element( "article/popular", $latestids );
        
        ?><br /><?php
		Element( "user/birthdays" );
        ?><br /><br /><?php
        Element( "notify/frontpage" );
        ?><br />
		<a href="index.php?p=advertise">&#187;Διαφημιστείτε στο <?php
        echo $rabbit_settings[ 'applicationname' ];
        ?></a><br /><br /><?php
        Element( "ad/leaderboardgameplanet" );
    }
?>
