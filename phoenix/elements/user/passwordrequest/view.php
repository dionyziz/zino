<?php
    class ElementUserPasswordRequestView extends Element {
        public function Render() {
            ?><h2>Επαναφορά κωδικού πρόσβασης</h2>
            <form action="do/user/password/request" method="post" style="padding-bottom: 20px">
                <p>
                    Πληκτρολόγησε το ψευδώνυμό σου:
                    <input type="text" value="" name="username" />
                    <input type="submit" value="Επαναφορά" />
                </p>
            </form><?php
        }
    }
?>
