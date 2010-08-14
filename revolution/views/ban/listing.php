<banned>
    <?php foreach ( $banned as $user ): ?>
	<ban id ="<?= $user[ 'id' ]; ?>">
		<user id ="<?= $user[ 'userid' ]; ?>">
			<name><?= $user[ 'name' ]; ?></name>			
		</user>
		<started><?= $user[ 'started' ]; ?></started>
		<expire><?= $user[ 'expire' ]; ?></expire>
		<reason><?= $user[ 'reason' ]; ?></reason>
		<admin><?= $user[ 'admin' ]; ?></admin>
	</ban>
	<?php endforeach; ?>
</banned>
