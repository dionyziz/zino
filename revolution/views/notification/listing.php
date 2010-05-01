<feed type="notify" count="<?= count( $notifications ) ?>">
    <? foreach ( $notifications as $notification ): ?>
    <? if ( $notification[ 'eventtype' ] == 'EVENT_COMMENT_CREATED' ): ?>

    <entry type="<?
    switch ( $notification[ 'comment' ][ 'typeid' ] ):
        case TYPE_IMAGE:
            ?>photo<?
            break;
        case TYPE_POLL:
            ?>poll<?
            break;
        case TYPE_USERPROFILE:
            ?>user<?
            break;
        case TYPE_JOURNAL:
            ?>journal<?
            break;
    endswitch;
    ?>" id="<?= $notification[ 'comment' ][ 'itemid' ] ?>">
        <discussion>
            <? if ( $notification[ 'comment' ][ 'parentid' ] != 0 ): ?>
            <comment id="<?= $notification[ 'comment' ][ 'parentid' ] ?>">
            <? endif; ?>
                <?
                    $comment = $notification[ 'comment' ];
                    $user = $notification[ 'user' ];
                    include 'views/comment/view.php';
                ?>
            <? if ( $notification[ 'comment' ][ 'parentid' ] != 0 ): ?>
            </comment>
            <? endif; ?>
        </discussion>
    </entry>
    <? endif; ?>
    <? endforeach; ?>

</feed>
