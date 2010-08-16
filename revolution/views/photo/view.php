<? global $settings ?>
<? if ( !empty( $photo[ 'deleted' ] ) ): ?>
<photo deleted="yes" />
<? return;
   endif; ?>
<photo id="<?= $photo[ 'id' ] ?>">
    <? if( $photo[ 'title' ] != '' ): ?>
        <title><?= htmlspecialchars( $photo[ 'title' ] ) ?></title>
    <? endif; ?>
    <? if ( isset( $album ) ): ?>
    <containedWithin>
        <album id="<?= $album[ 'id' ] ?>"
        <? if ( !empty( $album[ 'egoalbum' ] ) ): ?>
         egoalbum="yes"
        <? endif; ?>>
            <name><?= $album[ 'name' ] ?></name>
        </album>
    </containedWithin>
    <? endif; ?>
    <? if ( isset( $user ) ): ?>
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? if ( !empty( $user[ 'avatarid' ] ) ): ?>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
        <? endif; ?>
        <photos>
            <? if ( !empty( $photo[ 'previousid' ] ) ): ?>
            <photo id="<?= $photo[ 'previousid' ] ?>" navigation="previous" />
            <? endif; ?>
            <? if ( !empty( $photo[ 'nextid' ] ) ): ?>
            <photo id="<?= $photo[ 'nextid' ] ?>" navigation="next" />
            <? endif; ?>
        </photos>
    </author>
    <? endif; ?>
    <published><?= $photo[ 'created' ] ?></published>
    <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_full.jpg" width="<?= $photo[ 'w' ] ?>" height="<?= $photo[ 'h' ] ?>" /><?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</photo>
