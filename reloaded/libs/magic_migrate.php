<?php
    // TODO: remove this file, replace with WYSIWYG + XHTML/XML validation
    
	// merlin magic parsing script	
	function mformatstories_( $sources, $showemoticons = true ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		if ( $showemoticons !== false 
                 && ( $showemoticons === true || $showemoticons[ $i ] ) ) {
    			$sources[ $i ] = smileys_( $sources[ $i ] );
    		}
        }
        $outputs = magik_multi_( $sources );
        foreach ( $outputs as $i => $output ) {
    		$outputs[ $i ] = nl2br( $outputs[ $i ] );	
    		$outputs[ $i ] = $outputs[ $i ];
        }
		return $outputs;
	}
	function mformatcomments_( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		$sources[ $i ] = smileys_( $sources[ $i ] );
        }
        $outputs = magik_multi_( $sources );
        foreach ( $outputs as $i => $output ) {
            $outputs[ $i ] = nl2br( $outputs[ $i ] );	
        }
		return $outputs;
	}
	function mformatshouts_( $sources ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys_( $sources[ $i ] );
            $sources[ $i ] = nl2br( $sources[ $i ] );
        }
		return $outputs = $sources;
	}
	function mformatpms_( $sources ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys_( $sources[ $i ] );
        }
        $outputs = magik_multi_( $sources );
        foreach ( $outputs as $i => $output ) {
            $outputs[ $i ] = nl2br( $outputs[ $i ] );
        }
		return $outputs;
	}
	function mformatanswers_( $sources ) {
        if ( !is_array( $sources ) ) {
            return false;
        }

        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys_( $sources[ $i ] );
        }
		return $outputs = $sources;
	}
	function smileys_( $text ) {		
		static $smileys = array( 
					  ":D" => "teeth" ,
					  ":-)" => "smile" ,
					  ":)" => "smile" ,
					  ";-)" => "wink" , 
					  // ";)" => "wink" ,
                      ":P" => "tongue",
                      ":p" => "tongue",
                      ":-P" => "tongue",
                      ":-p" => "tongue",
					  ":-D" => "teeth" ,
					  ":-S" => "confused" ,
					  ":S" => "confused" ,
					  ":'(" => "cry" , 
					  ":angel:" => "innocent" ,
					  ":angry:" => "angry" , 
					  ":bat:" => "bat" ,
					  ":beer:" => "beer" ,
					  ":cake:" => "cake" ,
					  ":photo:" => "camera" ,
					  ":cat:" => "cat" ,
					  ":clock:" => "clock" ,
					  ":drink:" => "cocktail" ,
					  ":cafe:" => "cup" ,
					  ":666:" => "devil" ,
					  ":evil:" => "devil" ,
					  ":dog:" => "dog" ,
					  ":mail:" => "email" ,
					  ":email:" => "email" ,
					  ":e-mail:" => "email" ,
					  "^^Uu" => "embarassed" ,
					  ":film:" => "film" ,
					  ":smooch:" => "kiss" ,
					  ":idea:" => "lightbulb" ,
					  "LOL" => "lol" ,
					  ":phone:" => "phone" ,
					  ":cool:" => "shade" ,
					  ":no:" => "thumbs_down" ,
					  ":yes:" => "thumbs_up" ,
					  ":yuck:" => "tongue" ,
					  ":heartbroken:" => "unlove" ,
					  ":unlove:" => "unlove" ,
					  ":hate:" => "unlove" ,
					  ":rose:" => "wilted_rose" ,
					  ":star:" => "star" ,
					  ":X" => "uptight" ,
					  ":gift:" => "present" ,
					  ":present:" => "present" ,
					  ":love:" => "love" ,
					  ":heart:" => "love" ,
					  ":music:" => "note" ,
					  ":note:" => "note" ,
					  ":airplane:" => "airplane" , 
					  ":boy:" => "boy" ,
					  ":car:" => "car" ,
					  ":smoke:" => "cigarette" ,
					  ":computer:" => "computer" , 
					  ":girl:" => "girl" ,
					  ":-I" => "indifferent" ,
					  ":-|" => "indifferent" ,
					  ":island:" => "ip" ,
					  ":!!:" => "lightning" ,
					  ":sms:" => "mobile_phone" ,
					  ":wow:" => "omg" ,
					  ":-(" => "sad" ,
					  ":sheep:" => "sheep" ,
					  ":@:" => "snail" ,
					  ":ball:" => "soccer" , 
					  ":kaboom:" => "storm" ,
					  ":sun:" => "sun" ,
					  ":turtle:" => "turtle" ,
					  ":?:" => "thinking" ,
					  ":umbrella:" => "umbrella" ,
					  ":~:" => "ugly" ,
					  ":::" => "empty" );
        static $smileysprocessed = false;
        static $smileysprocessedkeys = false;
        global $xc_settings;
        global $water;
        
        if ( $smileysprocessed === false ) {
            foreach ( $smileys as $i => $smiley ) {
                $smileysprocessed[ $i ] = '<img src="' 
                                        . $xc_settings[ 'staticimagesurl' ] 
                                        . 'emoticons/' 
                                        . $smiley 
                                        . '.png" alt="' 
                                        . htmlspecialchars( $i ) 
                                        . '" title="' 
                                        . htmlspecialchars( ucfirst( $smiley ) ) 
                                        . '" class="emoticon" style="width:22px;height:22px;" />';
            }
            $smileysprocessedkeys = array_keys( $smileysprocessed );
        }
        
        return str_replace( $smileysprocessedkeys, $smileysprocessed, $text );
	}
	function magik_multi_( $sources, $light = false ) {
		global $water;
		
		$water->Profile( 'Magik' );
		
        $lookups = array(
            'articles' => array(),
            'images' => array(),
            'users' => array()
        );
        
        // first pass
        foreach ( $sources as $src ) {
    		$nextmerlin = -1;
    		while ( $nextmerlin + 1 <= strlen( $src ) && ( $nextmerlin = strpos( $src , "[merlin:" , $nextmerlin + 1 ) ) !== false ) {
    			$tagend = strpos( $src , "]" , $nextmerlin );
    			if ( $tagend === false ) {
    				break;
    			}
    			$tag = substr( $src , $nextmerlin , $tagend - $nextmerlin + 1 );
    			if ( $tag == "[merlin:nomagic]" ) {
    				$nextmerlin = strpos( $src , "[merlin:/nomagic]" , $nextmerlin ) - 1;
    			}
    			else {
                    merlinlookup_( $tag, $light, $lookups );
    			}
    		}
        }
        
        merlinperformlookups_( $lookups );
        
        // second pass
        foreach ( $sources as $i => $src ) {
    		$nextmerlin = -1;
    		while ( $nextmerlin + 1 <= strlen( $src ) && ( $nextmerlin = strpos( $src , "[merlin:" , $nextmerlin + 1 ) ) !== false ) {
    			$tagend = strpos( $src , "]" , $nextmerlin );
    			if ( $tagend === false ) {
    				break;
    			}
    			$tag = substr( $src , $nextmerlin , $tagend - $nextmerlin + 1 );
    			if ( $tag == "[merlin:nomagic]" ) {
    				$nextmerlin = strpos( $src , "[merlin:/nomagic]" , $nextmerlin ) - 1;
                    $src1 = substr( $src , 0 , $nextmerlin );
                    $src2 = substr( $src , $tagend + 1 );
                    $src = $src1 . $src2;
    			}
                else if ( $tag == "[merlin:/nomagic]"  ) {
                    $src1 = substr( $src , 0 , $nextmerlin );
                    $src2 = substr( $src , $tagend + 1 );
                    $src = $src1 . $src2;
                }
                else {
                    $rplc = merlintag_( $tag, $light, $lookups );
                    $src1 = substr( $src , 0 , $nextmerlin );
                    $src2 = substr( $src , $tagend + 1 );
                    $src = $src1 . $rplc . $src2;
                    $nextmerlin = strlen( $src1 . $rplc ) - 1;
                }
            }
            $sources[ $i ] = $src;
        }
        
		$water->ProfileEnd();
		
		return $sources;
	}
	
    function merlinperformlookups_( &$lookups ) {
        global $libs;
        global $water;
        
        if ( count( $lookups[ 'articles' ] ) ) {
            $libs->Load( 'article' );
            $lookups[ 'articles' ] = Article_ById( array_keys( $lookups[ 'articles' ] ) );
        }
        if ( count( $lookups[ 'users' ] ) ) {
            $lookups[ 'users' ] = User_ByUsername( array_keys( $lookups[ 'users' ] ) );
        }
        if ( count( $lookups[ 'images' ] ) ) {
            $libs->Load( 'image/image' );
            $lookups[ 'images' ] = Image_ById( array_keys( $lookups[ 'images' ] ) );
        }
        $water->Trace( 'Looked up merlin', $lookups );
    }
    
    function merlinlookup_( $tag, $light = false, &$lookups ) {
        $parsedtag = merlinparsetag_( $tag );
        $arguments = $parsedtag[ 'arguments' ];
        $args = $parsedtag[ 'args' ];
        $templatename = $parsedtag[ 'templatename' ];

        switch ( $templatename ) {
            case 'article':
            	if ( isset( $args[ 0 ] ) ) {
                    $lookups[ 'articles' ][ $articleid = $args[ 0 ] ] = false;
                }
                break;
            case 'img':
            	if ( isset( $args[ 0 ] ) && is_numeric( $args[ 0 ] ) ) {
                    $lookups[ 'images' ][ $imageid = $args[ 0 ] ] = false;
                }
                break;
            case 'icon':
            case 'user':
                if ( isset( $args[ 0 ] ) ) {
                    $lookups[ 'users' ][ $username = $args[ 0 ] ] = false;
                }
        }
    }
    
    function merlinparsetag_( $tag ) {
		$eqpos = strpos( $tag , " " );
		$semipos = strpos( $tag , ":" );
		if ( $eqpos === false ) {
			$eqpos = $semipos;
		}
		else {
			$eqpos = (( $semipos < $eqpos ) ? $semipos : $eqpos );
		}
		$tagname = substr( $tag , 1 , $eqpos - 1 );
		$tagval  = substr( $tag , $eqpos + 1 , strlen( $tag ) - $eqpos - 2 );
		$spacepos = strpos( $tagval , " " );
		if ( $spacepos === false ) {
			$spacepos = strlen( $tagval );
		}
		$templatename = substr( $tagval , 0 , $spacepos );
		$arguments = substr( $tagval , $spacepos + 1 );
		$args = split( "\|" , $arguments );
        
        return array(
            'templatename' => $templatename,
            'arguments'    => $arguments,
            'args'         => $args
        );
    }
    
	function merlintag_( $tag, $light = false, $lookups = false ) {
        global $libs;
        global $db;
        global $users; 
        global $images;
        global $xc_settings;
        global $water;
        
		if ( $light && !( $templatename == "emphasis" || $templatename == "link" || $templatename == "italics" ) ) {
			return '';
		}
        $parsedtag = merlinparsetag( $tag );
        $arguments = $parsedtag[ 'arguments' ];
        $args = $parsedtag[ 'args' ];
        $templatename = $parsedtag[ 'templatename' ];
        switch ( $templatename ) {
            case 'emphasis':
                return "<b>" . htmlspecialchars( $arguments ) . "</b>";
            case 'italics':
                return "<i>" . htmlspecialchars( $arguments ) . "</i>";
            case 'yt':
                // robustness
                if ( substr( $arguments, 0, strlen( 'http://www.veoh.com/' ) ) == 'http://www.veoh.com/' ) {
                    // veoh
                    $arguments = substr( $arguments, strrpos( $arguments, '/' ) + 1 );
                    if ( !preg_match( '#^[a-zA-Z0-9_\-]*$#', $arguments ) ) {
                        return '(invalid video link)';
                    }
                    return '<embed src="http://www.veoh.com/videodetails2.swf?permalinkId=' . $arguments . '&amp;id=anonymous&amp;player=videodetailsembedded&amp;videoAutoPlay=0" allowFullScreen="true" width="540" height="438" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
                }
                else {
                    // youtube
                    if ( strpos( $arguments, '?' ) !== false ) {
                        $arguments = substr( $arguments, strpos( $arguments, '?' ) );
                    }
                    $explode = explode( '&', $arguments );
                    if ( count( $explode ) == 1 ) {
                        $arguments = $explode[ 0 ];
                    }
                    else {
                        $found = false;
                        foreach ( $explode as $argument ) {
                            if ( substr( $argument, 0, 2 ) == 'v=' ) {
                                $arguments = substr( $argument, 2 );
                                $found = true;
                                break;
                            }
                        }
                        if ( !$found ) {
                            $arguments = $explode[ 0 ];
                        }
                    }
                    if ( strpos( $arguments, '=' ) !== false ) {
                        $arguments = substr( $arguments, strpos( $arguments, '=' ) + 1 );
                    }
                    if ( !preg_match( '#^[a-zA-Z0-9_\-]*$#', $arguments ) ) {
                        return '(invalid video link)';
                    }
                    return '<object width="425" height="355"><param name="movie" value="http://www.youtube.com/v/' . htmlspecialchars( $arguments ) . '"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/' . htmlspecialchars( $arguments ) . '" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object>';
                }
            case 'article':
            	$id = isset( $args[ 0 ] ) ? $args[ 0 ] : 0;
                if ( !isset( $lookups[ 'articles' ][ $id ] ) ) {
                    return '';
                }
                // else
                $args[ 0 ] = "?p=story&id=$id";
                /* fallthru */
            case 'link':
            	$linkhref = $args[ 0 ];
            	$linkname = isset( $args[ 1 ] ) ? $args[ 1 ] : "";
            	
            	if ( $linkname == "" ) {
            		$linkname = $linkhref;
            	}
            	
                if ( strpos( $linkhref, ':' ) !== false ) {
                    if (   substr( strtolower( $linkhref ), 0, strlen( 'http://' ) ) != 'http://'
                        && substr( strtolower( $linkhref ), 0, strlen( 'https://') ) != 'https://'
                        && substr( strtolower( $linkhref ), 0, strlen( 'ftp://'  ) ) != 'ftp://' ) {
                        $linkhref = '';
                    }
                }

                $linkhref = str_replace( "\n", "", $linkhref );
                
            	return '<a href="' . htmlspecialchars( $linkhref ) . '">' . htmlspecialchars( $linkname ) . '</a>';
            case 'icon':
                $iconsize = "";
                if ( isset( $args[ 1 ] ) && $args[ 1 ] == " small" )
                    $iconsize = "|small";
                
                $nolink = "";
                if ( isset( $args[ 1 ] ) && $args[ 1 ] == " nolink" ) {
                    $nolink = "|nolink";
                }
                
                $args[ 1 ] = '';
                $args[ 2 ] = "nospace$iconsize$nolink";
                /* fallthru */
            case 'user':
            	if ( !isset( $args[ 0 ] ) ) {
                    return '';
            	}
                if ( !isset( $lookups[ 'users' ][ $args[ 0 ] ] ) ) {
                    return '';
                }
                
                ob_start();
                Element( 'user/icon', $lookups[ 'users' ][ $args[ 0 ] ] );
                return ob_get_clean();
            case 'title':
                return '<h4>' . htmlspecialchars( $arguments ) . '</h4>';
            case 'img':
            	if ( !isset( $args[ 0 ] ) ) {
                    return '';
            	}
            	
                if ( !is_numeric( $args[ 0 ] ) ) {
                    $linkhref = $args[ 0 ];
                    if ( strpos( $linkhref, ':' ) !== false ) {
                        if (   substr( strtolower( $linkhref ), 0, strlen( 'http://' ) ) != 'http://'
                            && substr( strtolower( $linkhref ), 0, strlen( 'https://') ) != 'https://'
                            && substr( strtolower( $linkhref ), 0, strlen( 'ftp://'  ) ) != 'ftp://' ) {
                            $linkhref = '';
                        }
                    }

                    $linkhref = str_replace( "\n", "", $linkhref );
                    
                	return '<img src="' . htmlspecialchars( $linkhref ) . '" alt="" />';
                }
                $imageid = ( integer )$args[ 0 ];
                if ( isset( $lookups[ 'images' ][ $imageid ] ) ) {
            		$image = $lookups[ 'images' ][ $imageid ];
                }
            	if ( !isset( $image ) || !is_object( $image ) || !( $imageid > 0 ) || !$image->exists() ) {
            		return "<img width=\"50\" height=\"50\" src=\"" . $xc_settings[ 'staticimagesurl' ] . "anonymous.jpg\" alt=\"\" />";
            	}
                $src_x = $image->Width();
                $src_y = $image->Height();
                ob_start();
                ?><div><?php
                Element( 'image_migrate', $image, $src_x, $src_y );
                ?></div><?php
                return ob_get_clean();
        }
	}
?>
