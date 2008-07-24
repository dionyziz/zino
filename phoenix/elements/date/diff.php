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
                if ( $months == 1 ) {
                    ?>τον προηγούμενο μήνα<?php
                }
                else {
                    ?>πριν<?php
                    echo $months;
                    ?>μήνες<?php
                }
            }
            else if ( $weeks ) {
                if ( $weeks == 1 ) {
                    ?>την προηγούμενη εβδομάδα<?php
                }
                else {
                    ?>πριν<?php
                    echo $weeks;
                    ?>εβδομάδες<?php
                }
            }
            else if ( $days ) {
                if ( $days == 1 ) {
                    ?>χθες<?php
                }
                else if ( $days == 2 ) {
                    ?>προχθές<?php
                }
                else {
                    ?>πριν<?php
                    echo $days;
                    ?>μέρες<?php
                }
            }
            else if ( $hours ) {
                if ( $hours == 1 ) {
                    ?>πριν 1 ώρα<?php
                }
                else {
                    ?>πριν<?php
                    echo $hours;
                    ?>ώρες<?php
                }
            }
            else if ( $minutes ) {
                if ( $minutes == 1 ) {
                    ?>πριν 1 λεπτό<?php
                }
                else if ( $minutes == 15 ) {
                    ?>πριν ένα τέταρτο<?php
                }
                else if ( $minutes == 30 ) {
                    ?>πριν μισή ώρα<?php
                }
                else if ( $minutes == 45 ) {
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
