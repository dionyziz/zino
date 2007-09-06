<?php

    class XHTMLSanitizer {
        private $mXHTML;
        private $mSource;
        private $mAllowedTags;

        public function SetSource( $source ) {
            if ( !is_scalar( $source ) ) {
                $this->mSource = "";
            }
            else {
                $this->mSource = str_replace( "\n", " ", $source );
            }
            $this->mXHTML = false;
        }
        public function AllowTag( $sanetag ) {
            $this->mAllowedTags[] = $sanetag;
        }
        private function Sanitize() {
            $tags           = "";
            $attributes     = "";
            $allowedTags    = $this->mAllowedTags;
            while ( $tag = array_shift( $allowedTags ) ) {
                $tags .= $tag->Name() . "\n";
                $allowedAttributes = $tag->AllowedAttributes();
                while ( $attribute = array_shift( $allowedAttributes ) ) {
                    $attributes .= $tag->Name() . " " . $attribute->Name() . "\n";
                }
            }

            $data = $tags . "\n" . $attributes . "\n" . $this->mSource . "\n\n";

            $descriptorspec = array(
                0 => array( "pipe", "r" ),
                1 => array( "pipe", "w" ),
                2 => array( "file", "/tmp/error-output.txt", "a" )
            );

            $cmd = '/srv/www/vhosts/chit-chat.gr/subdomains/beta/httpsdocs/bin/sanitizer/sanitizer';
            chdir( '/srv/www/vhosts/chit-chat.gr/subdomains/beta/httpsdocs/bin/sanitizer' );
            $proccess = proc_open( $cmd, $descriptorspec, $pipes );
            if ( !is_resource( $proccess ) ) {
                die( "Error opening sanitizer process" );
            }
            
            fwrite( $pipes[ 0 ], $data );
            fclose( $pipes[ 0 ] );

            $this->mXHTML = stream_get_contents( $pipes[ 1 ] );
            fclose( $pipes[ 1 ] );

            proc_close( $proccess );
            
            $this->mXHTML = str_replace( "\n", "", $this->mXHTML );
        }
        public function GetXHTML() {
            if ( $this->mXHTML === false ) {
                $this->Sanitize();
            }
            return $this->mXHTML;
        }
        public function XHTMLSanitizer() {
            $this->mAllowedTags = array();
            $this->mXHTML = false;
        }
    }

    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;

        public function Name() {
            return $this->mName;
        }
        public function AllowedAttributes() {
            return $this->mAllowedAttributes;
        }
        public function AllowAttribute( $attribute ) {
            $this->mAllowedAttributes[] = $attribute;
        }
        public function XHTMLSaneTag( $name ) {
            $this->mName = $name;
            $this->mAllowedAttributes = array();
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
