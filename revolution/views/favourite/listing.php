<stream type="favourites">
    <author>
        <name><?= $username ?></name>
        <? if ( isset( $user[ 'gender' ] ) ): ?>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? endif; ?>
    </author>
	<? include 'models/wysiwyg.php'; ?>
    <? foreach ( $favourites as $favourite ): ?>
	<? switch ( $favourite[ 'typeid' ] ):
    case TYPE_JOURNAL: 
    ?>

    <entry id="<?= $favourite[ "id" ] ?>" type="journal">
		<title><?= htmlspecialchars( $favourite[ "data" ][ "title" ] ) ?></title>
		<text><?= WYSIWYG_PresentAndSubstr( $favourite[ "data" ][ "text" ], 400 ) ?></text>
		<created><?= $favourite[ "data" ][ "created" ] ?></created>
		<url><?= $favourite[ "data" ][ "url" ] ?></url>
		<numcomments><?= $favourite[ "data" ][ "numcomments" ] ?></numcomments>
		<itemid><?= $favourite[ "data" ][ "id" ] ?></itemid>
	<?
	break;
    case TYPE_PHOTO: 
    ?>

    <entry id="<?= $favourite[ "id" ] ?>" type="photo">
		<title><?= htmlspecialchars( $favourite[ "data" ][ "title" ] ) ?></title>
		<created><?= $favourite[ "data" ][ "created" ] ?></created>
		<numcomments><?= $favourite[ "data" ][ "numcomments" ] ?></numcomments>
		<itemid><?= $favourite[ "data" ][ "id" ] ?></itemid>
        <media url="http://images2.zino.gr/media/<?= $favourite[ 'data' ][ 'userid' ] ?>/<?= $favourite[ 'data' ][ 'id' ] ?>/<?= $favourite[ 'data' ][ 'id' ] ?>_150.jpg" />
	<?
	break;
	case TYPE_POLL: 
    ?>

    <entry id="<?= $favourite[ "id" ] ?>" type="poll">
	<?
	break;
	case TYPE_STOREITEM: 
    ?>

    <entry id="<?= $favourite[ "id" ] ?>" type="storeitem">
	<?
	break;
	endswitch; ?>

		<author>
			<name><?= $favourite[ "data" ][ "username" ] ?></name>
			<subdomain><?= $favourite[ "data" ][ "subdomain" ] ?></subdomain>
			<gender><?= $favourite[ "data" ][ "gender" ] ?></gender>
			<avatarid><?= $favourite[ "data" ][ "avatarid" ] ?></avatarid>
		</author>

	</entry>
	<? endforeach; ?>
</stream>
