<notifications count="<?= $count ?>">
    <? foreach ( $notifications as $notification ): ?>
    <notification type="<?
    switch ( $notification[ 'eventtypeid' ] ):
        case EVENT_COMMENT_CREATED:
            ?>comment<?
            break;
        case EVENT_FAVOURITE_CREATED:
            ?>favourite<?
            break;
        case EVENT_FRIENDRELATION_CREATED:
            ?>friend<?
    endswitch;
    ?>" id="<?= $notification[ 'id' ] ?>">
    <? switch ( $notification[ 'eventtypeid' ] ):
    case EVENT_COMMENT_CREATED: 
    switch ( $notification[ 'comment' ][ 'typeid' ] ):
        case TYPE_PHOTO:
            $type = 'photo';
            break;
        case TYPE_POLL:
            $type = 'poll';
            break;
        case TYPE_USERPROFILE:
            $type = 'user';
            break;
        case TYPE_JOURNAL:
            $type = 'journal';
            break;
    endswitch;
    ?>
    <<?= $type ?> id="<?= $notification[ 'comment' ][ 'itemid' ] ?>">
        <discussion>
            <? if ( $notification[ 'comment' ][ 'parentid' ] != 0 ): ?>
            <comment id="<?= $notification[ 'comment' ][ 'parentid' ] ?>">
            <? if ( $notification[ 'comment' ][ 'parent' ][ 'text' ] ): ?>
                <text><?= $notification[ 'comment' ][ 'parent' ][ 'text' ]; ?></text>
                <author id="<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'id' ] ?>">
                    <name><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'name' ]; ?></name>
                    <? if ( !empty( $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'gender' ] ) ): ?>
                    <gender><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'gender' ]; ?></gender>
                    <? endif; ?>
                    <subdomain><?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'subdomain' ]; ?></subdomain>
                    <? if ( !empty( $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'avatarid' ] ) ): ?>
                    <avatar>
                        <media url="http://images2.zino.gr/media/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'id' ]; ?>/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'avatarid' ]; ?>/<?= $notification[ 'comment' ][ 'parent' ][ 'user' ][ 'avatarid' ]; ?>_100.jpg" />
                    </avatar>
                    <? endif; ?>
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
    </<?= $type ?>>
    <? 
    break;
    case EVENT_FAVOURITE_CREATED: 
    switch ( $notification[ 'favourite' ][ 'typeid' ] ):
        case TYPE_PHOTO:
            $type = 'photo';
            break;
        case TYPE_POLL:
            $type = 'poll';
            break;
        case TYPE_JOURNAL:
            $type = 'journal';
            break;
    endswitch;
    ?>
    <<?= $type ?> id="<?= $notification[ 'favourite' ][ 'itemid' ] ?>">
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
    </<?= $type ?>>
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
    </notification>
    <? endforeach; ?>
</notifications>
