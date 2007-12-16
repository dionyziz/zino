<?php
    
    global $libs;
    $libs->Load( 'image' ); // just in case

    class ImagePrototype extends SearchPrototype {
        public function ImagePrototype() {
            $this->mClass = 'Image';

            $this->mTable = 'merlin_images';

            $this->SetFields( array(
                'image_id'          => 'Id',
                'image_userid'      => 'UserId',
                'image_created'     => 'Date',
                'image_userip'      => 'UserIp',
                'image_name'        => 'Name',
                'image_description' => 'Description',
                'image_width'       => 'Width',
                'image_height'      => 'Height',
                'image_size'        => 'Size',
                'image_mime'        => 'Mime',
                'image_albumid'     => 'AlbumId',
                'image_numcomments' => 'CommentsNum'
                // 'image_pageviews'   => 'Pageviews'
            ) );

            parent::SearchPrototype();
        }
    }

?>
