<?php

	class ElementDeveloperAbresasMassComments extends Element {
		public function Render() {
			?><h2>Mass Comments</h2>
			<form method="post" action="/phoenix/do/comment/fill">
				Typeid: <select name="typeid">
				<option value="4">Journal</option>
				<option value="1">Poll</option>
				<option value="2">Image</option>
				<option value="3">User Profile</option>
				</select><br />
				Itemid: <input type="text" name="itemid" /><br /><br />
				<input type="submit" value="Fill with comments!" />
			</form><?php
		}

	}
?>
