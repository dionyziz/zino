<?php
    class ElementiPhoneShoutboxNew extends Element {
        public function Render() {
            ?>
            <form action="do/shoutbox/new" method="post">
                <input type="submit" value="Σχολίασε" />
                <textarea>Άγγιξε εδώ για να γράψεις...</textarea>
            </form>
            <?php
        }
    }
?>
