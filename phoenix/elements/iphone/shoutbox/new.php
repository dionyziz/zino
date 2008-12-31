<?php
    class ElementiPhoneShoutboxNew extends Element {
        public function Render() {
            ?>
            <form action="do/shoutbox/new" method="post">
                <input type="submit" value="Σχολίασε" />
                <textarea name="text" onclick="this.innerText=''">Άγγιξε εδώ για να γράψεις...</textarea>
            </form>
            <?php
        }
    }
?>
