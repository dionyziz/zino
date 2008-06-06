<?php
    class MoodException extends Exception {
    }

    class Mood extends Satori {
        protected $mDbTableAlias = 'moods';    

        protected function OnBeforeCreate() {
            throw New MoodException( 'Mood list is immutable' );
        }
        protected function OnBeforeUpdate() {
            throw New MoodException( 'Mood list is immutable' );
        }
    }
?>
