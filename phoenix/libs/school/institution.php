<?php

    class InstitutionFinder extends Finder {
        protected $mModel = 'Institution';
    }

    class Institution extends Satori {
        protected $mDbTableAlias = 'institutions';
    }

?>
