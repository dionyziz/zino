<?php
    class MoodException extends Exception {
    }

    class MoodFinder extends Finder {
        protected $mModel = 'Mood';

        public function FindAll() {
            return $this->FindByPrototype( New Mood(), 0, 1000, 'Labelmale');
        }
    }

    class Mood extends Satori {
        protected $mDbTableAlias = 'moods';    

        protected function OnBeforeCreate() {
            throw New MoodException( 'Mood list is immutable; cannot create new mood' );
        }
        protected function OnBeforeUpdate() {
            throw New MoodException( 'Mood list is immutable; cannot update existing mood' );
        }
    }
?>
