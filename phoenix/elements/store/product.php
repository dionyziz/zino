<?php
    class ElementStoreProduct extends Element {
        public function Render( tString $name ) {
            global $libs;
            
            $libs->Load( 'store' );
            $name = $name->Get();
            
            switch ( $name ) {
                case 'necklace':
                    break;
                default:
                    return Element( '404' );
            }
            
            ?>
        <h1>
            <div class="city">
                <div class="cityend1">
                </div>
            </div>
            <span>
                <img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino" />
                <img src="http://static.zino.gr/phoenix/store/store.png" alt="STORE" />
            <span>
        </h1>
        <a class="back" href="http://www.zino.gr/">πίσω στο zino</a>
        <div class="content">
            <div class="productimage">
                <img src="http://static.zino.gr/phoenix/store/necklace.jpg" alt="Necklace φυσαλίδα" />
            </div>
            <div class="productdetails">
                <h2>Necklace φυσαλίδα <span><img src="http://static.zino.gr/phoenix/store/15euros.png" alt="15€" /></span></h2>
                <ul class="toolbox">
                    <li class="lurv"><a href="" onclick="return false;">Το αγαπώ</a></li>
                    <li class="wantz"><a href="" onclick="return false;">Το θέλω</a></li>
                </ul>
                <div class="description">
                    <p>
                        Η φυσαλίδα του Zino διατίθεται σε 
                        ένα σχέδιο από πολυμορφικό πηλό για το λαιμό, 
                        είτε είσαι αγόρι είτε κορίτσι, στο χρώμα του 
                        Zino και με λευκό περίγραμμα.
                    </p><p>
                        To σχέδιο είναι συλλεκτικό και διατίθεται στον περιορισμένο 
                        αριθμό των <strong>32 κομματιών</strong>.
                        Αφού πουληθούν όλα τα κομμάτια, το συγκεκριμένο σχέδιο δεν θα ξαναβγεί.
                        Κάθε ένα από τα 32 κομμάτια είναι 
                        χειροποίητο από την καλλιτέχνιδα <a href="http://toothfairy-creations.zino.gr/">Gardy</a>.
                    </p><p class="please">
                        <strong>Το Zino σ' αγαπάει:</strong> Η προσφορά σου είναι ζωτικής σημασίας 
                        για εμάς. Τα έσοδα από την αγορά είναι απαραίτητα για να 
                        συνεχίσει να ζει το Zino. Τα χρήματα θα διατεθούν για την 
                        βελτίωση και διατήρηση του Zino.
                    </p>
                </div>
            </div>
            <div class="eof"></div>
            <h3 class="lurv">Το αγαπάνε:</h3>
            <ul class="lurv">
                <li><a href="http://phil-marz.zino.gr/" title="Phil_marz">
                    <img src="http://images2.zino.gr/media/974/100753/100753_100.jpg" width="50" height="50" alt="Phil_marz" />
                </a></li>
                <li><a href="http://maybeshewill.zino.gr/" title="Maybeshewill">
                    <img src="http://images2.zino.gr/media/10759/198754/198754_100.jpg" width="50" height="50" alt="Maybeshewill" />
                </a></li>
                <li><a href="http://mel-theboof.zino.gr/" title="MeL_TheBoof">
                    <img src="http://images2.zino.gr/media/12124/193844/193844_100.jpg" width="50" height="50" alt="MeL_TheBoof" />
                </a></li>
                <li><a href="http://KaptenDiesel.zino.gr/" title="KaptenDiesel">
                    <img src="http://images2.zino.gr/media/6688/183876/183876_100.jpg" width="50" height="50" alt="KaptenDiesel" />
                </a></li>
            </ul>
            <h3 class="wantz">Το έχουν:</h3>
            <ul class="wantz">
                <li><a href="http://Antig0nh.zino.gr/" title="Antig0nh">
                    <img src="http://images2.zino.gr/media/5003/199204/199204_100.jpg" width="50" height="50" alt="Antig0nh" />
                </a></li>
                <li><a href="http://Nefeloumpa.zino.gr/" title="Nefeloumpa">
                    <img src="http://images2.zino.gr/media/7895/170657/170657_100.jpg" width="50" height="50" alt="Nefeloumpa" />
                </a></li>
                <li><a href="http://flame-ody.zino.gr/" title="fLaMe_OdY">
                    <img src="http://images2.zino.gr/media/6144/165096/165096_100.jpg" width="50" height="50" alt="fLaMe_OdY" />
                </a></li>
            </ul>
            <p class="remain">
                ...απομένουν 29 συλλεκτικά κομμάτια.
            </p>
        </div>
            <?php
        }
    }
?>
