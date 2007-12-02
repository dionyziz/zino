<?php
	function ElementArticleLatest() {
		global $user;
		global $page;
		global $libs;
		global $xc_settings;
        
		$libs->Load( 'search' );
		$libs->Load( 'article' );
		$libs->Load( 'category' );
		$libs->Load( 'pageviews' ); // needed for $search->SetRequirement( 'pageviews' )
		
		$search = New Search_Articles();
		$search->SetSortMethod( 'date', 'DESC' );
		$search->SetFilter( 'typeid', 0 );
		$search->SetFilter( 'delid', 0 );
		$search->SetRequirement( 'text' );
		$search->SetRequirement( 'pageviews' );
		$search->SetRequirement( 'editors' );
        $search->SetLimit( 4 );
		$latest = $search->Get();

		$latestids = array();
		?><div class="articles newestarticles"><?php
			Article_FormatSmallMulti( $latest );
			
            foreach ( $latest as $article ) {
                $latestids[ $article->Id() ] = true;
                Element( "article/small", $article );
            }
		    if ( $user->CanModifyStories() && $xc_settings[ 'readonly' ] <= $user->Rights() ) {
				?><a class="newarticle" href="?p=addstory&amp;id=0"><img class="newshout" src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/page_new.gif" title="Νέο άρθρο" alt="+" /> Νέο Άρθρο</a><?php
		    }
		    else {
				?><br /><?php
			}
		?><br /><a href="index.php?p=allarticles&amp;offset=1" style="padding-left:5px;">Παλαιότερα άρθρα</a>
		</div>
		<?php
		return $latestids;	
	}
?>
