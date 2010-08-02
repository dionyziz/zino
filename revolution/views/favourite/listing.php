<favourites>
    <author>
        <name><?= $username ?></name>
        <? if ( isset( $user[ 'gender' ] ) ): ?>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? endif; ?>
        <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
        <? if ( isset( $user[ 'avatarid' ] ) ): ?>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_150.jpg" />
        </avatar>
        <? endif; ?>
    </author>
	<? include 'models/wysiwyg.php'; ?>
    <? foreach ( $favourites as $favourite ): ?>
	<? switch ( $favourite[ 'typeid' ] ):
    case TYPE_JOURNAL: 
    ?>

    <journal id="<?= $favourite[ 'data' ][ "id" ] ?>">
		<title><?= htmlspecialchars( $favourite[ "data" ][ "title" ] ) ?></title>
		<text><?= WYSIWYG_PresentAndSubstr( $favourite[ "data" ][ "text" ], 400 ) ?></text>
		<created><?= $favourite[ "data" ][ "created" ] ?></created>
		<url><?= $favourite[ "data" ][ "url" ] ?></url>
		<numcomments><?= $favourite[ "data" ][ "numcomments" ] ?></numcomments>
	<?
	break;
    case TYPE_PHOTO: 
    ?>

    <photo id="<?= $favourite[ 'data' ][ "id" ] ?>">
		<title><?= htmlspecialchars( $favourite[ "data" ][ "title" ] ) ?></title>
		<created><?= $favourite[ "data" ][ "created" ] ?></created>
		<numcomments><?= $favourite[ "data" ][ "numcomments" ] ?></numcomments>
        <media url="http://images2.zino.gr/media/<?= $favourite[ 'data' ][ 'userid' ] ?>/<?= $favourite[ 'data' ][ 'id' ] ?>/<?= $favourite[ 'data' ][ 'id' ] ?>_150.jpg" />
	<?
	break;
	case TYPE_POLL: 
    ?>

    <poll id="<?= $favourite[ 'data' ][ "id" ] ?>">
	<?
	break;
	case TYPE_STOREITEM: 
    ?>

    <product id="<?= $favourite[ 'data' ][ "id" ] ?>">
	<?
	break;
	endswitch; ?>

		<author>
			<name><?= $favourite[ "data" ][ "username" ] ?></name>
			<subdomain><?= $favourite[ "data" ][ "subdomain" ] ?></subdomain>
			<gender><?= $favourite[ "data" ][ "gender" ] ?></gender>
			<avatarid><?= $favourite[ "data" ][ "avatarid" ] ?></avatarid>
		</author>
	<? switch ( $favourite[ 'typeid' ] ):
    case TYPE_JOURNAL: 
    ?></journal><?
	break;
    case TYPE_PHOTO: 
    ?></photo><?
	break;
	case TYPE_POLL: 
    ?></poll><?
	break;
	case TYPE_STOREITEM: 
    ?></product><?
	break;
	endswitch; ?>

	<? endforeach; ?>
</favourites>
