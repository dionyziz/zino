<?php

	/* 
	Developer: Aleksis Brezas <abresas@gmail.com>
	
	Class for handling graphs ( see example for use in images/graphs/activity.php ).
	*/
	
	final class Graph {
		private $mCaption;
		private $mGraphWidth;
		private $mGraphHeight;
		private $mData;
		private $mDataMax;
		private $mDataAvg;
		private $mTimeLength;
        private $mHighlightLast;
		private $mIm;
		
		public function Graph( $caption ) {
			$this->mCaption = $caption;
            $this->mHighlightLast = false;
		}
        public function HighlightLast() {
            $this->mHighlightLast = true;
        }
		public function SetSmoothing( $smooth ) {
			++$smooth;
			
			$newdata = array();
			for ( $i = 0, $j = 0; $i < count( $this->mData ); $i += $smooth, ++$j ) {
				$sum = 0;
				for ( $k = 0; $k < $smooth; ++$k ) {
					$sum += $this->mData[ $i ];
				}
				$avg = $sum / $smooth;
				$newdata[ $j ] = $avg;
			}
			$this->mData = $newdata;
		}
		public function SetSize( $width, $height ) {
			$this->mGraphWidth = $width;
			$this->mGraphHeight = $height;
			
			define( 'MAXWIDTH', imagefontwidth( 5 ) * strlen( $this->mDataMax ) );
			define( 'ACTUAL_WIDTH'  , $this->mGraphWidth - MAXWIDTH - 5 );
			define( 'ACTUAL_HEIGHT' , $this->mGraphHeight - 45 );
			define( 'TEXT_SIZE' , 12 );
			
			$this->mIm = imagecreatetruecolor( $width, $height ); // start creating image
		}
		public function SetData( $data ) {
			$this->mData = $data;		
			
			$max = 0;
			$avg = 0;
			foreach ( $this->mData as $perioddata ) { // get max and average values from data
				if ( $perioddata > $max ) { // if there wasn't any bigger value than the current
					$max = $perioddata; // then the max value is the current value
				}
				$avg += $perioddata;
			}
			$avg /= count( $this->mData );
			
			$this->mDataMax = $max;
			$this->mDataAvg = $avg;		
		}
		public function SetTime( $timelength ) {
			$this->mTimeLength = $timelength;
		}
		public function Render() {
			static $months = array(
				0 => 'Jan' , 1 => 'Feb' , 2 => 'Mar' , 3 => 'Apr' ,
				4 => 'May' , 5 => 'Jun' , 6 => 'Jul' , 7 => 'Aug' ,
				8 => 'Sep' , 9 => 'Oct' , 10 => 'Nov' , 11 => 'Dec'
			);
			
			$nowdate = time() - $this->mTimeOffset * 24 * 60 * 60;
			$nowmonth = date( 'n' , $nowdate );
			$nowday   = date( 'j' , $nowdate );
			
			imagefilltoborder( $this->mIm , 0 , 0 , imagecolorallocate( $this->mIm, 255, 255, 255 ) , imagecolorallocate( $this->mIm, 255, 255, 255 ) );
			
			imagerectangle( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH , 0 , $this->mGraphWidth - 1 , ACTUAL_HEIGHT , 0 );
			
			imagefilltoborder( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH / 2 , 1 , 0 , imagecolorallocate( $this->mIm, 223, 13, 22 ) );
			
			$oldpageviews = $this->mData[ 0 ];
			for ( $i =  1 ; $i < count( $this->mData ); ++$i ) {
				$pageviews = $this->mData[ $i ];
				$x1 = $this->mGraphWidth - ACTUAL_WIDTH + ( $i - 1 ) * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$x2 = $this->mGraphWidth - ACTUAL_WIDTH + $i * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$y1 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $oldpageviews / $this->mDataMax );
				$y2 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $pageviews / $this->mDataMax );
				imageline( $this->mIm, $x1, $y1, $x2, $y2, imagecolorallocate( $this->mIm, 0, 0, 0 ) );
				$oldpageviews = $pageviews;
		    }
            $lastpageviews = $oldpageviews;
			
			imagefilltoborder( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH / 2 , 1 , imagecolorallocate( $this->mIm, 0, 0, 0 ) , imagecolorallocate( $this->mIm, 255, 220, 132 ) );
			
			$oldpageviews = $this->mData[ 0 ];
			for ( $i =  1 ; $i < count( $this->mData ); ++$i ) {
				$pageviews = $this->mData[ $i ];
				$x1 = $this->mGraphWidth - ACTUAL_WIDTH + ( $i - 1 ) * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$x2 = $this->mGraphWidth - ACTUAL_WIDTH + $i * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$y1 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $oldpageviews / $this->mDataMax );
				$y2 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $pageviews / $this->mDataMax );
				imageline( $this->mIm, $x1, $y1, $x2, $y2, imagecolorallocate( $this->mIm, 0, 0, 0 ) );
				$oldpageviews = $pageviews;
			}
			
			imageantialias( $this->mIm , true );
		
			$oldpageviews = $this->mData[ 0 ];
			for ( $i =  1 ; $i < count( $this->mData ); ++$i ) {
				$pageviews = $this->mData[ $i ];
				$x1 = $this->mGraphWidth - ACTUAL_WIDTH + ( $i - 1 ) * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$x2 = $this->mGraphWidth - ACTUAL_WIDTH + $i * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$y1 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $oldpageviews / $this->mDataMax );
				$y2 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $pageviews / $this->mDataMax );
				imageline( $this->mIm, $x1, $y1, $x2, $y2, imagecolorallocate( $this->mIm, 0, 0, 0 ) );
				$oldpageviews = $pageviews;
			}
			
			// draw surrounding box (again)
			imagerectangle( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH , 0 , $this->mGraphWidth - 1 , ACTUAL_HEIGHT , imagecolorallocate( $this->mIm, 0, 0, 0 ) );
			
			
			// draw vertical rulers
			for ( $x = ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ; $x < ACTUAL_WIDTH ; $x += ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ) {
				$actualx = $x + $this->mGraphWidth - ACTUAL_WIDTH;
				
				for ( $y = 1 ; $y < ACTUAL_HEIGHT ; $y += 20 ) {
					imageline( $this->mIm , $actualx , $y , $actualx , $y + 10 , imagecolorallocate( $this->mIm, 150, 190, 255 ) );
				}
			}
			
			// draw months text
			$month = $nowmonth;
			for ( $x = ACTUAL_WIDTH - (($nowday - 15) / 30) * ACTUAL_WIDTH / ( $this->mTimeLength / 30 ); $x > 0 ; $x -= ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ) {
				--$month;
				if ( $month < 0 ) {
					$month += 12;
				}
				$text = $months[ $month ];
				$actualx = $x + $this->mGraphWidth - ACTUAL_WIDTH;
				$textwidth = imagefontwidth( 5 ) * strlen( $text );
				imagestring( $this->mIm , 5 , $actualx - $textwidth / 2 , ACTUAL_HEIGHT , $text , imagecolorallocate( $this->mIm, 0, 0, 0 ) );
			}
			
			// draw horizontal rulers and vertical values
			$i = $this->mDataMax / 5;
			for ( $y = ACTUAL_HEIGHT - ( ACTUAL_HEIGHT - 10 ) / 5; $y > 0; $y -= ( ACTUAL_HEIGHT - 10 ) / 5, $i += $this->mDataMax / 5 ) {
				$text = round( $i );
				$textwidth = imagefontwidth( 5 ) * strlen( $text );
				$widthdiff = MAXWIDTH - $textwidth;
				$x = 8;
				imagestring( $this->mIm, 5, $x + $widthdiff - 5, $y, $text, imagecolorallocate( $this->mIm, 0, 0, 0 ) );
				$x += MAXWIDTH;
				$line_y = $y + imagefontheight( 5 ) / 2;
				while ( $x < $this->mGraphWidth ) {
					imageline( $this->mIm , $x , $line_y , $x + 10 , $line_y , imagecolorallocate( $this->mIm, 150, 190, 255 ) );
					$x += 20;
				}
			}
			
	        // average line
			imageline( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH + 1 , ACTUAL_HEIGHT - ACTUAL_HEIGHT * $this->mDataAvg / $this->mDataMax , $this->mGraphWidth - 1 , ACTUAL_HEIGHT - ACTUAL_HEIGHT * $this->mDataAvg / $this->mDataMax , imagecolorallocate( $this->mIm, 60, 243, 149 ) );

            // last line
            // if ( $this->mHighLightLast ) {
                $y = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $lastpageviews / $this->mDataMax );
                imageline( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH + 1 , $y , $this->mGraphWidth - 1 , $y , imagecolorallocate( $this->mIm, 255, 63, 56 ) );
//            }
			
			//display graph caption
	        $textwidth = imagefontwidth( 5 ) * strlen( $this->mCaption );
	        imagestring( $this->mIm , 5 , ACTUAL_WIDTH / 2 , ACTUAL_HEIGHT + 15 , $this->mCaption, imagecolorallocate( $this->mIm, 60, 243, 149 ) );
			
			// create image
			imagepng( $this->mIm );
		}
	}

	/* class Graph {
		private $mCaption;
		private $mData;
		private $mGraphWidth;
		private $mGraphHeight;
		private $mIm;
		private $mTimeLength;
		private $mTimeOffset;
		private $mDataMax;
		private $mDataAvg;
		private $mSections;
		private $mGraphBoxColor;
		private $mGraphColor;
		private $mGraphBorderColor;
		private $mSpaceColor;
		private $mGraphTextColor;
		private $mGraphMainLineColor;
		private $mGraphLinesColor;
		private $mAverageLineColor;
		
		public function Graph( $caption ) {
			$this->mCaption = $caption;
			$this->SetSize( 600, 300 ); // set default size
			$this->SetTime( 0, 90 ); // set default time
			$this->mData = array(); // mData is array
			
			// Set Default Colors
			// maybe these need better names
			$this->SetColor( "graphbox", "lightorange" );
			$this->SetColor( "graphcolor", "orange" );
			$this->SetColor( "graphborder", "black" );
			$this->SetColor( "space", "white" );
			$this->SetColor( "graphtext", "black" );
			$this->SetColor( "maingraphline", "black" );
			$this->SetColor( "graphlines", "blue" );
			$this->SetColor( "averageline", "green" );
			$this->SetColor( "caption", "green" );
			
			// End Set Default Colors
		}
		public function SetData( $data ) {
			// w_assert( is_array( $data ) );
			$this->mData = $data;		
			
			$max = 0;
			$avg = 0;
			foreach ( $this->mData as $perioddata ) { // get max and average values from data
				if ( $perioddata > $max ) { // if there wasn't any bigger value than the current
					$max = $perioddata; // then the max value is the current value
				}
				$avg += $perioddata;
			}
			$avg /= count( $this->mData );
			
			$this->mDataMax = $max;
			$this->mDataAvg = $avg;
		}
		public function SetSize( $graphwidth, $graphheight ) {
			$this->mGraphWidth = $graphwidth;
			$this->mGraphHeight = $graphheight;
			
			define( 'MAXWIDTH', imagefontwidth( 5 ) * strlen( $this->mDataMax ) * 4 );
			define( 'ACTUAL_WIDTH'  , $this->mGraphWidth  - MAXWIDTH - 10 );
			define( 'ACTUAL_HEIGHT' , $this->mGraphHeight - 45 );
			define( 'TEXT_SIZE' , 12 );
			
			$this->mIm = imagecreatetruecolor( $this->mGraphWidth , $this->mGraphHeight ); // start creating image
		}
		public function SetTime( $timeoffset , $timelength ) {
			$this->mTimeOffset = $timeoffset;
			$this->mTimeLength = $timelength;
		}
		public function SetColor( $section, $color ) {
			// color is either an array of rbg values, or a color name
			// section: the section you are changing color
			
			if ( !is_array( $color ) ) {
				$colours = array(
					'white' => array( 255, 255, 255),
					'orange' => array( 239, 128, 0 ),
					'lightorange' => array( 255, 220, 132 ),
					'blue' => array( 150, 190, 255 ),
					'green' => array( 60, 243, 149 ),
					'red' => array( 223, 13, 22 ),
					'yellow' => array( 253, 255, 31 ),
					'black' => array( 0, 0, 0 )
				);
				if ( !isset( $colours[ $color ] ) ) {
					$color = 'black';
				}
				$rgb = $colours[ $color ];
			}
			else { // array with rgb
				$rgb = $color;
			}
			switch ( $section ) {
				case "graphbox":
					$this->mGraphBoxColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "graphcolor":
					$this->mGraphColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "graphborder":
					$this->mGraphBorderColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "space":
					$this->mSpaceColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "graphtext":
					$this->mGraphTextColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "maingraphline":
					$this->mMainGraphLineColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "graphlines":
					$this->mGraphLinesColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "averageline":
					$this->mAverageLineColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
					break;
				case "caption":
					$this->mCaptionColor = $this->AllocateColor( $rgb[ 0 ], $rgb[ 1 ], $rgb[ 2 ] );
				default:
					break;
			}
		}
		private function AllocateColor( $red, $green, $blue ) {
			return imagecolorallocate( $this->mIm, $red, $green, $blue );
		}
		public function Render() {
			global $water;
			
			static $months = array(
				0 => 'Jan' , 1 => 'Feb' , 2 => 'Mar' , 3 => 'Apr' ,
				4 => 'May' , 5 => 'Jun' , 6 => 'Jul' , 7 => 'Aug' ,
				8 => 'Sep' , 9 => 'Oct' , 10 => 'Nov' , 11 => 'Dec'
			);
			
			$nowdate = time() - $this->mTimeOffset * 24 * 60 * 60;
			$nowmonth = date( 'n' , $nowdate );
			$nowday   = date( 'j' , $nowdate );
			
			imagefilltoborder( $this->mIm , 1 , 1 , $this->mSpaceColor , $this->mSpaceColor );
			
			// draw surrounding box
			imagerectangle( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH , 0 , $this->mGraphWidth - 1 , ACTUAL_HEIGHT , $this->mGraphBorderColor );
		
			imagefilltoborder( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH / 2 , 1 , $this->mMainGraphLineColor , $this->mGraphColor );
			
			$this->Graph_Lines( $this->mGraphBorderColor );
			
			imagefilltoborder( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH / 2 , 1 , $this->mGraphBorderColor , $this->mGraphBoxColor );
			
			$this->Graph_Lines( $this->mMainGraphLineColor );
			
			imageantialias( $this->mIm , true );
		
			$this->Graph_Lines( $this->mGraphBorderColor );
			
			// draw surrounding box (again)
			imagerectangle( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH , 0 , $this->mGraphWidth - 1 , ACTUAL_HEIGHT , $this->mGraphBorderColor );
		
			// draw vertical rulers
			for ( $x = ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ; $x < ACTUAL_WIDTH ; $x += ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ) {
				$actualx = $x + $this->mGraphWidth - ACTUAL_WIDTH;
				for ( $y = 1 ; $y < ACTUAL_HEIGHT ; $y += 20 ) {
					imageline( $this->mIm , $actualx , $y , $actualx , $y + 10 , $this->mGraphLinesColor );
				}
			}
			
			// draw months text
			$month = $nowmonth;
			for ( $x = ACTUAL_WIDTH - (($nowday - 15) / 30) * ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ; $x > 0 ; $x -= ACTUAL_WIDTH / ( $this->mTimeLength / 30 ) ) {
				--$month;
				if ( $month < 0 ) {
					$month += 12;
				}
				$text = $months[ $month ];
				$actualx = $x + $this->mGraphWidth - ACTUAL_WIDTH;
				$textwidth = imagefontwidth( 5 ) * strlen( $text );
				// $textrect = imagettfbbox( TEXT_SIZE , 0 , 
				imagestring( $this->mIm , 5 , $actualx - $textwidth / 2 , ACTUAL_HEIGHT , $text , $this->mGraphTextColor );
			}
			
			// draw horizontal rulers and vertical values
			$i = $this->mDataMax / 5;
			for ( $y = ACTUAL_HEIGHT - ( ACTUAL_HEIGHT - 10 ) / 5; $y > 0; $y -= ( ACTUAL_HEIGHT - 10 ) / 5, $i += $this->mDataMax / 5 ) {
				// $water->Trace( "Vertical Graph Values", $i );
				$text = round( $i );
				$textwidth = imagefontwidth( 5 ) * strlen( $text );
				$widthdiff = MAXWIDTH - $textwidth;
				$x = 35;
				imagestring( $this->mIm, 5, $x + $widthdiff, $y, $text, $this->mGraphTextColor );
				$x += MAXWIDTH;
				$line_y = $y + imagefontheight( 5 ) / 2;
				while ( $x < $this->mGraphWidth ) {
					imageline( $this->mIm , $x , $line_y , $x + 10 , $line_y , $this->mGraphLinesColor );
					$x += 20;
				}
			}
			
	        // average line
			imageline( $this->mIm , $this->mGraphWidth - ACTUAL_WIDTH + 1 , ACTUAL_HEIGHT - ACTUAL_HEIGHT * $this->mDataAvg / $this->mDataMax , $this->mGraphWidth - 1 , ACTUAL_HEIGHT - ACTUAL_HEIGHT * $this->mDataAvg / $this->mDataMax , $this->mAverageLineColor );
			
			//display graph caption
	        $textwidth = imagefontwidth( 5 ) * strlen( $this->mCaption );
	        imagestring( $this->mIm , 5 , ACTUAL_WIDTH / 2 - $textwidth / 2 + GRAPH_WIDTH - ACTUALWIDTH , ACTUAL_HEIGHT + 15 , $this->mCaption, $this->mCaptionColor );
			
			// create image
			imagepng( $this->mIm );
		}
				
		private function Graph_Lines( $color ) {
			// draw actual graph lines
			$oldpageviews = $this->mData[ 0 ];
			for ( $i =  1 ; $i < count( $this->mData ) ; ++$i ) {
				$pageviews = $this->mData[ $i ];
				$x1 = $this->mGraphWidth - ACTUAL_WIDTH + ( $i - 1 ) * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$x2 = $this->mGraphWidth - ACTUAL_WIDTH + $i * ACTUAL_WIDTH / (count( $this->mData ) - 1);
				$y1 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $oldpageviews / $this->mDataMax );
				$y2 = ACTUAL_HEIGHT - ( ( ACTUAL_HEIGHT - 16 ) * $pageviews / $this->mDataMax );
				imageline( $this->mIm, $x1, $y1, $x2, $y2, $color );
				$oldpageviews = $pageviews;
			}
		}
	}
	
	*/
?>
