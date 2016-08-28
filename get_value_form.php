<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>

<form>
    First Name: <input type="text" id="firstname">
  </form>
<h1 id="demo"></h1>


<script>
   $('#firstname').on("input", function() {
     $('#demo').text($(this).val());
   });
</script>

</body>
</html>

<!--http://jsfiddle.net/pxfunc/5kpeJ/-->

<!--http://jsfiddle.net/philfreo/MqM76/-->

<!--http://jsbin.com/oleto5/507/edit?html,js,output-->