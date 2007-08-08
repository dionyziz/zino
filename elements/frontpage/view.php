<?php
    function ElementFrontpageView() {
        global $page;
        
        $page->SetTitle( "Συζήτηση και διασκέδαση" );
        $page->AttachStylesheet( 'css/sidebar.css' );
        $page->AttachScript( 'js/animations.js' );
        
        Element( "frontpage/leftbar" );

        $latestids = Element( "article/latest" );
        
        ?><div style="clear:both"></div><?php
        
        Element( "frontpage/rightbar" );
        Element( "article/popular", $latestids );
        
        ?><br /><div style="clear:both"></div><?php
        Element( "user/birthdays" );
        ?><br /><br /><?php
        Element( "photo/latest" );
        Element( "notify/frontpage" );
        ?><br /><?php
        Element( "ad/leaderboard" );
    }
?>
