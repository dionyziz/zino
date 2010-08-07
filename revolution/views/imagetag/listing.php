<imagetags photoid="<?= $photoid ?>" >
<? foreach ( $tags as $tag ): ?>
    <imagetag>
		<id><?= $tag[ 'id' ] ?></id>
		<imageid><?= $tag[ 'imageid' ] ?></imageid>
		<personid><?= $tag[ 'personid' ] ?></personid>
		<ownerid><?= $tag[ 'ownerid' ] ?></ownerid>
		<created><?= $tag[ 'created' ] ?></created>
		<left><?= $tag[ 'tagleft' ] ?></left>
		<top><?= $tag[ 'tagtop' ] ?></top>
		<width><?= $tag[ 'width' ] ?></width>
		<height><?= $tag[ 'height' ] ?></height>
	</imagetag>
    <?  endforeach; ?>
</imagetags>
