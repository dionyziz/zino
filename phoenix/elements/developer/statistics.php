<?php

    class ElementDeveloperStatistics extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'pageview' );

            $finder = New PageviewFinder();
            $bounces = $finder->FindTopBounces( 20 );
            $bouncesByElement = $bounces[ 0 ];
            $totalbounces = $bounces[ 1 ];
            ?><table>
            <thead><tr>
            <th>Element</th>
            <th>Bounce Rate</th>
            <th>Bounces</th>
            </tr></thead><tbody><?php
            foreach ( $bouncesByElement as $element => $bounces ) {
                ?><tr><td><?php
                echo $element;
                ?></td><td><?php
                echo $bounces / $totalbounces * 100;
                ?>%</td><td><?php
                echo $bounces;
                ?></td></tr><?php
            }
            ?></tbody></table><?php
        }
    }

?>
