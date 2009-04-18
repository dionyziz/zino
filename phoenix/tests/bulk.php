<?php

    final class TestBulk extends Testcase {
        protected $mAppliesTo = 'libs/bulk';
        private $mTestId = 0;

        public function SetUp() {
            global $libs;

            $libs->Load( 'bulk' );
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Bulk' ), 'Class Bulk does not exist' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( 'Bulk', 'FindById' ), 'Bulk::FindById method does not exist' );
            $this->Assert( method_exists( 'Bulk', 'Store' ), 'Bulk::Store method does not exist' );
            $this->Assert( method_exists( 'Bulk', 'Delete' ), 'Bulk::Delete method does not exist' );
        }
        public function TestCreation() {
            $id = Bulk::Store( "foobarblah" );

            $this->Assert( is_int( $id ), 'Bulk Id should be an integer after saving' );

            $this->mTestId = $id;
            $text = Bulk::FindById( $this->mTestId );

            $this->AssertEquals( "foobarblah", $text, 'Bulk text not the one saved after creating a new instance (with id ' . $id . ')' );
        }
        public function TestFindOne() {
            $text = Bulk::FindById( $this->mTestId );
            $this->AssertEquals( "foobarblah", $bulk->Text, 'Bulk text not the one saved after creating a new instance' );
        }
        public function TestDelete() {
            Bulk::Delete( $this->mTestId );

            $text = Bulk::FindById( $this->mTestId );
            $this->AssertFalse( $text, 'Bulk seems to exist after creating a new instance' );
        }
        public function TestFindMany() {
            $id0 = Bulk::Store( $text0 = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin mauris diam, egestas sed, scelerisque at, varius ut, odio. Integer dapibus. Mauris ultricies lorem eget arcu semper nonummy. Duis ac velit vitae velit vehicula aliquam. Aliquam in risus. Vivamus sit amet erat. Donec dolor nisi, venenatis sed, pharetra in, scelerisque cursus, mi. Donec luctus lectus vitae erat. Nulla facilisi. In ultrices ornare libero. Mauris tincidunt porta odio. Phasellus aliquet felis sed ligula. Aenean nisl diam, lobortis sed, interdum vel, dapibus quis, erat. Aliquam vulputate diam. Proin augue. Aenean ullamcorper feugiat justo.

            Donec pulvinar sapien vel nulla. Curabitur quis turpis et neque euismod luctus. Morbi vitae dolor sed eros vestibulum gravida. Nunc ac enim. Proin et enim. Nam gravida accumsan nibh. Phasellus vitae nisi. Vestibulum sodales placerat purus. Morbi quis lectus non ligula malesuada consectetuer. In vitae turpis. Vivamus elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus molestie, quam quis ornare lobortis, elit odio congue orci, quis varius metus tellus eu lorem. Aliquam ac mi. Mauris semper condimentum leo. Morbi vel sem. Vivamus dolor.

            Nulla blandit vestibulum nulla. In blandit tristique velit. In hac habitasse platea dictumst. Sed a ipsum. Nam pede nunc, tincidunt vitae, convallis a, ultricies sit amet, neque. Duis ut dui. Proin vel orci sit amet arcu malesuada auctor. Aliquam sed diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc venenatis aliquam sapien. Vestibulum eu tortor eget magna pretium sagittis. Nulla nec nunc non quam iaculis consectetuer. Nulla facilisi. Praesent convallis. Aliquam adipiscing nunc. Ut nec tortor vel leo volutpat pretium. Maecenas nisi diam, luctus eu, nonummy pulvinar, placerat eget, nisi. Mauris nulla urna, aliquet quis, egestas ac, tempus vitae, ipsum. Vivamus pharetra laoreet arcu." );

            $id1 = Bulk::Store( $text1 = "1. Omnium hominum quos ad amorem veritatis natura superior impressit hoc maxime interesse videtur: ut, quemadmodum de labore antiquorum ditati sunt, ita et ipsi posteris prolaborent, quatenus ab eis posteritas habeat quo ditetur. 2. Longe nanque ab offitio se esse non dubitet qui, publicis documentis imbutus, ad rem publicam aliquid afferre non curat; non enim est lignum, quod secus decursus aquarum fructificat in tempore suo, sed potius perniciosa vorago semper ingurgitans et nunquam ingurgitata refundens. 3. Hec igitur sepe mecum recogitans, ne de infossi talenti culpa quandoque redarguar, publice utilitati non modo turgescere, quinymo fructificare desidero, et intemptatas ab aliis estendere veritates. 4. Nam quem fructum ille qui theorema quoddam Euclidis iterum demonstraret? qui ab Aristotile felicitatem ostensam reostendere conaretur? qui senectutem a Cicerone defensam resummeret defensandam? Nullum quippe, sed fastidium potius illa superfluitas tediosa prestaret. 5. Cumque, inter alias veritates occultas et utiles, temporalis Monarchie notitia utilissima sit et maxime latens et, propter non se habere immediate ad lucrum, ab omnibus intemptata, in proposito est hanc de suis enucleare latibulis, tum ut utiliter mundo pervigilem, tum etiam ut palmam tanti bravii primus in meam gloriam adipiscar. 6. Arduum quidem opus et ultra vires aggredior, non tam de propria virtute confidens, quam de lumine Largitoris illius \"qui dat omnibus affluenter et non improperat\"." );

            $id2 = Bulk::Store( $text2 = "Hello, World!" );

            $ids = array( $id0, $id1, $id2 );
            $bulks = Bulk::FindById( $ids );
            $this->Assert( is_array( $bulks ), 'FindById did not return an array' );
            $this->AssertEquals( 3, count( $bulks ), 'FindById did not return the right number of bulks' );

            $isbulk = true;
            $oldbulks = array( $id0 => $text0, $id1 => $text1, $id2 => $text2 );
            foreach ( $bulks as $id => $text ) {
                $this->AssertEquals( $oldbulks[ $id ], $text, 'Bulk returned by FindById does not have the right text' );
            }

            Bulk::Delete( array( $id0, $id1, $id2 ) );
        }   
    }

    return New TestBulk();

?>
