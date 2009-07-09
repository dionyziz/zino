<?php

    class ElementDeveloperStatistics extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'pageview' );

            $finder = New PageviewFinder();
            $bouncesByUrl = $finder->FindTopBounces( $totalbounces, 20 );
            ?><table>
            <thead><tr>
            <th>URL</th>
            <th>Bounce Rate</th>
            <th>Bounces</th>
            </tr></thead><tbody><?php
            foreach ( $bouncesByUrl as $url => $bounces ) {
                ?><tr><td><?php
                echo $url;
                ?></td><td><?php
                echo $bounces / $totalbounces;
                ?>%</td><td><?php
                echo $bounces;
                ?></td></tr><?php
            }
            ?></tbody></table><?php
        }
    }

?>
