<?php
    function ElementFrontpageView() {
        global $page;
        global $rabbit_settings;
        
        $page->SetTitle( "Είσαι μέσα?" );
        $page->AttachStylesheet( 'css/sidebar.css' );
		$page->AttachStyleSheet( 'css/profileview.css' );
        $page->AttachScript( 'js/animations.js' );
        
        //Element( "photo/latest" );
		?><div class="rightslide">
			<div class="edge">
			</div>
		</div>
		<div class="upperslide">
			<img src="http://static.zino.gr/phoenix/img1.jpg" alt="img1" title="img1" />
			<img src="http://static.zino.gr/phoenix/img2.jpg" alt="img2" title="img2" />
			<img src="http://static.zino.gr/phoenix/img3.jpg" alt="img3" title="img3" />
			<img src="http://static.zino.gr/phoenix/img4.jpg" alt="img4" title="img4" />
			<img src="http://static.zino.gr/phoenix/img5.jpg" alt="img5" title="img5" />
			<img src="http://static.zino.gr/phoenix/img6.jpg" alt="img6" title="img6" />
			<img src="http://static.zino.gr/phoenix/img7.jpg" alt="img7" title="img7" />
			<img src="http://static.zino.gr/phoenix/img8.jpg" alt="img8" title="img8" />
			<img src="http://static.zino.gr/phoenix/img9.jpg" alt="img9" title="img9" />
		</div><?php
        Element( "frontpage/leftbar" );

        $latestids = Element( "article/latest" );
        
        ?><div style="clear:both"></div><?php
        
        //Element( "frontpage/rightbar" );
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
