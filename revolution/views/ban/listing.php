<banned>
    <?php foreach ( $banned as $user ): ?>
	<ban id ="<?= $user[ 'id' ]; ?>">
		<user>
			<id><?= $user[ 'userid' ]; ?></id>
			<rights><?= $user[ 'rights' ]; ?></rights>
			<started><?= $user[ 'started' ]; ?></started>
			<expire><?= $user[ 'expire' ]; ?></expire>
			<reason><?= $user[ 'reason' ]; ?></reason>
			<admin><?= $user[ 'admin' ]; ?></admin>
		</user>
	</ban>
	<?php endforeach; ?>
</banned>
