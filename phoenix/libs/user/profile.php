<?php
    global $libs;
    
    $libs->Load( 'place' );
    $libs->Load( 'school/school' );
    $libs->Load( 'user/oldprofile' );
    
    function Profile_Dob2Age( $dob ) {
        if ( $dob != "0000-00-00" ) {
            $validdob = true;
            $birthday = ( int )date( 'j', strtotime( $dob ) );
            $birthmonth = ( int )date( 'n', strtotime( $dob ) );
            $birthyear = ( int )date( 'Y', strtotime( $dob ) );
            $nowdate = GetDate();
            $nowyear = $nowdate[ "year" ];
            $ageyear = $nowyear - $birthyear;
            $nowmonth = $nowdate[ "mon" ];
            $nowday = $nowdate[ "mday" ];
            $hasbirthday = false;
            if ( $nowmonth < $birthmonth ) {
                --$ageyear;
            }
            else {
                if ( $nowmonth == $birthmonth ) {
                    if ( $nowday < $birthday ) {
                        --$ageyear;
                    }
                    else {
                        if ( $nowday == $birthday ) {
                            $hasbirthday = true;
                        }
                    }
                }
            }
        }
        if ( isset( $ageyear ) && $ageyear > 5 ) {
            return $ageyear;
        }
        return false;
    }
    
    function ValidateEmail( $id, $hash ) {
        global $libs;
        
        $libs->Load( 'user/user');
    
        $_user = New User( $id );
        if( $_user->Exists() 
            && ($_user->Profile->emailvalidated == false) && ($_user->Profile->emailvalidationhash != "") && ( $_user->Profile->emailvalidationhash == $hash ) ) {
            $_user->Profile->emailvalidated = true;
            $_user->Save();
            return true;
        }
        return false;            
    }
    
    class UserProfileFinder extends Finder {
        protected $mModel = 'UserProfile';

        public function FindBySchool( $school, $offset = 0, $limit = 10000 ) {
            $prototype = New UserProfile();
            $prototype->Schoolid = $school->Id;
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Userid', 'DESC' ) );
        }
        
        public function FindAllUsersByEmails( $mails ) {
            $query = $this->mDb->Prepare(
                'SELECT `user_id`, `profile_email`, `user_lastlogin` 
					FROM `userprofiles` 
					LEFT JOIN `users` ON userprofiles.profile_userid=users.user_id
				WHERE
					`profile_email` IN :emails
				ORDER BY
					`profile_email` ASC, `user_lastlogin` DESC
                ;'
            );
            $query->Bind( 'emails', $mails );  
            $res = $query->Execute();
            
            $users = array();
            $curmail = '';
            while ( $row = $res->FetchArray() ) {
				if ( $row[ 'profile_email' ] == $curmail ){
					continue;
				}
				$curmail = $row[ 'profile_email' ];
                $users[ $row[ 'profile_email' ] ] = $row[ 'user_id' ];
            }
            return $users;//<-return array[ 'profile_email' ] = 'profile_userid'
        }
    }

    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function CopyLocationFrom( $value ) {
            $this->mRelations[ 'Location' ]->CopyFrom( $value );
        }
        public function CopySchoolFrom( $value ) {
            $this->mRelations[ 'School' ]->CopyFrom( $value );
        }
        public function CopyMoodFrom( $value ) {
            $this->mRelations[ 'Mood' ]->CopyFrom( $value );
        }
        protected function OnBeforeUpdate() {
            $this->Updated = NowDate();
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'BirthDay':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'j', strtotime( $this->Dob ) );
                case 'BirthMonth':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'n', strtotime( $this->Dob ) );
                case 'BirthYear':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'Y', strtotime( $this->Dob ) );
                case 'Age':
                    $validdob = false;
                    return Profile_Dob2Age( $this->Dob );
                case 'HasBirthday':
                    if ( $this->Dob != "0000-00-00" ) {
                        $nowdate = getdate();
                        $dobmonth = ( int )date( 'n', $this->Dob );
                        $dobday = ( int )date( 'j', $this->Dob );
                        $ageyear = $nowyear - $dobyear;
                        $nowmonth = $nowdate[ "mon" ];
                        $nowday = $nowdate[ "mday" ];
                        if ( $nowmonth == $dobmonth && $nowday == $dobday ) {
                            return true;
                        }
                    }
                    return false;
                default:
                    return parent::__get( $key );
            }
        }
        protected function MakeBirthdate( $day, $month, $year ) {
            w_assert( is_int( $day ) );
            w_assert( is_int( $month ) );
            w_assert( is_int( $year ) );
            if ( $month < 10 ) {
                $month = '0' . ( string )$month;
            }
            else {
                $month = ( string )$month;
            }
            if ( $day < 10 ) {
                $day = '0' . ( string )$day;
            }
            else {
                $day = ( string )$day;
            }
            return "$year-$month-$day";
        }
        public function __set( $key, $value ) {
            global $water;

            if ( $key == 'School' && $this->mAllowRelationDefinition ) {
                return parent::__set( $key, $value );
            }
            switch ( $key ) {
                case 'BirthDay':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $value, $this->BirthMonth, $this->BirthYear );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'BirthMonth':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $this->BirthDay, $value, $this->BirthYear );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'BirthYear':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $this->BirthDay, $this->BirthMonth, $value );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'School':
                    $this->School->Id = $value->Id; // $this->Schoolid? -- abresas
                    break;
                default:
                    parent::__set( $key, $value );
            }
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->School = $this->HasOne( 'School', 'Schoolid' );
            $this->Mood = $this->HasOne( 'Mood', 'Moodid' );
            $this->OldProfile = $this->HasOne( 'OldUserProfile', 'Userid' );
        }
        protected function LoadDefaults() {
            $this->Education = 0;
            $this->Sexualorientation = '-';
            $this->Relationship = '-';
            $this->Religion = '-';
            $this->Politics = '-';    
            $this->Eyecolor = '-';
            $this->Haircolor = '-';
            $this->Smoker = '-';
            $this->Drinker = '-';
            $this->Height = -3;
            $this->Weight = -3;
            $this->Updated = NowDate();
            $this->Songid = -1;        
        }
        protected function OnUpdate( $updatedAttributes, $previousValues ) {
            global $user;

            foreach ( $previousValues as $attribute => $value ) {
                $this->OldProfile->$attribute = $value;
            }

            $this->OldProfile->Save();
            
            if ( isset( $updatedAttributes[ 'Email' ] ) ) {
                $this->ChangedEmail( $previousValues[ 'Email' ], $user->Name );
            }
        }
        public function ChangedEmail( $previousEmail, $username ) {
            global $libs;
            global $rabbit_settings;
            global $water;
                        
            $libs->Load( 'rabbit/helpers/email' );
            $libs->Load( 'rabbit/helpers/hashstring' );
            
            if ( $previousEmail == '' ) {
                $this->Emailvalidated = false;                
                $this->Emailvalidationhash = GenerateRandomHash();
                $this->Save();
                $link =  $rabbit_settings[ 'webaddress' ] . '/?p=emailvalidate&userid=' . $this->Userid . '&hash=' . $this->Emailvalidationhash . '&firsttime=true';
                return $link;
            }
            if ( $previousEmail != $this->Email ) {// Sent validation email,set new mail,and set email-validation false
                if ( $this->Email != "" ) {
                    //$this->Email = $email;
                    $this->Emailvalidated = false;                
                    $this->Emailvalidationhash = GenerateRandomHash();
                    $this->Save();
                    
                    $link =  $rabbit_settings[ 'webaddress' ] . '/?p=emailvalidate&userid=' . $this->Userid . '&hash=' . $this->Emailvalidationhash;                    
                    ob_start();
                    $subject = Element( 'email/validate', $username, $link );
                    $message = ob_get_clean();
                    Email( $username, $this->Email, $subject, $message, $rabbit_settings[ 'applicationname' ], 'noreply@' . $rabbit_settings[ 'hostname' ] );
                }
                else {
                    //$this->Email = $email;
                    $this->Emailvalidated = false;                
                    $this->Emailvalidationhash = "";
                    $this->Save();
                }
            }
            
            return;
        }
        
        
    }

?>
