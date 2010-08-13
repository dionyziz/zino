<albums>
    <author id="<?= $user[ 'id' ]; ?>">
        <name><?= $user[ 'name' ]; ?></name>
    </author>
    <?php foreach ( $albums as $album ): ?>
    <album id="<?= $album[ 'id' ]; ?>"<?
    if ( isset( $album[ 'egoalbum' ] ) && $album[ 'egoalbum' ] ): 
    ?> egoalbum="yes"<?
    endif;
    ?>>
        <name><?= htmlspecialchars( $album[ 'name' ] ); ?></name>  
        <author id="<?= $album[ 'ownerid' ] ?>">
            <type id="<?= $album[ 'ownertype' ] ?>"><?php
            switch ( $album[ 'ownertype' ] ) {
                case TYPE_USERPROFILE:
                    ?>user<?php
                    break;
                case TYPE_SCHOOL:
                    ?>school<?php
                    break;
                default:
                    ?>unknown<?php
            }
            ?></type>
        </author>
        <?php if ( $album[ 'mainimageid' ] > 0 ): ?>
        <!-- TODO: remove photos tag -->
        <photos count="<?= $album[ 'numphotos' ] ?>">
            <photo main="yes" id="<?= $album[ 'mainimageid' ] ?>">
                <media url="http://images2.zino.gr/media/<?= $album[ 'ownerid' ] ?>/<?= $album[ 'mainimageid' ] ?>/<?= $album[ 'mainimageid' ] ?>_150.jpg" />
            </photo>
        </photos>
        <?php endif; ?>
    </album>
    <?php endforeach; ?>
</albums>
