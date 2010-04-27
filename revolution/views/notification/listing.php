<feed type="notify" count="<?= count( $notifications ) ?>">
    <? foreach ( $notifications as $notification ): ?>
    <? if ( $notification[ 'eventtype' ] == 'EVENT_COMMENT_CREATED' ): ?>

    <entry type="<?
    switch ( $notification[ 'comment' ][ 'typeid' ] ):
        case TYPE_IMAGE:
            ?>image<?
            break;
        case TYPE_POLL:
            ?>poll<?
            break;
        case TYPE_USERPROFILE:
            ?>user<?
            break;
        case TYPE_JOURNAL:
            ?>user<?
            break;
    endswitch;
    ?>" id="<?= $notification[ 'comment' ][ 'itemid' ] ?>">
        <discussion>
            <comment id="<?= $notification[ 'comment' ][ 'parentid' ] ?>">
                <?
                    $comment = $notification[ 'comment' ];
                    $user = $notification[ 'user' ];
                    include 'views/comment/view.php';
                ?>
            </comment>
        </discussion>
    </entry>
    <? endif; ?>
    <? endforeach; ?>

</feed>
