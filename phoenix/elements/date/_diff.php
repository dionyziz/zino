<?php

//masked by: Chorvus
//reason: testing purposes, fell free to delete it

    class ElementDateDiff extends Element {

        public function Render( $date ) {
            $diff = dateDiff( $date );
            
            if ( $diff === false ) {
                ?>ποτέ<?php
                return;
            }

            $years = $diff[ 'years' ];
            $months = $diff[ 'months' ];
            $weeks = $diff[ 'weeks' ];
            $days = $diff[ 'days' ];
            $hours = $diff[ 'hours' ];
            $minutes = $diff[ 'minutes' ];

            
            if ( $years ) {
                if ( $years == 1 ) {
                    ?>πέρσι<?php
                }
                else if ( $years == 2 ) {
                    ?>πρόπερσι<?php
                }
                else {
                    ?>πριν <?php
                    echo $years;
                    ?> χρόνια<?php
                }
            }
            else if ( $months ) {
                if ( $months == 1 ) {
                    ?>τον προηγούμενο μήνα<?php
                }
                else {
                    ?>πριν <?php
                    echo $months;
                    ?> μήνες<?php
                }
            }
            else if ( $weeks ) {
                if ( $weeks == 1 ) {
                    ?>την προηγούμενη εβδομάδα<?php
                }
                else {
                    ?>πριν <?php
                    echo $weeks;
                    ?> εβδομάδες<?php
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
                    ?>πριν <?php
                    echo $days;
                    ?> μέρες<?php
                }
            }
            else if ( $hours ) {
                if ( $hours == 1 ) {
                    ?>πριν 1 ώρα<?php
                }
                else {
                    ?>πριν <?php
                    echo $hours;
                    ?> ώρες<?php
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
                    ?>πριν <?php
                    echo $minutes;
                    ?> λεπτά<?php
                }
            }
            else {
                ?>πριν λίγο-here<?php
            }
            var_dump( $diff );

        }
    }

?>