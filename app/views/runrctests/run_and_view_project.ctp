<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kløft and Jeppe Poss Pedersen 

This file is part of Bromine.

Bromine is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Bromine is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bromine.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<h2>Your tests are being executed. You can close this window if you like.</h2>
<p>You can track the status of your tests below</p> 
<div id="results">
<img src='/img/ajax-loader.gif' />
</div>
<script type="text/javascript">
<?php
    echo "new Ajax.Request('/runrctests/runProject/$project_id/$suite_id');";
    echo "new Ajax.PeriodicalUpdater('results', '/suites/view/$suite_id', {method: 'get', frequency: 0.5, decay: 2});";
?>
</script>