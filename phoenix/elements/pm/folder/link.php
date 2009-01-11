<?php
    
    class ElementPmFolderLink extends Element {
        public function Render( PMFolder $folder ) {
            global $user;

            $unreadCount = $user->Count->Unreadpms;
            if ( $folder->Typeid == PMFOLDER_INBOX ) {
                ?><div class="activefolder folder" alt="Εισερχόμενα" title="Εισερχόμενα" onload="pms.activefolder = this;return false" id="folder_<?php
                echo $folder->Id;
                ?>"><a href="" class="folderlinksactive" onclick="return pms.ShowFolderPm( this.parentNode, <?php
                    echo $folder->Id;
                ?> )"><span>&nbsp;</span>Εισερχόμενα<?php
                if ( $unreadCount ) {
                    ?> (<?php
                    echo $unreadCount;
                    ?>)<?php
                }
                ?></a></div><?php
            }
            else if ( $folder->Typeid == PMFOLDER_OUTBOX ) {
                ?><div class="noactivefolder folder top" alt="Απεσταλμένα" title="Απεσταλμένα" id="folder_<?php
                echo $folder->Id; 
                ?>"><a href="" class="folderlinks" onclick="return pms.ShowFolderPm( this.parentNode,<?php
                echo $folder->Id;
                ?> )"><span>&nbsp;</span>Απεσταλμένα</a></div><?php
            }
            else {
                ?><div class="noactivefolder createdfolder folder top" id="folder_<?php
                echo $folder->Id;
                ?>" alt="<?php
                echo htmlspecialchars( $folder->Name );
                ?>" title="<?php
                echo htmlspecialchars( $folder->Name );
                ?>"><a href="" class="folderlinks" onclick="return pms.ShowFolderPm( this.parentNode , '<?php
                echo $folder->Id;
                ?>' )"><span>&nbsp;</span><?php
                echo htmlspecialchars( $folder->Name );
                ?></a></div><?php
            }
        }

    }
?>
