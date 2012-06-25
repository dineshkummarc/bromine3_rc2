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
<?php
$id = uniqid('site');
echo "<div class='input text' id='$id'>";
echo "<input name='data[Newsites][]' type='text' value='http://www.' />"; 
echo $html->link( 
                'Remove', 
                '#',
                array('onclick' => "
                    if(confirm('Are you sure you want to remove this site?')){ 
                        $('$id').remove();
                    }"
                )
            );
echo "</div>";

 ?>