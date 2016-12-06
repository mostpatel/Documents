<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Droppable - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  .draggable { width: 100px; height: 100px; padding: 0.5em; float: left; margin: 10px 10px 10px 0; border:1px solid #000;}
  .droppable { width: 150px; height: 150px; padding: 0.5em; float: left; margin: 10px; }
  td{
	  border:1px solid #000;}
  </style>
  <script>
  $(function() {
    $( ".draggable" ).draggable({ containment: "parent" });
    $( ".droppable" ).droppable({
      drop: function( event, ui ) {
        $( this )
          .addClass( "ui-state-highlight" )
          .find( "p" )
            .html( "Dropped!" );
      }
    });
  });
  </script>
</head>
<body>
 <table>
 <tr>
 <th>col 1</th>
 <th>col 2</th>
 <th>col 3</th>
 <th>col 4</th>
 </tr>
  <tr>
 <td class="draggable droppable"> 1</td>
 <td class="draggable droppable"> 2</td>
 <td class="draggable droppable"> 3</td>
 <td class="draggable droppable"> 4</td>
 </tr>
  <tr>
 <td class="draggable droppable"> 1</td>
 <td class="draggable droppable"> 2</td>
 <td class="draggable droppable"> 3</td>
 <td class="draggable droppable"> 4</td>
 </tr>
  <tr>
 <td class="draggable droppable"> 1</td>
 <td class="draggable droppable"> 2</td>
 <td class="draggable droppable"> 3</td>
 <td class="draggable droppable"> 4</td>
 </tr>
 </table>
<div class="draggable" class="ui-widget-content">
  <p>Drag me to my target</p>
</div>
 
<div class="droppable" class="ui-widget-header">
  <p>Drop here</p>
</div>

<div class="draggable" class="ui-widget-content">
  <p>Drag me to my target</p>
</div>
 
<div class="droppable" class="ui-widget-header">
  <p>Drop here</p>
</div>
 
 
</body>
</html>