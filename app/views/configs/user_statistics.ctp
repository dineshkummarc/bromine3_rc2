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
<h1>Supplying anonymous user statistics makes Bromine better</h1>
<p>
We would like to get anonymous user statistics from all our users so please check the 'Enable reporting anonymous user statistics' checkbox below here.<br>
ALL DATA IS ANONYMOUS. We are using google analytics, see how it works here: <a href='http://www.google.com/support/googleanalytics/bin/answer.py?hl=en&answer=55539' target='_blank'>How google analytics works</a><br>
Furthermore we extract the following data from the database. <?php echo $ajax->link('See what data we are collecting', array('controller'=>'projects', 'action'=>'statistics'), array('update'=>'datadiv')); ?></a>
<div id='datadiv'></div>
We really hope you will help us make Bromine a better open source Test Management tool.
<br><br>
Thanks
<br><br>
The Bromine Team    
</p>

<?php
    echo $form->create('Config',array('action'=>'userStatistics'));
	echo "Enable anonymous user statistics ";
    $enableGA == 1 ? $enableGA = 'checked' : '';
    echo $form->checkbox('enableGA', array('checked' => $enableGA));
    echo $form->end('Submit');
?>


