<?php
/*
Copyright (c) 2006 - 2007, Dionysis Zindros <dionyziz@gmail.com> and Aleksis Brezas <abresas@gmail.com>
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
	* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
	* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
	* Neither the names of Dionysis Zindros and Aleksis Brezas nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
	// Water version 4/PHP5/XC
	
    require 'libs/water/json.php';
    
	function Water_HandleError( $errno , $errstr , $errfile , $errline , $errcontext ) {
		global $water;
		
		if ( !$water->AlreadyHandled( $errno, $errstr, $errfile, $errline ) ) {
			$water->HandleError( $errno , $errstr ); // the file, line, and context can be obtained by the calltrace
		}
	}
    function Water_HandleException( Exception $exception ) {
        global $water;
        
        $water->HandleException( $exception );
    }
	function w_assert( $condition, $reason = '' ) {
		global $water;
		
		$water->Assert( $condition, $reason );
	}
	
	final class Water {
		private $mOutputAlerts;
		private $mOutputProfiles;
		private $mOutputSQL;
		private $mSQLStartTime;
		private $mSQLQuery;
        private $mNumSQL;
		private $mNumTraces;
		private $mNumNotices;
		private $mNumWarnings;
		private $mNumErrors;
		private $mProfiles;
		private $mProfilesStack;
		private $mProfilesParented;
		private $mCurrentProfileIndex;
		private $mDebugEnabled;
		private $mSettings;
		private $mHandledErrors;
		private $mQuickDebugRequest;
		
		private function QuickDebugRequested() {
			return $this->mQuickDebugRequest;
		}
		public function SetSetting( $key , $value ) {
			$this->mSettings[ $key ] = $value;
		}
		public function Trace( $message, $dump = false ) {
			$this->HandleError( E_USER_TRACE , $message, $dump, debug_backtrace() );
		}
		public function Notice( $message, $dump = false ) {
			$this->HandleError( E_USER_NOTICE , $message, $dump, debug_backtrace() );
		}
		public function Warning( $message, $dump = false ) {
			$this->HandleError( E_USER_WARNING , $message, $dump, debug_backtrace() );
		}
		public function Error( $message, $dump = false ) {
			$this->HandleError( E_USER_ERROR , $message, $dump, debug_backtrace() );
		}
		public function Profile( $algorithm ) {
			if ( !$this->mDebugEnabled ) {
				return;
			}
			++$this->mCurrentProfileIndex;
			$profile = array(
				'index' => $this->mCurrentProfileIndex,
				'algorithm' => $algorithm,
				'start' => microtime(true)
			);
			array_push( $this->mProfilesStack , $profile );
		}
		public function ProfileEnd() {
			if ( !$this->mDebugEnabled ) {
				return;
			}
			if ( !count( $this->mProfilesStack ) ) {
				$this->Notice( 'Cannot $water->ProfileEnd(); profilling not started' );
				return;
			}
			$profile = array_pop( $this->mProfilesStack );
			$profile[ 'end' ] = microtime(true);
			$profile[ 'time' ] = $profile[ 'end' ] - $profile[ 'start' ];
			if ( !count( $this->mProfilesStack ) ) {
				$parent = 0;
			}
			else {
				$parent = $this->mProfilesStack[ count( $this->mProfilesStack ) - 1 ][ 'index' ];
			}
			$this->mOutputProfiles[ $parent ][] = $profile;
			
			return $profile[ 'time' ];
		}
		public function LogSQL( $query ) { // linear, cannot be nested
			if ( !$this->mDebugEnabled ) {
				return;
			}
			// $this->mSQLQuery = substr( $query , 0 , $this->mSettings[ 'sqlmaxlength' ] );  There is no point in displaying only part of the query -- Aleksis
			$this->mSQLQuery = $query;
			$this->mSQLStartTime = microtime(true);
		}
		public function LogSQLEnd() {
			if ( !$this->mDebugEnabled ) {
				return;
			}
			$endtime = microtime(true);
            ++$this->mNumSQL;
            if ( $this->mNumSQL > $this->mSettings[ 'loglimit' ] ) {
                return;
            }
            $this->mOutputSQL[] = array(
                'query' => $this->mSQLQuery,
                'start' => $this->mSQLStartTime,
                'end' => $endtime,
                'time' => $endtime - $this->mSQLStartTime,
                'calltrace' => $this->AlertCalltrace()
            );
		}
		public function AlreadyHandled( $errno, $errstr, $errfile, $errline ) {
			if ( isset( $this->mHandledErrors[ $errno . ":" . $errstr ][ $errline . ":" . $errfile ] ) ) {
				return true;
			}
			else {
				$this->mHandledErrors[ $errno . ":" . $errstr ][ $errline . ":" . $errfile ] = true;
			}
		}
		public function Water() {
			// to be filled in by notices/errors
			$this->mJson = '';
			$this->mNumTraces   = 0;
			$this->mNumNotices  = 0;
			$this->mNumWarnings = 0;
			$this->mNumErrors   = 0;
            $this->mNumSQL      = 0;
			$this->mCurrentProfileIndex = 0;
			$this->mDebugEnabled = false;
			$this->mProfilesStack = array();
			$this->mOutputSQL = array();
			$this->mOutputProfiles = array();
			$this->mOutputAlerts = array();
			
			if ( isset( $_GET[ 'water_quickdebug' ] ) ) {
				$this->mQuickDebugRequest = true;
			}
			else {
				$this->mQuickDebugRequest = false;
			}
            
            // default settings
        	$this->SetSetting( 'window_url'   , 'water/debug.php' );
        	$this->SetSetting( 'images_url'   , 'water/images/' );
        	$this->SetSetting( 'css_url'      , 'water/css/water.css' );
        	$this->SetSetting( 'server_root'  , '/var/www/localhost/htdocs' );
        	$this->SetSetting( 'calltraces'   , true );
            $this->SetSetting( 'calltracelvl' , 1 );
        	$this->SetSetting( 'loglevel'     , 0 ); // traces and up
            $this->SetSetting( 'loglimit'     , 400 );
            $this->SetSetting( 'strict'       , true );
            $this->SetSetting( 'sqlmaxlength' , 1000 );
			$this->SetSetting( 'maxstring'    , 1200 );
            $this->SetSetting( 'bottomdebug'  , true );
		}
		public function Enable() {
			$this->mDebugEnabled = true;
		}
		public function Disable() {
			$this->mDebugEnabled = false;
		}
		public function Enabled() {
			return $this->mDebugEnabled;
		}
		private function AppendAlert( $errno, $errstr, $errdump, $backtrace = false ) {
			$functions = $this->get_php_functions();
			$dump = w_json_encode( $errdump , $this->mSettings[ 'maxstring' ] );
			$alert = array(
				'id' => $errno, 
				'description' => $errstr, 
				'calltrace' => $this->AlertCalltrace( $backtrace )
			);
			
			if ( $errdump !== false ) {
				$alert[ 'dump' ] = $errdump;
			}

			$this->mOutputAlerts[] = $alert;
		}
        private function AlertCalltrace( $backtrace = false )
        {
            $calltrace = array();
			if ( $this->mSettings[ 'calltraces' ] ) {
				$lastword = $this->callstack_lastword( $backtrace );
			}
			else {
				$lastword = array();
			}

            $i = 0;
			while ( $call = array_shift( $lastword ) ) {
				$phpfunction = isset( $functions[ $call[ 'function' ] ] ); // if this is a php built-in function.
				if ( $call['file'] == '<water>' ) { // skip water functions
					continue;
				}
				if ( !isset( $call['class'] ) ) {
					$call['class'] = '';
				}
				if ( isset( $call['args'] ) && is_array( $call['args'] ) ) {
					$args = $call[ 'args' ];
				}
				else {
					$args = array();
				}
				reset( $args );

				$calltrace[] = array(
                                      'file' => $call['file'], 
                                      'line' => $call['line'], 
                                      'class' => $call['class'], 
                                      'name' => $call['function'], 
                                      'depth' => $call['depth'], 
                                      'phpfunction' => $phpfunction, 
                                      'args' => $args 
                                  );
                ++$i;
                if ( $i >= $this->mSettings[ 'calltracelvl' ] ) {
                    break;
                }
			}
            
            return $calltrace;
        }
		public function DebugThis() {
			$this->GenerateJS();
			?><br /><a href="" onclick="Water.OpenWindow();return false;">Debug this</a><?php
			die();
		}
		public function GenerateHTML() {
			?><script type="text/javascript"><?php
			ob_start();
			
			$this->GenerateJS();
			
			if ( $this->mNumErrors ) {
				$errtype = 3;
			}
			else if ( $this->mNumWarnings ) {
				$errtype = 2;
			}
			else if ( $this->mNumNotices ) {
				$errtype = 1;
			}
			else if ( $this->mNumTraces ) {
				$errtype = 4;
			}
			else {
				$errtype = 0;
			}
			if ( $errtype ) {
				?>
				Water.HideGlobalWarningPrepare();
				<?php
			}
			$script = ob_get_clean();

            if ( $this->mSettings[ 'strict' ] ) {
                $script = htmlspecialchars( $script );
            }
            
			echo $script;
			?></script><?php
			if ( $errtype ) {
				?><div id="globalwarning" onmouseover="Water.HideGlobalWarningClear()" onmouseout="Water.HideGlobalWarningPrepare()" onclick="Water.OpenWindow();Water.HideGlobalWarning()" style="<?php
                if ( $this->mSettings[ 'bottomdebug' ] ) {
                    ?>bottom:0<?php
                }
                else {
                    ?>top:0<?php
                }
                ?>"><img src="<?php
				echo $this->mSettings[ 'images_url' ];
				switch ( $errtype ) {
					case 3:
						?>water_error_icon.png<?php
						break;
					case 2:
						?>water_warning_icon.png<?php
						break;
					case 1:
						?>water_notice_icon.png<?php
						break;
					case 4:
						?>water_trace_icon.png<?php
						break;
				}
				?>" alt="<?php
				switch ( $errtype ) {
					case 3:
						?>Errors<?php
						break;
					case 2:
						?>Warnings<?php
						break;
					case 1:
						?>Notices<?php
						break;
					case 4:
						?>Traces<?php
						break;
				}
				?>" /> This page has <?php
				switch ( $errtype ) {
					case 3:
						?>errors<?php
						break;
					case 2:
						?>warnings<?php
						break;
					case 1:
						?>notices<?php
						break;
					case 4:
						?>traces<?php
						break;
				}
				?>. To debug, click on this box.</div><?php
			}
		}
		public function GenerateJS() {
			// this is called at the end of processing
			
			ksort( $this->mOutputProfiles );
			
			$json = w_json_encode( array(
					'alerts' => $this->mOutputAlerts,
					'profiles' => $this->mOutputProfiles,
					'sql' => $this->mOutputSQL
				 )
				, $this->mSettings[ 'maxstring' ]
			);
			
			// for allowing stand-alone debugging, we can process the page and only send back the debug JSON
			// the permissions checks are done by the caller of this function so this is safe
			// the request is done using XMLHTTP
			if ( $this->QuickDebugRequested() ) { 
				ob_clean();
				die( $json );
			}
			?>
			water_debug_data = <?php
			echo $json;
			?>;
			water_debug_uri = "<?php
			echo $_SERVER[ "REQUEST_URI" ];
			?>";
			var Water = {
				OpenWindow: function () {
					var wOpen;
					var wOptions;
					
					wOptions = 'resizable=yes,  scrollbars=yes, toolbar=no, location=no, directories=no, status=no, menubar=no';
					wOptions += ',width=' + screen.availWidth.toString();
					wOptions += ',height=' + screen.availHeight.toString();
					wOptions += ',screenX=0,screenY=0,left=0,top=0';
					
					wOpen = window.open( '', 'waterwindow', wOptions );
					wOpen.location = "<?php
					echo $this->mSettings[ 'window_url' ];
					?>";
					wOpen.focus();
					wOpen.moveTo( 0, 0 );
				}
				,GlobalWarningTop: 0
				,GlobalWarningHideTimeout: null
				,onGlobalWarningShowAnimation: false
				,HideGlobalWarningPrepare: function () {
					Water.HideGlobalWarningClear();
					Water.GlobalWarningHideTimeout = setTimeout('Water.HideGlobalWarningAnimation()', 4000);
				}
				,HideGlobalWarningClear: function () {
					clearTimeout(Water.GlobalWarningHideTimeout);
				}
				,HideGlobalWarning: function () {
					Water.HideGlobalWarningAnimation();
				}
				,HideGlobalWarningAnimation: function () {
					Water.GlobalWarningTop -= 3;
					document.getElementById('globalwarning').style.<?php
                    if ( $this->mSettings[ 'bottomdebug' ] ) {
                        ?>bottom<?php
                    }
                    else {
                        ?>top<?php
                    }
                    ?> = Water.GlobalWarningTop + 'px';
					if ( Water.GlobalWarningTop < -18 ) {
						Water.GlobalWarningTop = -18;
						document.getElementById('globalwarning').style.<?php
                        if ( $this->mSettings[ 'bottomdebug' ] ) {
                            ?>bottom<?php
                        }
                        else {
                            ?>top<?php
                        }
                        ?> = Water.GlobalWarningTop + 'px';
						Water.onGlobalWarningShowAnimation = false;
						document.getElementById('globalwarning').onmouseover = Water.ShowGlobalWarningPrepare;
					}
                    else {
						setTimeout('Water.HideGlobalWarningAnimation()', 50);
					}
				}
				,ShowGlobalWarningPrepare: function () {
					if( Water.onGlobalWarningShowAnimation == false) {
						Water.onGlobalWarningShowAnimation = true;
						Water.ShowGlobalWarningAnimation();
					}
				}
				,ShowGlobalWarningAnimation: function () {
					Water.GlobalWarningTop += 3;
					document.getElementById('globalwarning').style.<?php
                    if ( $this->mSettings[ 'bottomdebug' ] ) {
                        ?>bottom<?php
                    }
                    else {
                        ?>top<?php
                    }
                    ?> = Water.GlobalWarningTop + 'px';
					if ( Water.GlobalWarningTop < 0 ) {
						setTimeout('Water.ShowGlobalWarningAnimation()', 50);
					}
					else {
                        document.getElementById('globalwarning').style.<?php
                        if ( $this->mSettings[ 'bottomdebug' ] ) {
                            ?>bottom<?php
                        }
                        else {
                            ?>top<?php
                        }
                        ?> = '0px';
						document.getElementById('globalwarning').onmouseout = Water.HideGlobalWarningPrepare;
					}
				}
			};
			<?php
		}
		public function HandleError( $errno, $errstr, $errdump = false, $backtrace = false ) {
			if ( !$this->mDebugEnabled ) {
				return;
			}
			switch ( $errno ) {
				case E_ERROR:
				case E_USER_ERROR:
					++$this->mNumErrors;
                    if ( $this->mSettings[ 'loglevel' ] > 3 || $this->mNumErrors > $this->mSettings[ 'loglimit' ] ) {
                        return;
                    }
					break;
				case E_WARNING:
				case E_USER_WARNING:
					++$this->mNumWarnings;
                    if ( $this->mSettings[ 'loglevel' ] > 2 || $this->mNumWarnings > $this->mSettings[ 'loglimit' ] ) {
                        return;
                    }
					break;
				case E_NOTICE:
				case E_USER_NOTICE:
				case E_STRICT:
					++$this->mNumNotices;
                    if ( $this->mSettings[ 'loglevel' ] > 1 || $this->mNumNotices > $this->mSettings[ 'loglimit' ] ) {
                        return;
                    }
					break;
				case E_USER_TRACE:
					++$this->mNumTraces;
                    if ( $this->mSettings[ 'loglevel' ] > 0 || $this->mNumTraces > $this->mSettings[ 'loglimit' ] ) {
                        return;
                    }
			}
			$this->AppendAlert( $errno, $errstr, $errdump, $backtrace );
		}
		private function HandleException( $exception, $data = false ) {
			// since there has been no try/catch pair, this is a fatal exception
			$this->FatalError( $exception->getMessage(), $data, $exception->getTrace() );
		}
		public function Assert( $expression, $reason = '' ) {
			if ( !$expression ) {
                $msg = 'Assertion failed';
                if ( !empty( $reason ) ) {
                    $msg .= ': ' . $reason;
                }
				throw New Exception( $msg );
			}
		}
		private function FatalError( $message, $data, $backtrace = false ) {
			global $page;
            
			if ( function_exists( 'UserIp' ) ) {
				$userip = UserIp();
			}
			else {
				$userip = '(unknown)';
			}
			if ( function_exists( 'NowDate' ) ) {
				$nowdate = NowDate();
			}
			else {
				$nowdate = '0000-00-00 00:00:00';
			}

			$requesturi = $_SERVER[ 'REQUEST_URI' ];

			$level = ob_get_level();
			for ( $i = 0 ; $i < $level ; ++$i ) {
				ob_end_clean();
            }
            
            $quotes = array(
                'To the strongest!',
                'Wait a minute...',
                'Am I dying, or is this my birthday?',
                'Death seeks us all',
                'Applaud, my friends, the comedy is finished.',
                'Todo mortal...',
                'No.',
                'What is this?',
                'Deem-me cafe, vou escrever!',
                'Ah, that tastes nice. Thank you.',
                'Vivo!',
                'I\'m bored with it all.',
                'So here it is!',
                'That was the best ice-cream soda I ever tasted.',
                'Where is my clock?',
                'On the ground!',
                'But how the devil do you think this could harm me?',
                'Hit the water!...Hit the water!...Hit the water!...',
                'Shakespeare, I come!',
                'Adieu, mes amis, Je vais a la gloire!',
                'Was ist mit mir geschehen?',
                'Ich sterbe.',
                'Es ist gar nichts... es ist gar nichts...',
                'I\'d hate to die twice. It\'s so boring.',
                'Das gute Essen',
                'Kiss my ass.',
                'Bakayaro! Bakayaro!',
                'Goodbye.',
                'This is funny.',
                'Tvert imot!',
                'Don\'t worry... it\'s not loaded...',
                'No, you certainly can\'t.',
                'Beautiful.',
                'I think I\'m going to make it!',
                'Cheerio!',
                'Pee pee.',
                'It tastes bad.',
                'Excuse all the blood.',
                'Me l\'aspettavo.',
                'It\'s good.',
                'No, I\'m not!',
                'Go away. I\'m all right.',
                'Leave me alone - I\'m fine.',
                'Tell them I had a wonderful life.',
                'Already?'
            );
            
            $quote = array_rand( $quotes );
            
            if ( $page instanceof PageHTML ) {
                $this->die_html( $message, $data, $this->mSettings[ 'calltracelvl' ] > 0, $quotes[ $quote ], $backtrace );
            }
            else {
                $this->die_plaintext( $message, $data, $this->mSettings[ 'calltracelvl' ] > 0, $quotes[ $quote ], $backtrace );
            }
			exit();
		}
        private function die_plaintext( $errmessage, $data, $trace, $quote, $backtrace = false ) {
            if ( !headers_sent() ) {
                header( 'Content-type: text/plain' );
            }
            
            ?>Water: Unhandled Exception.<?php
            echo "\n\n";
            echo $errmessage;
            echo "\n";
            if ( !empty( $data ) ) {
                echo "\n--------------------\n";
                print_r( $data );
                echo "\n--------------------\n";
            }
            if ( $trace ) {
                echo "\n";
                echo $this->DumpTrace( $backtrace );
                echo "\n";
            }
            echo "\n";
            echo $quote;
        }
        private function die_html( $errmessage, $data, $trace, $quote ) {
            if ( !headers_sent() ) {
                header( 'Content-type: text/xml' );
            }
            
            echo "<?xml version='1.0'?>\n";
			?>
            <html xmlns="http://www.w3.org/1999/xhtml" 
                  xmlns:svg="http://www.w3.org/2000/svg">
                <head>
                    <title>Water: Unhandled Exception</title>
                    <style>
                        html {
                            width: 100%;
                            height: 100%;
                        }
                        body {
                            background-color: lightyellow;
                            width: 100%;
                            height: 100%;
                            margin: 0;
                            padding: 0;
                            font-family: Verdana;
                            font-size: 80%;
                        }
                        h1 {
                            display: block;
                            font-size: 150%;
                            margin: 0;
                            padding: 0;
                            color: red;
                            font-weight: normal;
                        }
                        div.report {
                            padding: 0 auto 0 auto;
                            margin: 0 auto 0 auto;
                            width: 700px;
                            text-align: center;
                            height: 100%;
                            background-color: white;
                            border-right: 1px solid #cecece;
                            border-left: 1px solid #cecece;
                            position: relative;
                        }
                        div.errmsg {
                            font-weight: bold;
                            margin-bottom: 20px;
                        }
                        table.callstack {
                            text-align: left;
                            border-collapse: collapse;
                            border: 1px solid #5599cc;
                            margin: auto;
                            font-size: 100%;
                        }
                        table.callstack tr {
                            background-color: #daf0ff;
                        }
                        table.callstack tr.title {
                            background-color: #aaccff;
                            border-bottom: 1px solid #5599cc;
                        }
                        table.callstack tr.title td {
                            padding: 5px;
                        }
                        table.callstack tr td {
                            padding: 3px;
                        }
                        div.quote {
                            position: absolute;
                            bottom: 0px;
                            left: 0px;
                            right: 0px;
                            padding-top: 5px 0 0 10px;
                            background-color: #cecece;
                            font-style: italic;
                        }
                    </style>
                </head>
                <body>
                    <div class="report"><br />
                        <div class="log">
                            <h1>Unhandled Water Exception</h1><br />
                            <svg:svg width="20px" height="20px" viewBox="30 10 180 360"><svg:path
                               style="fill:#3080ff;stroke:#253bda;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
                               d="M 60.445708,15.5 C 60.445708,15.5 90.445708,100.5 45.445708,180.5 C 8.5486677,246.09474 25.445708,295.5 35.445708,315.5 C 64.514588,373.63777 170,365 190.4457,305.5 C 210.99887,245.68723 209.06791,170.62949 60.445708,15.5 z " />
                            </svg:svg>
                        </div>
                        <br /><br />
                        <div class="errmsg"><?php
        					echo $errmessage;
                        ?></div><?php
                        if ( !empty( $data ) ) {
                            ?><br /><i><?php
                            print_r( $data );
                            ?></i><br /><?php
                        }
                        if ( $trace ) {
                            echo $this->DumpTrace( $backtrace );
                        }
                        ?><br />
                        <div class="quote"><?php
                            echo $quote;
                        ?></div>
                    </div>
                </body>
            </html><?php
        }
		private function DumpTrace( $backtrace = false ) {
			ob_start();
            
			$this->callstack_dump_lastword( $backtrace );
            
			return ob_get_clean();
		}
		private function get_all_functions() {
			static $memo;
			
			if ( !isset( $memo ) ) {
				$memo = get_defined_functions();
			}
			return $memo;
		}
		private function get_php_functions() {
			static $memo;
	
			if ( !isset( $memo ) ) {
				$allfunctions = $this->get_all_functions();
				$phpfunctions = $allfunctions['internal'];
				$phpfunctions[] = 'include';
				$phpfunctions[] = 'include_once';
				$phpfunctions[] = 'require';
				$phpfunctions[] = 'require_once';

				foreach ($phpfunctions as $function) {
					$map[ $function ] = true;
				}
				$memo = $map;
			}
			
			return $memo;
		}
		private function callstack_lastword( $backtrace = false ) {
            if ( $backtrace === false ) {
                $backtrace = debug_backtrace();
			}
            
			$i = count( $backtrace ) - 1;
			foreach ( $backtrace as $call ) {
				if ( !isset( $call[ 'file' ] ) ) {
					$call[ 'file' ] = '';
				}
				if ( strlen( $call[ 'file' ] ) < strlen( $this->mSettings[ 'server_root' ] ) ) {
					$lastword[ $i ][ 'revision' ] = phpversion();
					$lastword[ $i ][ 'file' ] = '(unknown)';
					$lastword[ $i ][ 'line' ] = '-';
				}
				else {
					$lastword[ $i ][ 'file' ] = $this->chopfile( $call[ 'file' ] );
					$lastword[ $i ][ 'line' ] = $call[ 'line' ];
				}
				if ( isset( $call[ 'class' ] ) ) {
					$lastword[ $i ][ 'class' ] = $call[ 'class' ];
				}
				$lastword[ $i ][ 'function' ] = $call[ 'function' ];
				$lastword[ $i ][ 'depth' ] = 0;
				if( isset($call[ 'args' ]) ) {
					$lastword[ $i ][ 'args' ] = $call[ 'args' ];
				}
				if ( isset( $call[ 'type' ] ) ) {
					$lastword[ $i ][ 'calltype' ] = $call[ 'type' ];
				}
				--$i;
			}
			
			return $lastword;
		}
        private function callstack_plaintext( $callstack ) {
            $functions = $this->get_php_functions();
            
			$calltrace_depth = 0;
            $out = array();
            $maxfunction = 0;
            $maxsource   = 0;
            $maxline     = 0;
			for ( $i = count( $callstack ) - 1 ; $i >= 0 ; --$i ) {
				if ( isset( $callstack[ $i ] ) ) {
					$info = $callstack[ $i ];
					$file = $info[ 'file' ]; // should already have been chopped for us
					if ( $file == '<water>' ) {
						// avoid tracing water calls
						continue;
					}
					++$calltrace_depth;
                    $me[ 'function' ] = '';
                    echo "\n";
					if ( isset( $info[ 'depth' ] ) ) {
						$me[ 'function' ] .= str_repeat( ' ' , $info[ 'depth' ] * 2 );
					}
					if ( !empty( $info[ 'class' ] ) ) {
						$me[ 'function' ] .= $info[ 'class' ];
						if ( !isset( $call[ 'type' ] ) ) {
							$call[ 'type' ] = '->';
						}
						switch ( $call[ 'type' ] ) {
							case '::':
								$me[ 'function' ] .= '::';
								break;
							case '->':
							default:
								$me[ 'function' ] .= '->';
								break;
						}
					}
					if ( isset( $info[ 'function' ] ) ) {
						$phpfunction = isset( $functions[ $info[ 'function' ] ] );
						if ( $phpfunction ) {
                            $me[ 'function' ] .= '*';
						}
                        $me[ 'function' ] .= $info[ 'function' ];
						if ( $phpfunction ) {
							$me[ 'function' ] .= '*';
						}
					}
                    $me[ 'function' ] .= '(';
					if ( isset( $info[ 'args' ] ) ) {
						$j = 0;
						$numargs = count( $info[ 'args' ] );
						foreach ( $info[ 'args' ] as $arg ) {
							$me[ 'function' ] .= ' ';
							if ( is_object( $arg ) ) {
								$me[ 'function' ] .= '[object]';
							}
							else if ( is_null( $arg ) ) {
								$me[ 'function' ] .= '[null]';
							}
							else if ( is_resource( $arg ) ) {
								$me[ 'function' ] .= '[resource: ' . get_resource_type( $arg ) . ']';
							}
							else if ( is_array( $arg ) ) {
								$me[ 'function' ] .= '[array]';
							}
							else if ( is_scalar( $arg ) ) {
								if ( is_bool( $arg ) ) {
									if ( $arg ) {
										$me[ 'function' ] .= '[true]';
									}
									else {
										$me[ 'function' ] .= '[false]';
									}
								}
								switch ( $info[ 'function' ] ) {
									case 'include':
									case 'include_once':
									case 'require':
									case 'require_once':
										$me[ 'function' ] .= $this->chopfile( $arg );
										break;
									default:
										if ( is_string( $arg ) ) {
											$me[ 'function' ] .= '"';
										}
										$argshow = str_replace( array( "\n", "\r" ), ' ', substr( $arg , 0 , 30 ) );
										$me[ 'function' ] .= $argshow;
										if ( strlen( $arg ) > strlen( $argshow ) ) {
											$me[ 'function' ] .= '...';
										}
										if ( is_string( $arg ) ) {
											$me[ 'function' ] .= '"';
										}
								}
							}
                            $me[ 'function' ] .= ' ';
							++$j;
							if ( $j != $numargs ) {
								$me[ 'function' ] .= ',';
							}
						}
					}
					$me[ 'function' ] .= ')';
                    $me[ 'source' ] = '';
					if ( isset( $file ) ) {
						if ( $calltrace_depth == 1 ) {
							$me[ 'source' ] .= '*';
						}
						$me[ 'source' ] .= $file;
						if ( $calltrace_depth == 1 ) {
							$me[ 'source' ] .= '*';
						}
					}
					if ( isset( $info[ 'line' ] ) ) {
                        $me[ 'line' ] = $info[ 'line' ];
					}
					else {
                        $me[ 'line' ] = '-';
					}
                    if ( $maxsource < strlen( $me[ 'source' ] ) ) {
                        $maxsource = strlen( $me[ 'source' ] );
                    }
                    if ( $maxfunction < strlen( $me[ 'function' ] ) ) {
                        $maxfunction = strlen( $me[ 'function' ] );
                    }
                    if ( $maxline < strlen( $me[ 'line' ] ) ) {
                        $maxline = strlen( $me[ 'line' ] );
                    }
                    $out[] = $me;
                }
			}
            
			?>function<?php
            echo str_repeat( ' ', $maxfunction - strlen( 'function' ) + 2 );
            ?>source<?php
            echo str_repeat( ' ', $maxsource - strlen( 'source' ) + 2 );
            ?>line<?php
            
            echo "\n";
            echo str_repeat( '-', $maxfunction + $maxsource + $maxline + 6 );
            echo "\n";
            foreach ( $out as $me ) {
                echo $me[ 'function' ];
                echo str_repeat( ' ', $maxfunction - strlen( $me[ 'function' ] ) + 2 );
                echo $me[ 'source' ];
                echo str_repeat( ' ', $maxsource - strlen( $me[ 'source' ] ) + 2 );
                echo $me[ 'line' ];
                echo "\n";
			}
            echo str_repeat( '-', $maxfunction + $maxsource + $maxline + 6 );
        }
		private function callstack_html( $callstack ) {
			$functions = $this->get_php_functions();
	
			?><div class="watertrace"><table class="callstack"><tr class="title">
            <td class="title">function</td><td class="title">source</td><td class="title">line</td></tr><?php
			$calltrace_depth = 0;
			for ( $i = count( $callstack ) - 1 ; $i >= 0 ; --$i ) {
				if ( isset( $callstack[ $i ] ) ) {
					$info = $callstack[ $i ];
					$file = $info[ 'file' ]; // should already have been chopped for us
					if ( $file == '<water>' ) {
						// avoid tracing water calls
						continue;
					}
					++$calltrace_depth;
					?><tr><?php
					?><td class="function"><?php
					if ( isset( $info[ 'depth' ] ) ) {
						echo str_repeat( '&nbsp;' , $info[ 'depth' ] * 2 );
					}
					if ( !empty( $info[ 'class' ] ) ) {
						echo $info[ 'class' ];
						if ( !isset( $call[ 'type' ] ) ) {
							$call[ 'type' ] = '->';
						}
						switch ( $call[ 'type' ] ) {
							case '::':
								?>::<?php
								break;
							case '->':
							default:
								?>-&gt;<?php
								break;
						}
					}
					if ( isset( $info[ 'function' ] ) ) {
						$phpfunction = isset( $functions[ $info[ 'function' ] ] );
						if ( $phpfunction ) {
							?><a href="http://www.php.net/<?php
							echo $info[ 'function' ];
							?>"><?php
						}
						echo $info[ 'function' ];
						if ( $phpfunction ) {
							?></a><?php
						}
					}
					?>(<?php
					if ( isset( $info[ 'args' ] ) ) {
						$j = 0;
						$numargs = count( $info[ 'args' ] );
						foreach ( $info[ 'args' ] as $arg ) {
							?> <?php
							if ( is_object( $arg ) ) {
								?>[object]<?php
							}
							else if ( is_null( $arg ) ) {
								?>[null]<?php
							}
							else if ( is_resource( $arg ) ) {
								?>[resource: <?php
								echo get_resource_type( $arg );
								?>]<?php
							}
							else if ( is_array( $arg ) ) {
								?>[array]<?php
							}
							else if ( is_scalar( $arg ) ) {
								if ( is_bool( $arg ) ) {
									if ( $arg ) {
										?>[true]<?php
									}
									else {
										?>[false]<?php
									}
								}
								switch ( $info[ 'function' ] ) {
									case 'include':
									case 'include_once':
									case 'require':
									case 'require_once':
										echo htmlspecialchars( $this->chopfile( $arg ) );
										break;
									default:
										if ( is_string( $arg ) ) {
											?>"<?php
										}
										$argshow = substr( $arg , 0 , 30 );
										echo htmlspecialchars( $argshow );
										if ( strlen( $arg ) > strlen( $argshow ) ) {
											?>...<?php
										}
										if ( is_string( $arg ) ) {
											?>"<?php
										}
								}
							}
							?> <?php
							++$j;
							if ( $j != $numargs ) {
								?>,<?php
							}
						}
					}
					?>)</td><td class="file"><?php
					if ( isset( $file ) ) {
						if ( $calltrace_depth == 1 ) {
							?><b><?php
						}
						echo $file;
						if ( $calltrace_depth == 1 ) {
							?></b><?php
						}
					}
					?></td><td class="line"><?php
					if ( isset( $info[ 'line' ] ) ) {
						echo $info[ 'line' ];
					}
					else {
						?>-<?php
					}
					?></td></tr><?php
				}
			}
			?></table></div><?php
		}
		private function callstack_dump_lastword( $backtrace = false ) {
			$this->callstack( $this->callstack_lastword( $backtrace ) );
		}
        private function callstack( $callstack ) {
            global $page;
            
            if ( $page instanceof PageHTML ) {
                return $this->callstack_html( $callstack );
            }
            return $this->callstack_plaintext( $callstack );
        }
		private function chopfile( $filename ) {
            if ( $filename === __FILE__ ) {
                return '<water>';
            }
            
			$beginpath = $this->mSettings[ 'server_root' ];
			
			if ( strtolower( substr( $filename, 0, strlen( $beginpath ) ) ) == $beginpath ) {
				$ret = substr( $filename, strlen( $beginpath ) );
			}
			else {
				$ret = $filename;
			}
			
			if ( strtolower( substr( $ret, -4 ) ) == '.php' ) {
				$ret = substr( $ret, 0, strlen( $ret ) - 4 );
			}
			
			return $ret;
		}
	}

	if ( headers_sent() ) {
		die( 'The water library must be included before any content has been sent to the client.' );
	}
	
	define( 'E_USER_TRACE' , 16384 );
	
	if ( function_exists( 'Water_HandleError' ) ) {
		// turn water on
		set_error_handler( 'Water_HandleError' );
	}
    if ( function_exists( 'Water_HandleException' ) ) {
        set_exception_handler( 'Water_HandleException' );
    }
    
	error_reporting( E_ALL );

	return New Water(); // singleton
?>
