<?php

    // handle basic HTML stuff
    // like stuff in <header>, scripts, stylesheets

    // coala calls will never use this
    // no separation for action pages

    class ControllerPage {
        protected $mSupportsXML = false;
        protected $mFavicon = '';
        protected $mBase = '';
        protected $mTitle = '';
        protected $mIsTitleFinal = false;
        protected $mMeta = array();
        protected $mKeywords = array();
        protected $mScripts = array();
        protected $mStylesheets = array();
        protected $mScriptsInline = array();
        protected $mDoWaterDump = false;
        protected $mBaseIncludePath = '';
        protected $mValidLanguages = array();
        protected $mNaturalLanguage = '';

        public function __construct() {
            global $rabbit_settings;

            // from Rabbit_Construct
            $this->SetNaturalLanguage( $rabbit_settings[ 'language' ] );
            $this->SetBaseIncludePath( $rabbit_settings[ 'rootdir' ] );
            $this->SetBase( $rabbit_settings[ 'webaddress' ] . '/' );
            $this->SetWaterDump( !$rabbit_settings[ 'production' ] );

            $this->CheckXML();
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
        public function XMLStrict() {
            return $this->mSupportsXML;
        }
        public function SetBase( $base ) {
            $this->mBase = $base;
        }
        public function Title() {
            return $this->mTitle;
        }
        public function IsTitleFinal() {
            return $this->mIsitleFinal;
        }
        public function SetTitle( $title ) {
            $this->mTitle = $title;
        }
        public function SetFinalTitle( $title ) {
            $this->SetTitle( $title );
            $this->mIsTitleFinal = true;
        }
        public function SetIcon( $favicon ) {
            $this->mFavIcon = $favicon;
        }
        public function AddMeta( $name, $content ) {
            w_assert( preg_match( '#^[A-Za-z-]+$#', $name ) );
            $this->mMeta[ $name ] = $content;
        }
        public function AddKeyword( $keyword ) {
            if ( is_array( $keyword ) ) {
                $this->mKeywords = array_merge( $this->mKeywords, $keyword );
            }
            else {
                $this->mKeywords[] = $keyword;
            }
            $this->AddMeta( 'keywords', implode( ', ', $this->mKeywords ) );
        }
        public function AttachStylesheet( $filename, $ieversion = false ) {
            global $water;
            
            if ( !isset( $this->mStylesheets[ $filename ] ) ) {
                $water->Trace( 'Loading stylesheet ' . $filename );
                $this->mStylesheets[ $filename ] = array(
                    'filename' => $filename,
                    'ieversion' => $ieversion
                );
                if ( count( $this->mStylesheets ) > 30 ) {
                    $water->Warning( 'You are approaching or over 32 CSS loaded stylesheets. Internet Explorer may not be able to handle this.' );
                }
            }
        }
        public function AttachScript( $filename, $language = 'javascript', $head = false, $ieversion = '', $priority = 0 ) {
            global $water;
            
            if ( isset( $this->mScripts[ $filename ] ) ) {
                unset( $this->mScripts[ $filename ] );
            }
            else {
                $water->Trace( 'Loading script ' . $filename );
            }
            $this->mScripts[ $filename ] = array( 'language'  => $language, 
                                                  'filename'  => $filename, 
                                                  'ieversion' => $ieversion,
                                                  'head'      => $head,
                                                  'priority'  => $priority
                                                );
        }
        public function AttachInlineScript( $code, $language = 'javascript' ) {
            if ( !isset( $this->mScriptsInline[ $language ] ) ) {
                $this->mScriptsInline[ $language ] = '';
            }
            $this->mScriptsInline[ $language ] .= $code;
        }
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
        protected function WaterLink() {
            global $water;
            
            // keep in mind that profiles and alerts beyond this point will not be dumped
            if ( $this->mDoWaterDump ) {
                $water->Post();
            }
        }
        private function uasortCheckPriority( $a, $b ) {
            $a = (int) $a[ 'priority' ];
            $b = (int) $b[ 'priority' ];
            if ( $a == 0 ) {
                return 1;
            }
            if ( $b == 0 ) {
                return -1;
            }
            if ( $a == $b ) {
                return 0;
            }
        }
        public function Output( $html ) {
            $this->OutputHeaders();
            $this->OutputHTMLStart();
            $this->OutputHTMLHeader();
            $this->OutputHTMLBody( $html );
            $this->OutputHTMLEnd();
        }
        protected function OutputHeaders() {
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
            if ( $this->mFavIcon !== false ) {
                ?><link rel="shortcut icon" href="<?php
                echo htmlspecialchars( $this->mFavIcon );
                ?>" type="image/vnd.microsoft.icon" />
                <link rel="icon" href="<?php
                echo htmlspecialchars( $this->mFavIcon );
                ?>" type="image/vnd.microsoft.icon" /><?php
            }
            $prioritized = Array();
            foreach( $this->mScripts as $key => $script ) {
                if ( $script[ 'priority' ] != 0 ) {
                    $prioritized[ $key ] = $script;
                    unset( $this->mScripts[ $key ] );
                }
            }
            uasort( $prioritized, array( 'PageHTML', 'uasortCheckPriority' ) );
            $this->mScripts = array_merge_recursive( array_reverse( $prioritized ), $this->mScripts );
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
            ?>" charset="utf-8"></script><?php
            if ( $script[ 'ieversion' ] != '' ) {
                ?><![endif]--><?php
                echo "\n";
            }
        }
        private function OutputHTMLBody( $bodyhtml ) {
            ?><body><?php
            echo $bodyhtml;
            $this->WaterLink();
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
        }
        private function OutputHTMLEnd() {
            ?></html><?php
        }
    }

?>
