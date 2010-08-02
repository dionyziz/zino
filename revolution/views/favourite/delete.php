<operation resource="favourite" method="delete">
    <? if ( empty( $error ) ): ?>
        <result>SUCCESS</result>
        <? switch ( $favourite[ 'typeid' ] ):
            case TYPE_JOURNAL:
                ?><journal id="<?= $itemid ?>" /><?
                break;
            case TYPE_PHOTO:
                ?><photo id="<?= $itemid ?>" /><?
                break;
            case TYPE_POLL:
                ?><poll id="<?= $itemid ?>" /><?
                break;
        endswitch;
    else: ?>
        <result>FAIL</result>
    <? endif; ?>
</operation>
