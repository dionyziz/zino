<?php

	function daysDiff( $dateTimeBegin, $dateTimeEnd = NULL ) {
		if ( !isset( $dateTimeEnd ) ) {
			$dateTimeEnd = NowDate();
		}

		if ( !$dateTimeBegin || !$dateTimeEnd ) {
			// error condition
			return false;
		}

		$dateTimeBegin = strtotime( $dateTimeBegin );
		$dateTimeEnd = strtotime( $dateTimeEnd );

		if ( $dateTimeEnd === -1 || $dateTimeBegin === -1 ) {
			// error condition
			return false;
		}

		$diff = $dateTimeEnd - $dateTimeBegin;

		if ( $diff < 0 ) {
			// error condition
			return false;
		}

		$rest = ( $diff % 86400 );
		$days = ( $diff - $rest ) / 86400; // seconds a day

		return $days;
	}

	function dateDiff( $dateTimeBegin, $dateTimeEnd = NULL ) {
		if ( !isset( $dateTimeEnd ) ) {
			$dateTimeEnd = NowDate();
		}

		if ( !$dateTimeBegin || !$dateTimeEnd ) {
			// error condition
			return false;
		}

		$dateTimeBegin = strtotime( $dateTimeBegin );
		$dateTimeEnd = strtotime( $dateTimeEnd );

		if ( $dateTimeEnd === -1 || $dateTimeBegin === -1 ) {
			// error condition
			return false;
		}

		$diff = $dateTimeEnd - $dateTimeBegin;

		if ( $diff < 0 ) {
			// error condition
			return false;
		}

		$weeks = $days = $hours = $minutes = $seconds = 0; // initialize vars

		if ( $diff % 604800 > 0 ) {
			$rest1 = $diff % 604800;
			$weeks = ( $diff - $rest1 ) / 604800; // seconds a week
			if ( $rest1 % 86400 > 0 ) {
				$rest2 = ( $rest1 % 86400 );
				$days = ( $rest1 - $rest2 ) / 86400; // seconds a day
				if ( $rest2 % 3600 > 0 ) {
					$rest3 = ( $rest2 % 3600 );
					$hours = ( $rest2 - $rest3 ) / 3600; // seconds an hour
					if ( $rest3 % 60 > 0 ) {
						$seconds = ( $rest3 % 60 );
						$minutes = ( $rest3 - $seconds ) / 60; // seconds a minute
					} 
					else {
						$minutes = $rest3 / 60;
					}
				}
				else {
					$hours = $rest2 / 3600;
				}
			}
			else {
				$days = $rest1 / 86400;
			}
		}
		else {
			$weeks = $diff / 604800;
		}
		if ( $weeks ) {
			$hours = 0;
		}
		if ( $days || $weeks ) {
			$minutes = 0;
		}
		$months = floor( $weeks / 4 );
		if ( $months ) {
			$weeks -= $months * 4;
		}
		$years = floor( $months / 12 );
		if ( $years ) {
			$months -= $years * 12;
		}

		$result = array(
			'years' => $years,
			'months' => $months,
			'weeks' => $weeks,
			'days' => $days,
			'hours' => $hours,
			'minutes' => $minutes,
		);

		return $result;
	}

	function sqldate2xml( $date ) {
		return str_replace( ' ', 'T', $date ) . 'Z';
	}

	function ParseDate( $date, &$year, &$month, &$day, &$hour, &$minute, &$second ) {
		if ( !$date || $date == "0000-00-00 00:00:00" )
			return;

		$dateElements = split( ' ', $date );
		ParseSolDate( $dateElements[ 0 ], $year, $month, $day );

		$dateTimeElements = split( ':', $dateElements[ 1 ] );

		$hour = $dateTimeElements[ 0 ];
		$minute = $dateTimeElements[ 1 ];
		$second = $dateTimeElements[ 2 ];
	}

	function ParseSolDate( $date, &$year, &$month, &$day ) {
		$year = $month = $day = 0;
		$dateDateElements = explode( '-', $date );
		if ( count( $dateDateElements ) ) {
			$year = array_shift( $dateDateElements );
			if ( count( $dateDateElements ) ) {
				$month = array_shift( $dateDateElements );
				if ( count( $dateDateElements ) ) {
					$day = array_shift( $dateDateElements );
				}
			}
		}
	}

	function NowDate() {
		return date( 'Y-m-d H:i:s', time() );
	}

	function NowSolDate() {
		return date( 'Y-m-d', time() );
	}

	// TODO: make it non-greek-specific
	function GrMonth( $month ) {
		switch ( $month ) {
			case 1:
				return 'Ιαν';
			case 2:
				return 'Φεβ';
			case 3:
				return 'Μάρ';
			case 4:
				return 'Απρ';
			case 5:
				return 'Μάη';
			case 6:
				return 'Ιουν';
			case 7:
				return 'Ιούλ';
			case 8:
				return 'Αύγ';
			case 9:
				return 'Σεπ';
			case 10:
				return 'Οκτ';
			case 11:
				return 'Νοε';
			case 12:
				return 'Δεκ';
		}
	}

	function MakeDate( $sqldate ) {
		if ( !$sqldate )
			return '';

		ParseDate( $sqldate, $Year, $Month, $Day, $Hour, $Minute, $Second );

		$dateTimestamp = mktime( $Hour, $Minute, $Second, $Month, $Day, $Year );
		
		$dateDay = date( 'd', $dateTimestamp );
		$dateMonth = date( 'n', $dateTimestamp );
		$displayMonth = GrMonth( $dateMonth );
		$dateRst = date( 'y, H:i', $dateTimestamp );
		return "$dateDay $displayMonth $dateRst";
	}

?>
