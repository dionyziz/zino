<?php

    class ElementStatisticsBounces extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'pageview' );

            $finder = New PageviewFinder();
            $bounces = $finder->FindBounceRates( 20 );
            $bounceRates = $bounces[ 0 ];
            $bouncesByElement = $bounces[ 1 ];
            $landingsByElement = $bounces[ 2 ];
            ?><table>
            <thead><tr>
            <th>Element</th>
            <th>Bounce Rate</th>
            <th>Bounces</th>
            <th>Landings</th>
            </tr></thead><tbody><?php
            foreach ( $bouncesByElement as $element => $bounces ) {
                ?><tr><td><?php
                echo $element;
                ?></td><td><?php
                echo $bounceRates[ $element ];
                ?>%</td><td><?php
                echo $bounces;
                ?></td><td><?php
                echo $landingsByelement[ $element ];
                ?></td></tr><?php
            }
            ?></tbody></table><?php
        }
    }

?>
