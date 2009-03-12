<?php
    
    class ElementAlbumPhotoUpload extends Element {
        public function Render( tInteger $albumid , tInteger $typeid , tString $color ) {
            global $water;
            global $user;
            global $rabbit_settings;
            global $page;
            
            $page->SetTitle( 'Ανέβασε μια εικόνα' );
            //typeid is 0 for album photo uploads and 1 for avatar uploads at settings
            $album = New Album( $albumid->Get() );
            $color = $color->Get();
            $page->AttachInlineScript( "document.body.style.backgroundColor = '#" . $color . "';" );
            if ( $typeid->Get() == 2 && UserBrowser() == "MSIE" ) {
                $page->AttachInlineScript( "document.body.style.backgroundColor = '#ffdf80';" );
            }
            if ( $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                switch ( $album->Ownertype ) {
                    case TYPE_USERPROFILE:
                        $canupload = $album->Owner->Id == $user->Id;
                        break;
                    case TYPE_SCHOOL:
                        $canupload = $user->Profile->Schoolid == $album->Owner->Id; 
                        break;
                    default:
                        $canupload = false;
                }
                if ( $canupload ) {
                    ?><form method="post" enctype="multipart/form-data" action="<?php
						if ( $typeid->Get() == 4 ) {
							Element( 'user/url', $user );
						}
					?>do/image/upload2" id="uploadform">
                            <input type="hidden" name="albumid" value="<?php
                            echo $album->Id;
                            ?>" />
                            <input type="hidden" name="typeid" value="<?php
                            echo $typeid->Get();
                            ?>" />
                            <input type="hidden" name="color" value="<?php
                            echo $color;
                            ?>" />
                            <div class="colorlink">
                                Νέα φωτογραφία
                            </div>
                            <input type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
                            <input type="submit" value="upload" style="display:none" />
                        </form>
                        <div id="uploadingwait">
                            <img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" />
                            Παρακαλώ περιμένετε                
                        </div><?php    
                }
            }
            return array( 'tiny' => true );
        }
    }
?>
