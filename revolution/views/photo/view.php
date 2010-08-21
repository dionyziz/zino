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
            <name><?= htmlspecialchars( $album[ 'name' ] ) ?></name>
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
            <? if ( !empty( $photo[ 'previd' ] ) ): ?>
            <photo id="<?= $photo[ 'previd' ] ?>" navigation="previous" />
            <? endif; ?>
            <? if ( !empty( $photo[ 'nextid' ] ) ): ?>
            <photo id="<?= $photo[ 'nextid' ] ?>" navigation="next" />
            <? endif; ?>
        </photos>
        <? if ( isset( $userfriendship ) && $userfriendship === true ): ?>
        <friends><user id="<?= $me[ 'id' ] ?>" /></friends>
        <? endif; ?>
    </author>
    <? endif; ?>
    <published><?= $photo[ 'created' ] ?></published>
    <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_full.jpg" width="<?= $photo[ 'w' ] ?>" height="<?= $photo[ 'h' ] ?>" /><?
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
	<? if ( isset( $imagetags ) ): ?>
	<imagetags>
	<? foreach ( $imagetags as $tag ): ?>
		<imagetag>
			<id><?= $tag[ 'id' ] ?></id>
			<user id ="<?= $tag[ 'personid' ] ?>" >
				<name><?= $tag[ 'name' ] ?></name>
			</user>
			<created><?= $tag[ 'created' ] ?></created>
			<left><?= $tag[ 'tagleft' ] ?></left>
			<top><?= $tag[ 'tagtop' ] ?></top>
			<width><?= $tag[ 'width' ] ?></width>
			<height><?= $tag[ 'height' ] ?></height>
		</imagetag>
		<?  endforeach; ?>
	</imagetags>
    <? endif; ?>
</photo>
