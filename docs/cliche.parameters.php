<?php

$

?>

<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
	<tr>
		<th>Task Details</th>
		<th>Progress</th>
		<th>Vital Task</th>
	</tr><!-- Table Header -->
    
	<tr>
		<td>Create pretty table design</td>
		<td>100%</td>
		<td>Yes</td>
	</tr><!-- Table Row -->
	
	<tr class='even'><td>Take the dog for a walk</td><td>100%</td><td>Yes</td></tr><!-- Darker Table Row -->

	<tr><td>Waste half the day on Twitter</td><td>20%</td><td>No</td></tr>
	<tr class='even'><td>Feel inferior after viewing Dribble</td><td>80%</td><td>No</td></tr>
	
    <tr><td>Wince at "to do" list</td><td>100%</td><td>Yes</td></tr>
	<tr class='even'><td>Vow to complete personal project</td><td>23%</td><td>yes</td></tr>

	<tr><td>Procrastinate</td><td>80%</td><td>No</td></tr>
    <tr class='even'><td><a href="#yep-iit-doesnt-exist">Hyperlink Example</a></td><td>80%</td><td><a href="#inexistent-id">Another</a></td></tr>
</table>


<table cellspacing='0'> 
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th>Default Value</th>
	</tr><!-- Table Header -->
	[[+tbody]]
</table>

<tr class="[[+class]]">
	<td>[[+name]]</td>
	<td>[[+description]]</td>
	<td>[[+default]]</td>
</tr><!-- Table Row -->