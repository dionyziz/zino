<?php
/*
	Developer: Dionyziz
*/

abstract class Page {
	protected $mTitle;
	protected $mBody;
	protected $mMainElements;
    protected $mNaturalLanguage;
    protected $mValidLanguages;
    protected $mDoWaterDump;
    protected $mBaseIncludePath;
    protected $mRedirection;
    protected $mOutputLevel;
    
    public function SetWaterDump( $enabled ) {
        $this->mDoWaterDump = $enabled;
    }
    public function SetBaseIncludePath( $baseincludepath ) {
        $this->mBaseIncludePath = $baseincludepath;
    }
    final public function SetNaturalLanguage( $languagecode ) {
        global $libs;
        global $water;
        
        $languagecode = strtolower( $languagecode );
        if ( !isset( $this->mValidLanguages ) ) {
            $this->mValidLanguages = $libs->Load( 'rabbit/page/xml-languages' );
        }
        if ( !in_array( $languagecode, $this->mValidLanguages ) ) {
            $water->Notice( 'Invalid IANA language code specified as the natural language of your page' );
            return;
        }
        $this->mNaturalLanguage = $languagecode;
    }
	final protected function OutputStart() {
		ob_start();
        // $this->mOutputLevel = ob_get_level();
	}
	final protected function OutputEnd() {
        /* 
        global $water;
        
        if ( ob_get_level() != $this->mOutputLevel ) {
            $water->ThrowException( 'Output buffering level is inconsistent (' . $this->mOutputLevel . '/' . ob_get_level() . ')' );
        } */
		echo ob_get_clean();
	}
	protected function GenerateBody() {
		global $water;
		global $elemental;
        
		$water->Profile( 'Render Page' );
		
		ob_start();
        foreach ( $this->mMainElements as $mainelement ) {
            $ret = $elemental->MainElement( $mainelement[ 'name' ], $mainelement[ 'req' ] );
            
            if ( $ret instanceof HTTPRedirection ) {
                $this->mRedirection = $ret;
            }
        }
		$this->mBody = ob_get_clean();
		
		$water->ProfileEnd();
	}
	public function Output() {
		global $water;
		global $libs;
		
		$water->Trace( $libs->CountLoaded() . ' libraries loaded before rendering' );
		$this->GenerateBody();
		
		$this->WaterLink();
		
        if ( $this->mRedirection instanceof HTTPRedirection ) {
            return $this->mRedirection->Redirect();
        }
        
		$this->OutputStart();
        $this->OutputPage();
		$this->OutputEnd();
		$water->Trace( $libs->CountLoaded() . ' libraries loaded after rendering' );
	}
    abstract protected function OutputPage();
	public function Page() {
        $this->mNaturalLanguage = 'en-US';
        $this->mMainElements = array();
	}
	public function Title() {
		return $this->mTitle;
	}
	public function SetTitle( $title ) {
		$this->mTitle = $title;
	}
	public function AttachMainElement( $mainelementid , $req ) {
        global $water;
        
        w_assert( is_array( $req ) );
        
		$this->mMainElements[] = array(
            'name' => $mainelementid,
            'req'  => $req
        );
	}
}

final class PageEmpty extends Page {
    protected function OutputPage() {
    }
    public function Output() {
        $this->OutputStart();
        $this->OutputEnd();
    }
}

final class PageHTML extends Page {
	private $mSupportsXML;
	private $mStylesheets;
	private $mScripts;
    private $mScriptsInline;
	private $mBase;
    private $mMeta;
    
    public function PageHTML() {
		$this->mElements      = array();
		$this->mScripts       = array();
        $this->mScriptsInline = array();
		$this->mStylesheets   = array();
        $this->mMeta          = array();
		$this->CheckXML();
        $this->Page();
    }
    public function XMLStrict() {
        return $this->mSupportsXML;
    }
    protected function OutputPage() {
		$this->OutputHeaders();
		$this->OutputHTMLStart();
		$this->OutputHTMLHeader();
		$this->OutputHTMLMain();
		$this->OutputHTMLEnd();
    }
	private function OutputHeaders() {
		if ( $this->mSupportsXML ) {
			header( "Content-Type: application/xhtml+xml; charset=utf-8" );
		}
		else {
			header( "Content-Type: text/html; charset=utf-8" );
		}
	}
	private function OutputHTMLStart() {
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php
        echo $this->mNaturalLanguage;
        ?>" lang="<?php
        echo $this->mNaturalLanguage;
        ?>"><?php
	}
	private function OutputHTMLHeader() {
		?><head><title><?php
			echo htmlspecialchars( $this->mTitle );
		?></title><?php
		if ( !empty( $this->mBase ) ) {
			?><base href="<?php
			echo $this->mBase;
			?>" /><?php
		}
        foreach ( $this->mStylesheets as $filename => $info ) {
            if ( $info[ 'ieversion' ] !== false ) {
                ?><!--[if IE]><?php
            }
            ?><link href="<?php
            echo $this->mBase;
            echo $filename;
            if ( file_exists( $this->mBaseIncludePath . '/' . $filename ) ) {
                ?>?<?php
                // force uncaching if necessary
                echo filemtime( $this->mBaseIncludePath . '/' . $filename );
            }
            ?>" rel="stylesheet" type="text/css" /><?php
            if ( $info[ 'ieversion' ] !== false ) {
                ?><![endif]--><?php
            }
        }
        foreach ( $this->mMeta as $name => $content ) {
            ?><meta name="<?php
            echo $name;
            ?>" content="<?php
            echo htmlspecialchars( $content );
            ?>" /><?php
        }
		foreach ( $this->mScripts as $script ) {
            if ( $script[ 'head' ] ) {
                $this->OutputScript( $script );
            }
		}
		?></head><?php
	}
    private function OutputScript( $script ) {
        w_assert( is_array( $script ) );
        
        if ( $script[ 'ieversion' ] != '' ) {
            ?><!--[if lt IE <?php
            echo $script[ 'ieversion' ];
            ?>]><?php
            echo "\n";
        }
        ?><script type="text/<?php
        echo $script[ 'language' ];
        ?>" src="<?php
        echo $script[ 'filename' ];
        if ( file_exists( $this->mBaseIncludePath . '/' . $script[ 'filename' ] ) ) {
            ?>?<?php
            // force uncaching if necessary
            echo filemtime( $this->mBaseIncludePath . '/' . $script[ 'filename' ] );
        }
        ?>"></script><?php
        if ( $script[ 'ieversion' ] != '' ) {
            ?><![endif]--><?php
            echo "\n";
        }
    }
	private function OutputHTMLMain() {
		ob_start( 'html_filter' );
		?><body><?php
		echo $this->mBody;
		foreach ( $this->mScripts as $script ) {
            if ( !$script[ 'head' ] ) {
                $this->OutputScript( $script );
            }
		}
        foreach ( $this->mScriptsInline as $language => $code ) {
            ?><script type="text/<?php
            echo $language;
            ?>"><?php
            if ( $this->mSupportsXML ) {
                echo htmlspecialchars( $code );
            }
            else {
                echo $code; // not good if it contains "</script>" etc; care should be taken
            }
            ?></script><?php
        }
		?></body><?php
		echo ob_get_clean();
	}
	private function OutputHTMLEnd() {
	   ?></html><?php
	}
	private function CheckXML() {
		$xmlmimetype = 'application/xhtml+xml';
		$accepted = explode( ',' , $_SERVER[ 'HTTP_ACCEPT' ] );
		if ( in_array( $xmlmimetype , $accepted ) ) {
			$this->mSupportsXML = true;
		}
		else {
			$this->mSupportsXML = false;
		}
	}
	public function SetBase( $base ) {
		$this->mBase = $base;
	}
    public function AddMeta( $name, $content ) {
        w_assert( preg_match( '#^[a-z]+$#', $name ) );
        $this->mMeta[ $name ] = $content;
    }
	public function AttachStylesheet( $filename, $ieversion = false ) {
		global $water;
		
		if ( !isset( $this->mStylesheets[ $filename ] ) ) {
			$water->Trace( 'Loading stylesheet ' . $filename );
			$this->mStylesheets[ $filename ] = array(
                'filename' => $filename,
                'ieversion' => $ieversion
            );
		}
	}
	public function AttachScript( $filename, $language = 'javascript', $head = false, $ieversion = '' ) {
		global $water;
		
		if ( !isset( $this->mScripts[ $filename ] ) ) {
			$water->Trace( 'Loading script ' . $filename );
			$this->mScripts[ $filename ] = array( 'language'  => $language, 
												  'filename'  => $filename, 
												  'ieversion' => $ieversion,
                                                  'head'      => $head );
		}
	}
    public function AttachInlineScript( $code, $language = 'javascript' ) {
        if ( !isset( $this->mScriptsInline[ $language ] ) ) {
            $this->mScriptsInline[ $language ] = '';
        }
        $this->mScriptsInline[ $language ] .= $code;
    }
	protected function WaterLink() {
		global $water;
        global $page;
        
		// keep in mind that profiles and alerts beyond this point will not be dumped
		if ( $this->mDoWaterDump ) {
            $water->SetSetting( 'strict', $page->XMLStrict() );
            ob_start();
            $water->GenerateHTML();
            $this->mBody = ob_get_clean() . $this->mBody;
            $this->AttachStylesheet( 'css/water.css' );
		}
	}

    /* DOM Handling Functions */
    public function appendChild( PageDOMEntity $element ) {
        echo $element;
    }
    public function createElement( $tagname ) {
        return New PageDOMElement( $tagname );
    }
    public function createTextNode( $text ) {
        return New PageDOMTextElement( $text );
    }
}

abstract class PageDOMEntity {
    public abstract function __toString();
}

final class PageDOMTextElement extends PageDOMEntity {
    private $mText;
    
    private function PageDOMTextElement( $text ) {
        $this->mText = $text;
    }
    public function __toString() {
        return htmlspecialchars( $this->mText );
    }
}

final class PageDOMElement extends PageDOMEntity {
    private $mAttributes;
    private $mTagName;
    private $mChildren;
    
    /* attribute magic functions */
    private function __set( $name , $value ) {
        w_assert( $this->ValidAttribute( $name ) );
        $this->mAttributes[ $name ] = $value;
    }
    private function __get( $name ) {
        global $water;
        
        w_assert( $this->ValidAttribute( $name ) );
        if ( isset( $this->mAttributes[ $name ] ) ) {
            return $this->mAttributes[ $name ];
        }
        $water->Notice( 'Undefined DOM attribute ' . $name );
        return null;
    }
    private function __isset( $name ) {
        w_assert( $this->ValidAttribute( $name ) );
        return isset( $this->mAttributes[ $name ] );
    }
    private function __unset( $name ) {
        global $water;
        
        w_assert( $this->ValidAttribute( $name ) );
        if ( !isset( $this->mAttributes[ $name ] ) ) {
            $water->Notice( 'Undefined DOM attribute ' . $name );
        }
        unset( $this->mAttributes[ $name ] );
    }
    private function ValidAttribute( $name ) {
        return preg_match( '#^[a-z][a-z0-9]*$#', $name );
    }
    private function ValidTagName( $name ) {
        return preg_match( '#^[a-z][a-z0-9]*$#', $name );
    }
    public function PageDOMElement( $tagname ) {
        w_assert( $this->ValidTagName( $tagname ) );
        $this->mTagName = $tagname;
    }
    public function appendChild( PageDOMEntity $element ) {
        $this->mChildren[] = $element;
    }
    public function __toString() {
        $content = '<' . $this->mTagName;
        $attribs = array();
        foreach ( $this->mAttributes as $name => $value ) {
            $attribs[] = $name . '="' . htmlspecialchars( $value ) . '"';
        }
        if ( !empty( $attribs ) ) {
            $content .= ' ' . implode( ' ', $attibs );
        }
        $content .= '>';
        foreach ( $this->mChildren as $child ) {
            $content .= $child;
        }
        $content .= '</' . $this->mTagName . '>';
    }
}

final class PageCoala extends Page {
    public function PageCoala() {
        global $coala;
        global $libs;
        
        $coala = $libs->Load( 'rabbit/coala' );
        
        $this->Page();
    }
    public function Output() {
        $this->GenerateBody();
        $this->OutputStart();
        $this->OutputPage();
        $this->OutputEnd();
    }
    protected function OutputPage() {
        ?>while(1);<?php // JS hijacking prevention
        echo $this->mBody;
    }
    protected function GenerateBody() {
        global $water;
        global $elemental;
        global $coala;
        
        $water->Profile( 'Render Unit' );
        
        $ret = '';
        foreach ( $this->mMainElements as $mainelement ) {
            $ret .= $coala->Run( $mainelement[ 'type' ], $mainelement[ 'name' ], $mainelement[ 'req' ] );
        }
        $this->mBody = $ret;
        
        $water->ProfileEnd();
    }
    public function AttachMainElement( $type, $id, $req ) {
        global $water;
        
        w_assert( is_array( $req ) );
        
        $this->mMainElements[] = array(
            'type' => $type,
            'name' => $id,
            'req'  => $req
        );
    }
}

final class PageAction extends Page {
    public function PageAction() {
        global $actions;
        global $libs;
        
        $actions = $libs->Load( 'rabbit/action' );
        $this->Page();
    }
    public function Output() {
        $this->GenerateBody();
        $this->OutputStart();
        $this->OutputPage();
        $this->OutputEnd();
    }
    protected function GenerateBody() {
        global $water;
        global $actions;
        
        $water->Profile( 'Render Action' );
        
        w_assert( count( $this->mMainElements ) == 1 );
        $redirect = $actions->Request( $this->mMainElements[ 0 ][ 'name' ], $this->mMainElements[ 0 ][ 'req' ] );
        
        $water->ProfileEnd();
        
        if ( !( $redirect instanceof HTTPRedirection ) ) {
            // TODO: change this into an exception
            $water->Warning( 'Action did not return a valid redirection path: ' . $this->mMainElements[ 0 ][ 'name' ] );
            return;
        }
        
        $this->mRedirection = $redirect;
    }
    protected function OutputPage() {
        if ( isset( $this->mRedirection ) ) {
            $this->mRedirection->Redirect();
        }
    }
}

?>
