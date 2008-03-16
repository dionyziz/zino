<?php
    // TODO: remove this file, replace with WYSIWYG + XHTML/XML validation
    
	// merlin magic parsing script	
	function mformatstories( $sources, $showemoticons = true ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		if ( $showemoticons !== false 
                 && ( $showemoticons === true || $showemoticons[ $i ] ) ) {
    			$sources[ $i ] = smileys( $sources[ $i ] );
    		}
        }
        $outputs = magik_multi( $sources );
        foreach ( $outputs as $i => $output ) {
    		$outputs[ $i ] = nl2br( $outputs[ $i ] );	
    		$outputs[ $i ] = "<blockquote>" . $outputs[ $i ] . "</blockquote>";
        }
		return $outputs;
	}
	function mformatcomments( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		$sources[ $i ] = smileys( $sources[ $i ] );
        }
        $outputs = magik_multi( $sources );
        foreach ( $outputs as $i => $output ) {
            $outputs[ $i ] = nl2br( $outputs[ $i ] );	
        }
		return $outputs;
	}
	function mformatsigs( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		$sources[ $i ] = smileys( $sources[ $i ] );
        }
		$outputs = magik_multi( $sources );
		// notice: no nl2br
		return $outputs;
	}
	function mformatshouts( $sources ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys( $sources[ $i ] );
            $sources[ $i ] = nl2br( $sources[ $i ] );
        }
		return $outputs = $sources;
	}
	function mformatpms( $sources ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys( $sources[ $i ] );
        }
        $outputs = magik_multi( $sources );
        foreach ( $outputs as $i => $output ) {
            $outputs[ $i ] = nl2br( $outputs[ $i ] );
        }
		return $outputs;
	}
	function mformatanswers( $sources ) {
        if ( !is_array( $sources ) ) {
            return false;
        }

        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = htmlspecialchars( $sources[ $i ] );
            $sources[ $i ] = smileys( $sources[ $i ] );
        }
		return $outputs = $sources;
	}
	function mformatchats( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
        }
        $outputs = magik_multi( $sources );
		return $outputs;
	}
	function mformatsmallstories( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = clearmerlin( $sources[ $i ] );
    		$sources[ $i ] = strip_tags( $sources[ $i ] );
    		$sources[ $i ] = utf8_substr( $sources[ $i ], 0, 700 );
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		$sources[ $i ] = smileys( $sources[ $i ] );
        }
        
		return $outputs = $sources;
	}
	
	function mformatcommentsearches( $sources, $q ) {
        foreach ( $sources as $i => $source ) {
            $sources[ $i ] = clearmerlin( $sources[ $i ] );
            $sources[ $i ] = strip_tags( $sources[ $i ] );
    		if ( strlen( $sources[ $i ] ) < 80 ) {
                // skip
    		}
    		else {
                $pos = strpos( $sources[ $i ], $q );
                if ( $pos < 40 ) {
                    $sources[ $i ] = substr( $sources[ $i ], 0, 80 );
        		}
        		else {
        			$sources[ $i ] = substr( $sources[ $i ], $pos - 40, $pos + 40 );
        		}
            }
    		$outputs[ $i ] = preg_replace("/(" . preg_quote( $q , "/" ) . ")/i","<b>\\0</b>", htmlspecialchars( $sources[ $i ] ) );
        }
        
		return $outputs;
	}
	
	function mformatcategorydescs( $sources ) {
        foreach ( $sources as $i => $source ) {
    		$sources[ $i ] = htmlspecialchars( $sources[ $i ] );
    		$sources[ $i ] = smileys( $sources[ $i ] );
        }
		return $outputs = $sources;
	}
	
	function smileys( $text ) {		
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
	function magik_multi( $sources, $light = false ) {
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
                    merlinlookup( $tag, $light, $lookups );
    			}
    		}
        }
        
        merlinperformlookups( $lookups );
        
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
                    $rplc = merlintag( $tag, $light, $lookups );
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
	
	function merlin_valid( $src ) {
		$nextmerlin = 0;
		
		while ( ( $nextmerlin = strpos( $src , "[merlin:" , $nextmerlin ) ) !== false ) {
			$tagend = strpos( $src , "]" , $nextmerlin );
			if ( $tagend === false )
				return false;
			$tag = substr( $src , $nextmerlin , $tagend - $nextmerlin + 1 );
			$eqpos = strpos( $tag , "=" );
			if ( $eqpos === false ) {
				$tagname = substr( $tag , 1 , strlen( $tag ) - 2 );
				$tagval  = "";
			}
			else {
				$tagname = substr( $tag , 1 , $eqpos - 1 );
				$tagval  = substr( $tag , $eqpos + 1 , strlen( $tag ) - $eqpos - 2 );
			}
			switch ( $tagname ) {
				case "merlin:nomagic":
					$src1 = substr( $src , 0 , $nextmerlin );
					$src2 = substr( $src , $tagend + 1 );
					$src = $src1 . $src2;
					$nextmerlin = strpos( $src , "[merlin:/nomagic]" , $nextmerlin );
					if ( $nextmerlin === false )
						return false;
					$tagend = $nextmerlin + strlen("[merlin:/nomagic]");
				default:
			}
			$nextmerlin++;
		}
		return true;
	}
	
    function merlinperformlookups( &$lookups ) {
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
    
    function merlinlookup( $tag, $light = false, &$lookups ) {
        $parsedtag = merlinparsetag( $tag );
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
    
    function merlinparsetag( $tag ) {
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
    
	function merlintag( $tag, $light = false, $lookups = false ) {
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
                    return '<div style="text-align: center"><embed src="http://www.veoh.com/videodetails2.swf?permalinkId=' . $arguments . '&amp;id=anonymous&amp;player=videodetailsembedded&amp;videoAutoPlay=0" allowFullScreen="true" width="540" height="438" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></div>';
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
                    return '<div style="text-align:center"><object width="425" height="355"><param name="movie" value="http://www.youtube.com/v/' . htmlspecialchars( $arguments ) . '&amp;rel=1"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/' . htmlspecialchars( $arguments ) . '&amp;rel=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object></div>';
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
            	
            	if ( isset( $args[ 1 ] ) ) {
            		$class = $args[ 1 ];
            	}
            	else {
            		$class = 'ccimage';
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
                    
                	return '<img src="' . htmlspecialchars( $linkhref ) . '" alt="" class="' . $class . '" />';
                }
                $imageid = ( integer )$args[ 0 ];
                if ( isset( $lookups[ 'images' ][ $imageid ] ) ) {
            		$image = $lookups[ 'images' ][ $imageid ];
                }
                else {
                    $water->Notice( 'Image lookup åfailed', $lookups );
                }
            	if ( !isset( $image ) || !is_object( $image ) || !( $imageid > 0 ) || !$image->exists() ) {
            		if ( isset( $args[ 2 ] ) && $args[ 2 ] == "left" ) {
            			return "<img style=\"width:50px;height:50px\" class=\"$class\" src=\"" . $xc_settings[ 'staticimagesurl' ] . "anonymous.jpg\" alt=\"cc\" />";
            		}
            		return "<div style=\"text-align:center\"><img style=\"width:50px;height:50px\" class=\"$class\" src=\"" . $xc_settings[ 'staticimagesurl' ] . "anonymous.jpg\" alt=\"cc\" /></div>";
            	}
            	else {
            		$src_x = $image->Width();
            		$src_y = $image->Height();
            		if ( isset( $args[ 2 ] ) && $args[ 2 ] == "left" ) {
            			$style = 'width:' . $src_x . 'px;height:' . $src_y . 'px;';
                        ob_start();
            			Element( 'image', $image, $class , $style , '' , '' );
                        return ob_get_clean();
            		}
                    // else
                    ob_start();
                    Element( 'image', $image, $src_x, $src_y, $class, "background-image:url('" . $xc_settings[ 'staticimagesurl' ] . "anonymous.jpg')", '', '' );
                    return "<div style=\"text-align:center\">" . ob_get_clean() . "</div>";
            	}
            	
            	return $imagecode;
        }
	}
	
	function clearmerlin( $text ) { 
		$nextmerlin = 0;
		$src = $text;
		
		while ( ( $nextmerlin = strpos( $src , "[merlin:" , $nextmerlin ) ) !== false ) {
			$tagend = strpos( $src , "]" , $nextmerlin );
			if ( $tagend === false )
				break;
			$tag = substr( $src , $nextmerlin , $tagend - $nextmerlin + 1 );
			$rplc = "";
			$src1 = substr( $src , 0 , $nextmerlin );
			$nextmerlin = strlen( $src1 );
			$src2 = substr( $src , $tagend + 1 );
			$src = $src1 . $rplc . $src2;
		}
		
		return $src;
	}
?>
