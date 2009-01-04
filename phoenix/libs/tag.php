<?php
    // Everytime you define a new interest tag type, please change all the files that include a comment with the following text: INTEREST_TAG_TYPE, and update them accordingly. If you use Linux, an easy way to do this is by executing the following commands in your local repository root:
    // find -iname "*.php" -or -iname "*.js" ! -path "*svn*" -print0 | xargs -0 grep -i "INTEREST_TAG_TYPE" -n --color -C5
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    define( 'TAG_HOBBIE', 1 );
    define( 'TAG_MOVIE', 2 );
    define( 'TAG_BOOK', 3 );
    define( 'TAG_SONG', 4 );
    define( 'TAG_ARTIST', 5 );
    define( 'TAG_GAME', 6 );
    define( 'TAG_SHOW', 7 );

    function Tag_ValidType( $type ) {
        return is_int( $type ) && $type <= 7 && $type >= 1;
    }

    function is_tag( $tag ) {
        return $tag instanceof Tag && $tag->Exists();
    }

    function Tag_Clear( $user ) {
        global $db;
        
        w_assert( $user instanceof User || is_int( $user ), 'Tag_Clear() accepts either a user instance or an integer parameter' );
        
        $query = $db->Prepare(
            "DELETE 
            FROM 
                :tags
            WHERE 
                `tag_userid` = :TagUserId;"
        );
        
        $query->BindTable( "tags" );
        if ( $user instanceof User ) {
            $query->Bind( 'TagUserId', $user->Id );
        }
        else {
            $query->Bind( 'TagUserId', $user );
        }

        return $query->Execute();
    }
    
    class TagException extends Exception {
    }
    
    class TagFinder extends Finder {
        protected $mModel = 'Tag';
        
        public function FindByUser( $user ) {
            if( !( $user instanceof User ) ) {
                throw New TagException( 'TagFinder::FindByUser pleads you to make sure that the argument you provided is an instance of User class' );
            }
            
            $prototype = New Tag();
            $prototype->Userid = $user->Id;
            $old = $this->FindByPrototype( $prototype, 0, 2000 );

            return $old;

            /* no sorting for now
            
            if ( count( $old ) < 2 ) { // No need for sorting
                return $old;
            }
            
            // I use the Nextid's of the Tags as keys on a new array
            $res = array();
            $i = -1; // However, there may be more than one tags with Nextid=0 (heads). Therefore we will represent those with a negative index
            foreach ( $old as $temp ) {
                if ( $temp->Nextid == 0 ) {
                    $res[ $i ] = $temp;
                    --$i; // Decrease $i, so that each head is assigned a unique index
                }
                else {
                    $res[ $temp->Nextid ] = $temp;
                }
            }
            $res_new = array();
            foreach ( $res as $temp ) {
                if ( $temp->Nextid > 0 ) {
                    continue;
                }
                $res_new[] = $temp; // found a head
                $tag = $temp;
                while ( isset( $res[ $tag->Id ] ) ) { //create the list
                    $res_new[] = $tag = $res[ $tag->Id ];
                }
            }

            return array_reverse( $res_new );
            */
        }
        public function FindByTextAndType( $text, $typeid ) {
            $prototype = New Tag();
            $prototype->Text = $text;
            $prototype->Typeid = $typeid;
            return $this->FindByPrototype( $prototype, 0, 2000 );
        }
        public function FindByNextId( $next_id ) {
            $prototype = New Tag();
            $prototype->Nextid = $next_id;
            return $this->FindByPrototype ( $prototype, 0, 2000 );
        }// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
        public function FindSuggestions( $text, $type ) { //finds all tags of a certain type, starting with text
            global $user;

            $query = $this->mDb->Prepare(
                 "SELECT DISTINCT tag_text 
                 FROM :tags
                 WHERE 
                     `tag_text` LIKE ':TagText%'
                 AND `tag_typeid` = :TagType
                 AND `tag_userid` <> :UserId
                 LIMIT 0, 50;"
            );
            $query->BindTable( 'tags' );
            $query->BindLike( "TagText", $text );
            $query->Bind( "TagType", $type );
            $query->Bind( "UserId", $user->Id );
            $res = $query->Execute();
            $arr = array();
            while( $row = $res->FetchArray() ) {
                $arr[] = $row[ 'tag_text' ];
            }
            return $arr;
        }
        public function FindPopular( $type = TAG_HOBBIE, $limit = 20 ) {
            w_assert( is_int( $limit ) );
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( * ) AS popularity,
                    `tag_text` AS text
                FROM
                    :tags
                WHERE
                    `tag_typeid` = :type
                GROUP BY
                    `tag_text`
                ORDER BY popularity DESC
                LIMIT :limit;'
            );

            $query->BindTable( 'tags' );
            $query->Bind( 'type', $type );
            $query->Bind( 'limit', $limit );
            $res = $query->Execute();
            $rows = array();

            $min = PHP_INT_MAX;
            $max = 0;
            while ( $row = $res->FetchArray() ) {
                if ( $row[ 'popularity' ] > $max ) {
                    $max = $row[ 'popularity' ];
                }
                if ( $row[ 'popularity' ] < $min ) {
                    $min = $row[ 'popularity' ];
                }
                $rows[] = $row;
            }

            // normalize
            if ( $max == $min ) { // avoid division by zero and make all popularities 0 
                ++$max;
            }
            $ret = array();
            foreach ( $rows as $i => $row ) {
                $ret[ $row[ 'text' ] ] = ( $row[ 'popularity' ] - $min ) / ( $max - $min );
            }
            ksort( $ret );

            return $ret;
        }
    }
 
    class Tag extends Satori {
        protected $mDbTableAlias = 'tags';
        private $mUser;
         
        public function __get( $key ) {
            if ( $key == 'User' ) {
                if ( !is_object( $this->mUser ) || $this->mUser->Id != $this->Userid ) {
                    $this->mUser = New User( $this->Userid );
                }
                return $this->mUser;
            }

            return parent::__get( $key );
        }
        public function MoveAfter( $tag ) {
            if ( !is_tag( $tag ) ) {
                throw New TagException( 'Tag::MoveAfter argues that the argument you provided is not of type tag, or it does not exist in the database. What do you have to say about this?' );
            }
            if ( $tag->Typeid != $this->Typeid ) {
                throw New TagException( "Tag::MoveAfter does not allow you to order tags of different types" );
            }
            if ( $tag->Userid != $this->Userid ) {
                throw New TagException( "Tag::MoveAfter does not allow you to order tags belonging to different users" );
            }
            $finder = New TagFinder();
            $a = $finder->FindByNextId( $this->Id );
            $a = $a[0];
            if ( is_tag( $a ) ) {
                $a->Nextid = $this->Nextid;
                $a->Save();
            }

            $this->Nextid = $tag->Nextid;
            $this->Save();

            $tag->Nextid = $this->Id;
            $tag->Save();
        }
        public function MoveBefore( $tag ) {
            if ( !is_tag( $tag ) ) {
                throw New TagException( 'Tag::MoveBefore argues that the argument you provided is not of type tag, or it does not exist in the database. What do you have to say about this?' );
            }
            if ( $tag->Typeid != $this->Typeid ) {
                throw New TagException( "Tag::MoveBefore does not allow you to order tags of different types" );
            }
            if ( $tag->Userid != $this->Userid ) {
                throw New TagException( "Tag::MoveBefore does not allow you to order tags belonging to different users" );
            }
            $finder = New TagFinder();
            $a = $finder->FindByNextId( $this->Id );
            $a = $a[0];
            if ( is_tag( $a ) ) {
                $a->Nextid = $this->Nextid;
                $a->Save();
            }

            $b = $finder->FindByNextId( $tag->Id );
            $b = $b[0];
            if ( is_tag( $b ) ) {
                $b->Nextid = $this->Id;
                $b->Save();
            }
            $this->Nextid = $tag->Id;
            $this->Save();
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        protected function OnCreate() {
            $this->User->Profile->Save(); // force last update date to change
        }
        protected function OnUpdate() {
            $this->User->Profile->Save();  // force last update date to change
        }
        protected function OnDelete() {
            $this->User->Profile->Save();  // force last update date to change
            
            // fix broken linked list?
            $finder = New TagFinder();
            $a = $finder->FindByNextId( $this->Id );
            $a = $a[ 0 ];
            if ( is_tag( $a ) ) { // TODO: what does this do? this seems to be incorrect --dionyziz
                $a->Nextid = $this->Nextid; // setting the nextid to a tag that is about to be deleted?  --dionyziz
                $a->Save();
            }
        }
        protected function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
        }
     }
?>
