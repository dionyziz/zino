<?php
	/*
		Developer: Izual
		Backend for the universities task, masked
	*/
	
	final class Uni extends Satori {
		protected $mId;
		protected $mName;
		protected $mTypeId;
		protected $mPlaceId;
		protected $mDelId;
		
		protected function GetName() {
			return $this->mName;
		}
		
		protected function SetName( $name ) {
			$this->mName = $name;
		}
		
		protected function GetType() {
			return $this->mTypeId;
		}
		
		protected function SetType( $typeid ) {
			$this->mTypeId = $typeid;
		}
		
		protected function GetPlace() {
			return $this->mPlaceId;
		}
		
		protected function SetPlace( $placeid ) {
			$this->mPlaceId = $placeid;
		}
		
		public function Exists() {
			return $this->mDelId == 0;
		}
		
		public function Delete() {
			$this->mDelId = 1;
			$this->Save();
		}
		
		public function Save() {
			global $user;
			global $universities;
			global $db;
			global $water;
			
			if ( $this->Exists() ) {
				$sql = "UPDATE
							`$universities`
						SET 
							`uni_name` = '" . myescape( $this->mName ) . "',
							`uni_typeid` = '" . myescape( $this->mTypeId ) . "', 
							`uni_placeid` = '" . myescape( $this->mPlaceId) . "',
							`uni_updateuserid` = '" . $user->Id() . "',
							`uni_updatedate` = '" . NowDate() . "',
							`uni_updateip` = '" . UserIp() . "',
							`uni_delid` =  '" . myescape( $this->mDelId ) . "'
						WHERE
							`uni_id` = '" . $this->mId . "';";
			
				$change = $db->Query( $sql );
				
				return $change;
			}
			//if not updating then instert
			
			if ( empty( $this->Date ) ) { 
				$this->Date = NowDate();
			}
			
			$sqlarray = array( 
				'uni_name' => $this->mName,
				'uni_typeid' => $this->mTypeId,
				'uni_placeid' => $this->mPlaceId,
				'uni_updateuserid' => $user->Id(),
				'uni_updatedate' => $this->Date,
				'uni_updateip' => UserIp(),
				'uni_delid' => 0
			);
			
			$change = $db->Insert( $sqlarray , $universities );
			
			if ( $change === false ) {
				return false;
			}
			$this->mId = $change->InsertId();
			
			return $change;
		}
		public function Uni( $construct ) {
			global $universities;
			global $db;
			
			if ( !is_array( $construct ) ) {
				$construct = myescape( $construct );
				
				$sql = "SELECT 
							* 
						FROM 
							`$universities`
						WHERE
							`uni_id` = '" . $construct . "'
						LIMIT 1;";
						
				$res = $db->Query( $sql );
				if ( $res->Results() ) {
					$construct = $res->FetchArray();
				}
				else {
					$construst = array();
				}
			}
			$this->mId = isset( $construct[ 'uni_id' ] ) ? $this->mId = $construct[ 'uni_id' ] : 0;
			$this->mName = isset( $construct[ 'uni_name' ] ) ? $this->mName = $construct[ 'uni_name' ] : "";
			$this->mTypeId = isset( $construct[ 'uni_typeid' ] ) ? $this->mTypeId = $construct[ 'uni_typeid' ] : false;
			$this->mPlaceId = isset( $construct[ 'uni_placeid' ] ) ? $this->mPlaceId = $construct[ 'uni_placeid' ] : 0;
			$this->mDelId = isset( $construct[ 'uni_delid' ] ) ? $this->mDelId = $construct[ 'uni_delid' ] : false;
			
		}
	}