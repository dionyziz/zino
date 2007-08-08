<div class="box latestcomments" id="latestcomments">
	<div class="header">
		<div style="float:right"><img src="images/soraright.jpg" /></div>
		<div style="float:left"><img src="images/soraleft.jpg" /></div>
		<h3>Νεότερα σχόλια</h3>
	</div>
	<div class="body">
		<div>
			<a href="" onclick="return false;" title="candy"><img src="images/candy.jpg" class="avatar" style="width:16px;height:16px" /></a>
			στο
			<a href="" onclick="return false;">Naruto</a><span>, πριν ένα λεπτό</span>
			<div style="clear:left">
			</div>
		</div>
		<div>
			<a href="" onclick="return false;" title="dionyziz"><img src="images/dionyziz.jpg" class="avatar" style="width:16px;height:16px" /></a>
			στο
			<a href="" onclick="return false;">World Trade Center</a><span>, πριν 2 λεπτά</span>
			<div style="clear:left">
			</div>
		</div>
		<div>
			<a href="" onclick="return false;" title="titi"><img src="images/titi.jpg" class="avatar" style="width:16px;height:16px" /></a>
			στο
			<a href="" onclick="return false;">Magic the Gathering</a><span>, πριν 5 λεπτά</span>
			<div style="clear:left">
			</div>
		</div>
		<a href="" class="arrow" onclick="ShowLatestComments();return false" title="Περισσότερα σχόλια"></a>
	</div>
</div>
<script type="text/javascript"><?php
ob_start();
?>
function ShowLatestComments() {
    shoutbox = document.getElementById( 'shoutbox' );
	onlinenow = document.getElementById( 'onlinenow' );
    latest = document.getElementById('latestcomments');
    inith = shoutbox.offsetHeight;
    Animations.Create( shoutbox, 'opacity', 1000, 1, 0, new Function (), Interpolators.Sin );
	Animations.Create( onlinenow, 'opacity', 1000, 1, 0, new Function (), Interpolators.Sin );
    Animations.Create( shoutbox, 'height', 3000, inith, 0, new Function (), Interpolators.Pulse );
    divs = latest.getElementsByTagName('div');
    for ( i in divs ) {
        div = divs[ i ];
        if ( div.className == 'body' ) {
            firstcomment = div.getElementsByTagName( 'div' )[ 0 ];
            for ( i = 0 ; i < 10 ; ++i ) {
                div.appendChild( firstcomment.cloneNode( true ) );
            }
            break;
        }
    }
    as = latest.getElementsByTagName('a');
    for ( i in as ) {
        a = as[ i ];
        if ( a.className == 'arrow' ) {
            a.style.display = 'none';
            break;
        }
    }
}
<?php
$js = ob_get_clean();
// echo htmlspecialchars( $js );
?>
</script>
