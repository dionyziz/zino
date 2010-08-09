<stream type="notify" count="<?= $count ?>">
    <? foreach ( $notifications as $notification ): ?>
    <? switch ( $notification[ 'eventtypeid' ] ):
    case EVENT_COMMENT_CREATED: 
    ?>

    <entry type="<?
    switch ( $notification[ 'comment' ][ 'typeid' ] ):
        case TYPE_PHOTO:
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
            <? if ( $notification[ 'comment' ][ 'parent' ][ 'text' ] ): ?>
                <text><?= $notification[ 'comment' ][ 'parent' ][ 'text' ]; ?></text>
                <author id="<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'id' ] ?>">
                    <name><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'name' ]; ?></name>
                    <gender><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'gender' ]; ?></gender>
                    <subdomain><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'subdomain' ]; ?></subdomain>
                    <avatar>
                        <media url="http://images2.zino.gr/media/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'id' ]; ?>/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'avatarid' ]; ?>/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'avatarid' ]; ?>" />
                    </avatar>
                </author>
            <? endif; ?>
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
    <? 
    break;
    case EVENT_FAVOURITE_CREATED: 
    ?>

    <entry type="<?
    switch ( $notification[ 'favourite' ][ 'typeid' ] ):
        case TYPE_PHOTO:
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
    ?>" id="<?= $notification[ 'favourite' ][ 'itemid' ] ?>">
        <favourites>
            <user id="<?= $notification[ 'favourite' ][ 'user' ][ 'id' ] ?>">
                <?
                    $user = $notification[ 'favourite' ][ 'user' ];
                ?>
                <name><?= $user[ 'name' ] ?></name>
                <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
                <gender><?= $user[ 'gender' ] ?></gender>
                <age><?= $user[ 'age' ] ?></age>
                <? if ( isset( $user[ 'place' ] ) && isset( $user[ 'place' ][ 'name' ] ) ): ?>
                <location><?= $user[ 'place' ][ 'name' ] ?></location>
                <? endif; ?>
                <? if ( $user[ 'avatarid' ] ): ?>
                <avatar id="<?= $user[ 'avatarid' ] ?>">
                    <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
                </avatar>
                <? endif; ?>
            </user>
        </favourites>
    </entry>
    <?
    break;
    case EVENT_FRIENDRELATION_CREATED:
        $user = $notification[ 'friendship' ][ 'user' ];
    ?>
        <user id="<?= $user[ 'id' ] ?>">
            <name><?= $user[ 'name' ] ?></name>
            <? if ( $user[ 'avatarid' ] ): ?>
            <avatar id="<?= $user[ 'avatarid' ] ?>">
                <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
            <? endif; ?>
            <gender><?= $user[ 'gender' ]; ?></gender>
            <age><?= $user[ 'age' ]; ?></age>
            <location><?= $user[ 'place' ][ 'name' ]; ?></location>
            <knows>
                <user id="<?= $notification[ 'friendship' ][ 'friend' ][ 'id' ] ?>" />
            </knows>
        </user>
    <?
    break;
    endswitch; ?>
    <? endforeach; ?>

</stream>
