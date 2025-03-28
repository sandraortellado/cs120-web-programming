<!doctype html>
<html>
    <head>
        <title>Problem 1</title>
    </head>
    <body>
    <?php
        $num = $_GET['n'];
        $arr = array('length' => $num, 'fibSequence' => []);
        function fibonacci($num) {
            $seq = [];
            if ($num >=1) $seq[] = 0;
            if ($num >=2) $seq[] = 1;
            
            for ($i = 2; $i < $num; $i++) {
                $seq[] = $seq[$i-1] + $seq[$i-2];
            }
            
            return $seq;
        }
        $arr['fibSequence'] = fibonacci($num);
        echo json_encode($arr)
    ?>
    </body>
</html>