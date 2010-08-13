<? switch ( $activity[ 'item' ][ 'typeid' ] ):
    case TYPE_JOURNAL:
        ?><journal id="<?= $activity[ 'item' ][ 'id' ] ?>">
            <title><?= htmlspecialchars( $activity[ 'item' ][ 'title' ] ) ?></title>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url>
            <? if ( isset( $activity[ 'item' ][ 'text' ] ) ): ?>
            <text><?= $activity[ 'item' ][ 'text' ] ?></text>
            <? endif; ?>
        </journal><?
        break;
    case TYPE_POLL:
        ?><poll id="<?= $activity[ 'item' ][ 'id' ] ?>">
            <question><?= htmlspecialchars( $activity[ 'item' ][ 'title' ] ) ?></question>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url>
        </poll><?
        break;
    case TYPE_PHOTO:
        ?><photo id="<?= $activity[ 'item' ][ 'id' ] ?>">
        <? if ( !empty( $activity[ 'item' ][ 'user' ][ 'id' ] ) ): ?>
            <author id="<?= $activity[ 'item' ][ 'user' ][ 'id' ] ?>">
                <name><?= $activity[ 'item' ][ 'user' ][ 'name' ] ?></name>
                <gender><?= $activity[ 'item' ][ 'user' ][ 'gender' ] ?></gender>
            </author>
        <? endif; ?>
        <? if ( !empty( $activity[ 'item' ][ 'title' ] ) ): ?>
            <title><?= htmlspecialchars( $activity[ 'item' ][ 'title' ] ) ?></title>
        <? endif; ?>
        <? if ( !empty( $activity[ 'item' ][ 'url' ] ) ): ?>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url> 
        <? endif; ?>
        </photo><?
        break;
    case TYPE_ALBUM:
        ?><album id="<?= $activity[ 'item' ][ 'id' ] ?>">
            <name><?= htmlspecialchars( $activity[ 'item' ][ 'title' ] ) ?></name>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url>
        </album><?
        break;
    case TYPE_USERPROFILE:
        ?><profile id="<?= $activity[ 'item' ][ 'user' ][ 'id' ] ?>">
            <name><?= $activity[ 'item' ][ 'user' ][ 'name' ] ?></name>
        </profile><?
        break;
endswitch;
