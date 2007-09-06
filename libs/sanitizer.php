<?php

    class XHTMLSanitizer {
        private $mXHTML;
        private $mSource;
        private $mAllowedTags;

        public function SetSource( $source ) {
            $this->mSource =  $source;
        }
        public function AllowTag( $sanetag ) {
            $this->mAllowedTags[] = $sanetag;
        }
        public function Sanitize() {
            $tags           = "";
            $attributes     = "";
            $allowedTags    = $this->mAllowedTags;
            while ( $tag = array_shift( $allowedTags ) ) {
                $tags .= $tag->Name();
                $allowedAttributes = $tag->AllowedAttributes();
                while ( $attribute = array_shift( $allowedAttributes ) ) {
                    $attributes .= $tag->Name() . " " . $attribute->Name();
                }
            }

            $data = $tags . "/n/n" . $attributes . "/n/n" . $this->mSource;
            if ( !$handle = popen( "bin/sanitizer/sanitizer", "w" ) ) {
                die( "Could not start sanitizer" );
            }
            if ( fwrite( $handle, $data ) === false ) {
                die( "Error writing data to sanitizer" );
            }
            
            $xhtml = "";
            while ( !feof( $handle ) ) {
                $xhtml .= fread( $handle, 8192 );
            }

            fclose( $handle );

            $this->mXHTML = $xhtml;
        }
        public function GetXHTML() {
            return $this->mXHTML;
        }
        public function XHTMLSanitizer() {
        }
    }

    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;

        public function Name() {
            return mName;
        }
        public function AllowAttribute( $attribute ) {
            $this->mAllowedAttributes[] = $attribute;
        }
        public function XHTMLSaneTag( $name ) {
            $this->mName = $name;
        }
    }

    class XHTMLSaneAttribute {
        public function Name() {
            return mName;
        }
        public function XHTMLSaneAttribute( $name ) {
            $this->mName = $name;
        }
    }

?>
