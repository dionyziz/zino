<?php

	function PM_FormatMulti( $pms ) {	
		$texts = array();
		foreach ( $pms as $pm ) {
			$texts[ $pm->Id() ] = $pm->TextRaw();
		}
			
		$formattedTexts = mformatpms( $texts );
		
		foreach ( $pms as $pm ) {
			$pm->SetTextFormatted( $formattedTexts[ $pm->Id() ] );
		}
		
		return true;
	}

	final class PM {
		private $mId;
		private $mFrom;
		private $mTo;
		private $mCreated;
		private $mText;
		private $mTextFormatted;
		private $mDelId;
		
		public function Id() {
			return $this->mId;
		}
		public function Text() {
			if ( $this->mTextFormatted === false ) {
				$this->mTextFormatted = mformatpms( array( $this->mText ) );
			}
			return $this->mTextFormatted;
		}
		public function TextRaw() {
			return $this->mText;
		}
		public function SetTextFormatted( $text ) {
			$this->mTextFormatted = $text;
		}
		public function Time() {
			return dateDistance( $this->mCreated );
		}
		public function IsRead() {
			return $this->mDelId > 0;
		}
		public function Sender() {
			return New User( $this->mFrom );
		}
		public function Receiver() {
			return New User( $this->mTo );
		}
		public function User() {
			//If x sent a pm to y, and now y looks at his pms, return x
			if ( !$this->mUser ) {
				if ( $this->UserIsSender() ) {
					return $this->Receiver();
				}
				return $this->Sender();
			}
			return $this->mUser;
		}
		public function UserIsSender() {
			global $user;
			
			return ( $user->Id() == $this->Sender()->Id() );
		}
		private function Construct( $pmid ) {
			global $db;
			global $pms;
			
			$sql = "SELECT * FROM `$pms` WHERE `pm_id` = '$pmid' ORDER BY `pm_created` DESC LIMIT 1;";
			$res = $db->Query( $sql );
			
			if ( !$res->Results() ) {
				return false;
			}
			return $res->FetchArray();
		}
		public function PM( $construct ) { 
			if ( is_array( $construct ) ) {
				$array = $construct;
			}
			else {
				$array = $this->Construct( $construct );
			}
			
			$this->mId 		= isset( $array[ "pm_id" ] ) ? $array[ "pm_id" ] : 0;
			$this->mFrom	= isset( $array[ "pm_from" ] ) ? $array[ "pm_from" ] : 0;
			$this->mTo		= isset( $array[ "pm_to" ] ) ? $array[ "pm_to" ] : 0;
			$this->mCreated	= isset( $array[ "pm_created" ] ) ? $array[ "pm_created" ] : '0000-00-00 00:00:00';
			$this->mText	= isset( $array[ "pm_text" ] ) ? $array[ "pm_text" ] : '';
			$this->mDelId	= isset( $array[ "pm_delid" ] ) ? $array[ "pm_delid" ] : 0;
			$this->mUser	= isset( $array[ "user_id" ] ) ? New User( $array ) : false;
			
			$this->mTextFormatted = false;
		}
	}
?>