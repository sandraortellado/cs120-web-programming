<!doctype html>
<html>
    <head>
        <title>Problem 2</title>
        <style>
            body {
                max-width: 300px;
                margin: 20px auto;
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .hours li {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
            }
            
        </style>
    </head>
    <body>
    <?php
        $arr = array('Monday' => '8 am - 5 pm', 'Tuesday' => '8 am - 5 pm', 'Wednesday' => '8 am - 5 pm', 'Thursday' => '8am - 5pm', 'Friday' => '8am - 4pm', 'Saturday' => '10 am - 2 pm' );
        function showHours($arr) {
            $output = "<h2>Business Hours</h2><ul class='hours'>"; //create output
            foreach ($arr as $day => $time) { //loop through arr
                $output .= "<li><strong>$day:</strong> $time</li>"; //add text to output
            }
            $output .= "</ul>"; //end list
            return $output;	//return string
        }
        
        echo showHours($arr);
    ?>
    </body>
</html>