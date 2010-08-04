<activities>
<? foreach ( $activities as $activity ): ?>
    <activity>
        <user id="<?= $activity[ 'user' ][ 'id' ] ?>"></user><?
        switch ( $activity[ 'typeid' ] ):

        case ACTIVITY_COMMENT:
        ?><type>comment</type>
        <comment id="<?= $activity[ 'comment' ][ 'id' ] ?>">
            <text><?= $activity[ 'comment' ][ 'text' ] ?></text>
            <? include 'views/activity/itemview.php'; ?>
        </comment>
        <?
        break;

        case ACTIVITY_FAVOURITE:
        ?><type>favourite</type><?
        include 'views/activity/itemview.php';
        break;

        case ACTIVITY_FRIEND:
        ?><type>friend</type>
        <friend id="<?= $activity[ 'friend' ][ 'id' ] ?>">
            <name><?= $activity[ 'friend' ][ 'name' ] ?></name>
            <subdomain><?= $activity[ 'friend' ][ 'subdomain' ] ?></subdomain>
        </friend>
        <?
        break;

        case ACTIVITY_FAN:
        ?><type>fan</type>
        <friend id="<?= $activity[ 'fan' ][ 'id' ] ?>">
            <name><?= $activity[ 'fan' ][ 'name' ] ?></name>
            <subdomain><?= $activity[ 'fan' ][ 'subdomain' ] ?></subdomain>
        </friend>
        <?
        break;

        case ACTIVITY_SONG:
        ?><type>song</type>
        <song id="<?= $activity[ 'song' ][ 'id' ] ?>">
            <title><?= htmlspecialchars( $activity[ 'song' ][ 'title' ] ) ?></title>
        </song>
        <?
        break;

        case ACTIVITY_STATUS:
        ?><type>status</type>
        <status id="<?= $activity[ 'status' ][ 'id' ] ?>">
            <message><?= htmlspecialchars( $activity[ 'status' ][ 'message' ] ) ?></message>
        </status>
        <?
        break;

        case ACTIVITY_ITEM:
        ?><type>item</type>
        <? include 'views/activity/itemview.php';
        break;

        endswitch; ?>
    </activity>
    <?  endforeach; ?>
</activities>
