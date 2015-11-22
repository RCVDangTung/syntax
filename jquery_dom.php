<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dinamically Row With JQuery</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script type="text/javascript" src="jquery_append.js"></script>
  </head>
  <body>
  <form id="id_form" action="jquery_dom_save.php" method="post">
    	<table>
    		<tr>
        	<td><input type="button" name="add_btn" value="Add" id="add_btn"></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        </tr>
    		<tr>
    			<td>No</td><td>NIM</td><td>Nama Depan</td><td>Nama Belakang</td><td>&nbsp;</td>
    		</tr>
    		<tbody id="container">

        </tbody>
        <tr>
	        <td><input type="submit" name=submit value="Save"></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	      </tr>
    	</table>
    </form>
  </body>
</html>


