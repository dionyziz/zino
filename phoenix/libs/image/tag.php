<?php
    global $libs;

    $libs->Load( 'image/image' );

    class ImageTagFinder extends Finder {
        protected $mModel = 'ImageTag';

        public function FindByImage( Image $image ) {
            $prototype = New ImageTag();
            $prototype->Imageid = $image->Id;

            return $this->FindByPrototype( $prototype, 0, 10, array( 'Id', 'DESC' ) );
        }
        public function LoadDefaults() {
            global $user;

            $this->Ownerid = $user->Id;
        }
    }

    class ImageTag extends Satori {
        protected $mDbTableAlias = 'imagetags';
        
        protected function OnConstruct() {
            global $water;
            
            var_dump( func_get_args() );
            $water->callstack( $water->callstack_lastword( $assertresult->Callstack ) );
            
            ?><br /><?php
        }
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_IMAGETAG_CREATED;
            $event->Itemid = $this->Imageid;
            $event->Userid = $this->Ownerid;
            $event->Save();
        }
    }
?>
