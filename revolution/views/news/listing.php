<feed>
    <? foreach ( $news as $item ): ?>
    <entry type="<?= $item[ 'type' ] ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain<?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
    </entry>
    <? endforeach; ?>
	<? foreach ( $polls as $item ): ?>
    <entry type="poll">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain<?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
    </entry>
    <? endforeach; ?>
	<? foreach ( $news as $item ): ?>
    <entry type="<?= $item[ 'type' ] ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain<?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
    </entry>
    <? endforeach; ?>
</feed>
