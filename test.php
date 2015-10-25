<!DOCTYPE html>
<html>
    <head>
        <title>Your First PHP while loop!</title>
    </head>
    <body>
        <?php
//	$loopCond = true;
//        $flag = FALSE;
//	while ($flag === FALSE){
//		//Echo your message that the loop is running below
//		echo "<p>The loop is running.</p>";
////		$loopCond = false;
//                $flag = TRUE;
//	}
////        $flag = FALSE;
//	echo "<p>And now it's done.</p>";
//        
//        for ($i = 0; $i < 5; ++$i) {
//            if ($i == 2){
//                continue;
//            }
//            print "$i\n";
//                
//        }
        
        echo '<br />';
        echo 'demo_test';
        echo '<br />';
        echo "=============";
        echo '<br />';
        
        $fruits = array('apples', 'figs', 'bananas');
        $continue = true;
        for ($i = 0; $i < count($fruits) && $continue == true; $i++) {
            $fruit = $fruits[$i];

            if ($fruit == 'figs') {
                $continue = false;
            }

            echo $fruit . "\n";
        }

        echo '<br />';
        echo '<br />';
        echo "=============";

        echo "<select name=\"day\">";
        $i = 1;
        while ($i <= 31) {
            echo "<option value=" . $i . ">" . $i . "</option>";
            $i++;
        }
        echo "</select>";
        echo "<br />";
        echo "<select name=\"year\">";
        $i = 1900;
        while ($i <= 2007) {
            echo "<option value=" . $i . ">" . $i . "</option>";
            $i++;
        }
        echo "</select>";



        echo "<br />";
        echo '=============';
        echo "<h1>Multiplication table</h1>";
        echo "<table border=2 width=50%";

        for ($i = 1; $i <= 9; $i++) {   //this is the outer loop
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            for ($j = 2; $j <= 9; $j++) { // inner loop
                echo "<td>" . $i * $j . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";

        echo "<br />";
        echo '=============';

        $total = 0;
        $even = 0;

        for ($x = 1, $y = 1; $x <= 10; $x++, $y++) {
            if (( $y % 2 ) == 0) {
//                echo $y;
                $even = $even + $y;
            }
            $total = $total + $x;
        }

        echo "The total sum: " . $total . "<br />";
        echo "The sum of even values: " . $even;


        echo "<br />";
        echo '=============';

        $i_1 = 0;
        $j_1 = 0;

        while ($i_1 < 10) {
            while ($j_1 < 10) {
                if ($j_1 == 5) {
                    break 2;
                } // breaks out of two while loops
                echo $j_1;
                $j_1++;
            }
            $i_1++;
        }

        echo "<br />";
        echo "The first number is " . $i_1 . "<br />";
        echo "The second number is " . $j_1 . "<br />";


        echo "<br/>";
        echo "========================";
        
        $shop = array(
                array(array("rose", 1.25, 15),
                    array("daisy", 0.75, 25),
                    array("orchid", 1.15, 7) 
                   ),
                array(array("rose", 1.25, 15),
                    array("daisy", 0.75, 25),
                    array("orchid", 1.15, 7) 
                   ),
              array(array("rose", 1.25, 15),
                    array("daisy", 0.75, 25),
                    array("orchid", 1.15, 7) 
                   )
             );
        
        
        echo "<ul>";
        for ($layer = 0; $layer < 3; $layer++) {
            echo "<li>The layer number $layer";
            echo "<ul>";

            for ($row = 0; $row < 3; $row++) {
                echo "<li>The row number $row";
                echo "<ul>";

                for ($col = 0; $col < 3; $col++) {
                    echo "<li>" . $shop[$layer][$row][$col] . "</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";
        ?>
    </body>
</html>
