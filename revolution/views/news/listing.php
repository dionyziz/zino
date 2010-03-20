<feed>
	<? foreach ( $polls as $item ): ?>
    <entry type="poll">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
        <question><?= $item[ 'question' ]; ?></question>
    </entry>
    <? endforeach; ?>
	<? foreach ( $journals as $item ): ?>
    <entry type="journal">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
        <title><?= $item[ 'title' ]; ?></title>
    </entry>
    <? endforeach; ?>
</feed>
