<?php
    class ElementUserPasswordRequestView extends Element {
        public function Render() {
            ?><h2>Επαναφορά κωδικού πρόσβασης</h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    Πληκτρολόγησε το ψευδώνυμό σου:
                    <input type="text" value="" name="username" />
                    <input type="submit" value="Επαναφορά" />
                </p>
            </form><?php
        }
    }
?>
