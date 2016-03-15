
<html>
    <head>
        <title>Refresh or Reload a Page Using JQuery</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    </head>

    <body>
        <!--<div><input id="btReload" type="button" value="Reload Page" /></div>-->
        <h3>aaaaaaaaaaaaaa</h3>
        <script> type = "text/javascript" >
                    $(document).ready(function () {
                setInterval('refreshPage()', 3000);
            });

            function refreshPage() {
//                location.reload();
//                history.go(0);
//                var url = location.href;
//                alert(url);
//                location.href = location.href
                window.location = location.href;
            }

        </script>
    </body>
    <!--5 minutes * 60 seconds * 1000 milliseconds = 300000ms-->
</html>