<?php
    class TestLog extends Testcase {
        private $mLog;
        
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Log' ), 'class Log doesn\'t exist' );
        }
        public function TestMethodsExist() {
            $this->mLog = New Log();
            $this->Assert( is_object( $this->mLog ), 'Could not instanciate Log object' );
            $this->Assert( $this->mLog instanceof Log, 'Instanciated Log object doesn\'t appear to be a Log object' );
            $this->Assert( method_exists( $this->mLog, 'Save' ), 'Log->Save method doesn\'t exist' );
        }
        public function TestLog() {
            $log = New Log();
            $log->Save();
            $this->AssertTrue( $log->Exists(), 'Newly created log doesn\'t exist' );
        }
    }
    
    return New TestLog();
?>
