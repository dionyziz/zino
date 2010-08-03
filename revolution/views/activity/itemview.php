<? switch ( $activity[ 'item' ][ 'typeid' ] ):
    case TYPE_JOURNAL:
        ?><journal id="<?= $activity[ 'item' ][ 'id' ] ?>">
            <title><?= $activity[ 'item' ][ 'title' ] ?></title>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url>
            <? if ( isset( $activity[ 'item' ][ 'text' ] ) ): ?>
            <text><?= $activity[ 'item' ][ 'text' ] ?></text>
            <? endif; ?>
        </journal><?
        break;
    case TYPE_POLL:
        ?><poll id="<? $activity[ 'item' ][ 'id' ] ?>">
            <question><?= $activity[ 'item' ][ 'title' ] ?></question>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url>
        </poll><?
        break;
    case TYPE_PHOTO:
        ?><photo id="<? $activity[ 'item' ][ 'id' ] ?>">
            <title><?= $activity[ 'item' ][ 'title' ] ?></title>
            <url><?= $activity[ 'item' ][ 'url' ] ?></url> 
        </photo><?
        break;
endswitch;
