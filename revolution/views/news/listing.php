<feed>
	<? foreach ( $polls as $item ): ?>
    <entry type="poll" id="<?= $item[ 'id' ]; ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
        <url><?= $item[ 'url' ]; ?></url>
        <question><?= $item[ 'question' ]; ?></question>
    </entry>
    <? endforeach; ?>
	<? foreach ( $journals as $item ): ?>
    <entry type="journal" id="<?= $item[ 'id' ]; ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
        <title><?= $item[ 'title' ]; ?></title>
    </entry>
    <? endforeach; ?>
</feed>
