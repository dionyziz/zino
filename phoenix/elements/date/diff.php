<?php

    class ElementDateDiff extends Element {

        public function Render( $timestamp ) {
            $date = date( 'Y-m-d H:i:s', $timestamp );
            $diff = dateDistance( $date );

            $months = $diff[ 'months' ];
            $weeks = $diff[ 'weeks' ];
            $days = $diff[ 'days' ];
            $hours = $diff[ 'hours' ];
            $minutes = $diff[ 'minutes' ];

            if ( $months ) {
                if ( count( $months ) === 1 ) {
                    ?>τον προηγούμενο μήνα<?php
                }
                else {
                    ?>πριν<?php
                    echo $months;
                    ?>μήνες<?php
                }
            }
            else if ( $weeks ) {
                if ( count( $weeks ) === 1 ) {
                    ?>την προηγούμενη εβδομάδα<?php
                }
                else {
                    ?>πριν<?php
                    echo $weeks;
                    ?>εβδομάδες<?php
                }
            }
            else if ( $days ) {
                if ( count( $days ) === 1 ) {
                    ?>χθες<?php
                }
                else if ( count( $days ) == 2 ) {
                    ?>προχθές<?php
                }
                else {
                    ?>πριν<?php
                    echo $days;
                    ?>μέρες<?php
                }
            }
            else if ( $hours ) {
                if ( count( $hours ) === 1 ) {
                    ?>πριν 1 ώρα<?php
                }
                else {
                    ?>πριν<?php
                    echo $hours;
                    ?>ώρες<?php
                }
            }
            else if ( $minutes ) {
                if ( count( $minutes ) === 1 ) {
                    ?>πριν 1 λεπτό<?php
                }
                else if ( count( $minutes ) == 15 ) {
                    ?>πριν ένα τέταρτο<?php
                }
                else if ( count( $minutes ) == 30 ) {
                    ?>πριν μισή ώρα<?php
                }
                else if ( count( $minutes ) == 45 ) {
                    ?>πριν τρία τέταρτα<?php
                }
                else {
                    ?>πριν<?php
                    echo $minutes;
                    ?>λεπτά<?php
                }
            }
            else {
                ?>πριν λίγο<?php
            }
        }
    }

?>

