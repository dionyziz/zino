<?php
    class ElementStatisticsView extends Element {
            
        
        public function Render(tInteger $daysBefore,tText $graphType) {
            global $page;
            global $user;

            if ( !$user->HasPermission( PERMISSION_STATISTICS_VIEW ) ) {
            ?> Permission denied <?php
            return;
            }

            $page->setTitle( 'Daily statistics' );

            $daysBefore = $daysBefore->Get();
            $graphType = $graphType->Get();

            if ( $daysBefore == 0 ) {
            $daysBefore = 30;
            }
            if ( $graphType == "" ) {
            $graphType = "Shoutbox";
            }
        
            ?><h2>Daily statistics</h2><?php

            ?><ul><?php                
                foreach ( array( 30, 60, 90 ) as  $days ) {                    
                    ?><li><?php
                    ?><a href="?p=statistics&amp;daysBefore=<?php echo $days;?>&amp;graphType=<?php echo $graphType ?> "> <?php
                    if ( $daysBefore == $days) {
                    ?><strong><?php
                    }
                    echo $days;
                    ?> days before<?php 
                    if ( $daysBefore == $days ) {
                    ?></strong><?php
                    }
                    ?></a></li><?php            
                }                
            ?></ul><?php

                ?><ul><?php                
                foreach ( array( 'Shoutbox', 'Users', 'Images', 'Polls', 'Comments', 'Journals', 'Albums', 'All' ) as $table) {
                    ?><li><?php
                    ?><a href="?p=statistics&amp;daysBefore=<?php echo $daysBefore; ?>&amp;graphType=<?php echo $table; ?>"> <?php

                    if (  $graphType == $table ) {
                    ?><strong><?php
                    }
                    
                    echo $table;
                
                    if (  $graphType == $table ) {
                    ?></strong><?php
                    }
            
                    ?></a></li><?php                    
                }
            ?></ul><?php
            
            
            foreach ( array( 'Shoutbox', 'Users', 'Images', 'Polls', 'Comments', 'Journals', 'Albums' ) as $table) {
                if ( $graphType == $table || $graphType == "All" ) {
                ?> <img src="images/statistics/stats.php?name=<?php echo strtolower($table[0]) . substr( $table , 1 ); ?>&amp;days=<?php echo $daysBefore; ?>" alt="img"/> <?php
                }
            }
           }
        }
?>
