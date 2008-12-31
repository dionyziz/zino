<?php
    class ElementiPhoneShoutboxNew extends Element {
        public function Render() {
            global $page;

            ?>
            <form action="do/shoutbox/new" method="post">
                <input type="submit" value="Σχολίασε" />
                <textarea name="text">Άγγιξε εδώ για να γράψεις...</textarea>
            </form>
            <?php
            $page->AttachInlineScript( 'iPhone.Frontpage.OnLoad();' );
        }
    }
?>
