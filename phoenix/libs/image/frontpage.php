<?php
    class FrontpageImageFinder extends Finder {
        protected $mModel = 'FrontpageImage';

        public function FindLatest( $offset = 0, $limit = 15, $totalcount = false ) {
            $prototype = New FrontpageImage();
            
            $latest = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Imageid', 'DESC' ), $totalcount );
            
            $imageids = array();
            $userids = array();
            
            foreach ( $latest as $frontpageimage ) {
                $imageids[] = $frontpageimage->Imageid;
                $userids[] = $frontpageimage->Userid;
            }
            
            $userfinder = New UserFinder();
            $users = $userfinder->FindByIds( $userids );
            
            w_assert( is_array( $users ) );
            
            $imagefinder = New ImageFinder();
            $images = $imagefinder->FindByIds( $imageids );
            w_assert( is_array( $images ) );
            
            $userbyid = array();
            $imagebyid = array();
            foreach ( $users as $user ) {
                w_assert( $user instanceof User, 'Expecting instance of User, ' . gettype( $user ) . ' variable given' );
                w_assert( $user->Id > 0 );
                $userbyid[ $user->Id ] = $user;
            }
            foreach ( $images as $image ) {
                w_assert( $user instanceof Image );
                w_assert( $image->Id > 0 );
                $imagebyid[ $image->Id ] = $image;
            }
            
            foreach ( $latest as $i => $frontpageimage ) {
                w_assert( isset( $imagebyid[ $frontpageimage->Imageid ] ) );
                w_assert( isset( $userbyid[ $frontpageimage->Userid ] ) );
                $latest[ $i ]->CopyImageFrom( $imagebyid[ $frontpageimage->Imageid ] );
                $latest[ $i ]->CopyUserFrom( $userbyid[ $frontpageimage->Userid ] );
            }
            
            return $latest;
        }
    }
    
    class FrontpageImage extends Satori {
        protected $mDbTableAlias = 'imagesfrontpage';
        
        public function CopyImageFrom( $value ) {
            $this->mRelations[ 'Image' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Image = $this->HasOne( 'Image', 'Imageid' );
        }
    }
?>
