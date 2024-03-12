<?php
/**
 * SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage fieldsets
 * @file       default_playgroundnotiz.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013-2023 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;

//echo '<pre>'.print_r($this->playgroundnotic,true).'</pre';

echo 'Hier können Sie die Notizen zum Stadion hinterlegen. Wie Name oder unterschiedliche Kapazitäten.<br>';

?>
<script type="text/javascript">
        function addRows()
        {
            // Reference to the table body
var body = jQuery("#playgroundnotic").find('tbody');
// Create a new row element
var row = jQuery('<tr>');
// Create a new column element
var column = jQuery('<td>');
// Create a new image element
var image = jQuery('<input>');
image.attr('type', 'text');
image.attr('id', 'id');          
image.attr('type', 'text');
image.attr('disabled', 'disabled');   

// Append the image to the column element
column.append(image);
// Append the column to the row element
row.append(column);

var column1 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'playground_id');
input.attr('name', 'playground_id[]');
input.attr('disabled', 'disabled');          
input.attr('value', '<?php echo $this->item->id; ?>');          
column1.append(input);          
row.append(column1); 
          
var column2 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'date_von');
input.attr('name', 'date_von[]');
column2.append(input);          
row.append(column2);          

var column3 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'date_bis');
input.attr('name', 'date_bis[]');
column3.append(input);          
row.append(column3);                    
          
var column4 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'name_visitors');
input.attr('name', 'name_visitors[]');
column4.append(input);          
row.append(column4);            
          
          
var column5 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'notes');
input.attr('name', 'notes[]');
column5.append(input);          
row.append(column5); 
          
          
var column6 = jQuery('<td>');          
var input = jQuery('<input>');          
input.attr('type', 'text');          
input.attr('id', 'max_visitors');
input.attr('name', 'max_visitors[]');
input.attr('size', '10');          
column6.append(input);          
row.append(column6); 
          
          
// Append the row to the table body
body.append(row);
        }
  
        </script>

<input type="button" value="Add Rows" onclick="addRows()" />
<table class="table table-striped" id="playgroundnotic">
  <thead>
    <tr>
    
  <th>
    id
    </th>
    <th>
    playground_id
    </th>
    <th>
    date_von
    </th>
    <th>
    date_bis
    </th>
    <th>
    name_visitors
    </th>
    <th>
    notes
    </th>
    <th>
    max_visitors
    </th>
  </tr>
  </thead>
  
<tbody>
  
<?php  
  foreach( $this->playgroundnotic as $key => $value )
  {
    ?>
  <tr>
  <td>
    <?php
    echo $value->id;
    ?>
    </td>

     <td>
    <?php
    echo $value->playground_id;
    ?>
    </td>

     <td>
    <?php
    echo $value->date_von;
    ?>
    </td>

     <td>
    <?php
    echo $value->date_bis;
    ?>
    </td>
     <td>
    <?php
    echo $value->name_visitors;
    ?>
    </td>
     <td>
    <?php
    echo $value->notes;
    ?>
    </td>
     <td>
    <?php
    echo $value->max_visitors;
    ?>
    </td>
  
  </tr>
 
    <?php  
  }
  
  
  ?>
  
  
  
  
  
  
  
  
  
  
  </tbody>  
</table>
