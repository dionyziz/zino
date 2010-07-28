<stream>
    <? foreach ( $favourites as $favourite ): ?>
	<? switch ( $favourite[ 'typeid' ] ):
    case TYPE_JOURNAL: 
    ?>

    <entry id="<?= $favourite[ "data" ][ "id" ] ?>" type="journal">
		<title><?= $favourite[ "data" ][ "title" ] ?></title>
		<created><?= $favourite[ "data" ][ "created" ] ?></created>
		<url><?= $favourite[ "data" ][ "url" ] ?></url>
		<numcomments><?= $favourite[ "data" ][ "numcomments" ] ?></numcomments>
	<?
	break;
    case TYPE_PHOTO: 
    ?>

    <entry id="<?= $favourite[ "data" ][ "id" ] ?>" type="photo">
	<?
	break;
	case TYPE_POLL: 
    ?>

    <entry id="<?= $favourite[ "data" ][ "id" ] ?>" type="poll">
	<?
	break;
	case TYPE_STOREITEM: 
    ?>

    <entry id="<?= $favourite[ "data" ][ "id" ] ?>" type="storeitem">
	<?
	break;
	endswitch; ?>

		<author>
			<id><?= $favourite[ "data" ][ "userid" ] ?></id>
			<name><?= $favourite[ "data" ][ "username" ] ?></name>
			<subdomain><?= $favourite[ "data" ][ "subdomain" ] ?></subdomain>
			<gender><?= $favourite[ "data" ][ "gender" ] ?></gender>
			<avatarid><?= $favourite[ "data" ][ "avatarid" ] ?></avatarid>
		</author>

	</entry>
	<? endforeach; ?>
</stream>
