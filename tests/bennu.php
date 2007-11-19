<?php

    global $libs;
    $libs->Load( 'bennu' );

    final class TestBennu extends Testcase { 
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Bennu' ), 'Class bennu does not exist' );
            $this->Assert( class_exists( 'BennuRule' ), 'BennuRule class does not exist' );
            $this->Assert( class_exists( 'BennuSexRule' ), 'Default BennuSexRule class does not exist' );
            $this->Assert( class_exists( 'BennuAgeRule' ), 'Default BennuAgeRule class does not exist' );
            $this->Assert( class_exists( 'BennuRegisterRule' ), 'Default BennuRegisterRule class does not exist' );
            $this->Assert( class_exists( 'BennuRandomRule' ), 'Default BennuRandomRule class does not exist' );
            // and more default classes..
        }
        public function TestMethodsExist() {
            $bennu = New Bennu();
            $this->Assert( method_exists( $bennu, 'AddRule' ), 'Bennu::AddRule method does not exist' );
            $this->Assert( method_exists( $bennu, 'Exclude' ), 'Bennu::Exclude method does not exist' );
            $this->Assert( method_exists( $bennu, 'Get' ), 'Bennu::Get method does not exist' );

            /* methods for extending bennu rule */
            $rule = New BennuRule();
            $this->Assert( method_exists( $rule, 'NormalDistribution' ), 'BennuRule::NormalDistribution method does not exist' );
            $this->Assert( method_exists( $rule, 'Random' ), 'BennuRule::Random method does not exist' );
            $this->Assert( method_exists( $rule, 'Get' ), 'BennuRule::Get method does not exist' );
            $this->Assert( method_exists( $rule, 'Calulate' ), 'BennuRule::Calculate method does not exist' );
        }
        public function TestNoRuleBennu() {
            $bennu = New Bennu();
            $list = $bennu->Get( 20 );

            $this->Assert( is_array( $list ), 'List returned from Bennu::Get is not an array' );
            $this->AssertFalse( empty( $list ), 'List returned from Bennu without rules is empty' ); // well, no users? duh.
            
            if ( User_Count() >= 20 ) {
                $this->AssertEquals( count( $list ), 20, 'Bennu without rules did not return the number of users requested' );
            }

            $list = $bennu->Get( User_Count() + 10 );
            $this->AssertEquals( count( $list ), User_Count(), 'Bennu should return all the users when the number of users requested are equal to or more than the number of all users' );
        }
    }

?>
