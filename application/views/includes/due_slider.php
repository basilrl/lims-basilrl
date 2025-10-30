<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
            .item {
                /* background: #333;*/
                text-align: center;
                overflow-y: auto !important;overflow-x: hidden !important;
                /* height:500px; */
            }

            table {
                margin: 0;
                /*color: #888;*/
                padding-top: 80px;
                font-size: 12px;
                width:100%;
              
            }
            .carousel-indicators{
                position : absolute!important; 
            }
            th{text-align: center;}
            .carousel-control.left,.carousel-control.right{
                background:none !important;color:#31373d !important;
                height: 40px; 
                width: 25px;
                margin-top: 230px;
                position: fixed!important;
                }
                #tabhead {
                color: #80821f!important;
                font-size: 18px !important;
                }
        </style>
</head>

<body>
    <div class="thumbnail" style="width: 800px;">
        <div id="DemoCarousel" class="carousel slide" data-interval="2000" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-bs-target="#DemoCarousel" data-slide-to="0" class="active"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="1"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="2"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="3"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="4"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="5"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="6"></li>
                <li data-bs-target="#DemoCarousel" data-slide-to="7"></li>
            </ol>
            <?php
            $html = '<div class="carousel-inner">';
            $d = 1;
            $active = '';

            if ($back_log_data != "") {
                foreach ($back_log_data as $key => $division) {

                    if ($d == 1) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }

                    $backLogCnt = count($division);

                    $html .= '<div class="item  ' . $active . '">'
                        . '<span id="tabhead">' . $key . ' Backlogs(' . $backLogCnt . ')</span>'
                        . '<table border class="table table-bordered table-striped mb-0 text-left" style="width:800px">'
                        . '<thead>'
                        . '<tr>'
                        . '<th>S.N</th>'
                        . '<th>Basil Report Number</th>'
                        . '<th>Sample Desc</th>'
                        . '<th>Due Date</th></tr></thead><tbody>';

                    $i = 1;
                    foreach ($division as $row) {
                        $html .= "<tr><td>" . $i . "</td>"
                            . "<td>" . $row->gc_no . "</td>"
                            . "<td>" . $row->sample_desc . "</td>"
                            . "<td style='width:150px'>" . $row->due_date . " </td></tr>";
                        $i++;
                    }
                    $html .= '<tbody></table></div>';
                    $d++;
                }
            }



            if ($today_log != '') {
                $todayCnt = count($today_log);

                $html .= '<div class="item">'
                    . '<span id="tabhead">Today Logs(' . $todayCnt . ')</span><table border class="table table-bordered table-striped mb-0"><thead><tr><th>S.N</th><th>Basil Report Number</th><th>Sample Desc</th><th>Due Date</th></tr></thead><tbody>';


                $i = 1;
                foreach ($today_log as $row) {
                    $html .= "<tr>"
                        . "<td>" . $i . "</td>"
                        . "<td>" . $row->gc_no . "</td>"
                        . "<td>" . $row->sample_desc . "</td>"
                        . "<td>" . $row->due_date . " </td></tr>";
                    $i++;
                }
                $html .= '<tbody></table></div>';
            }


            if ($upcoming_log != "") {
                $upcomingCnt = count($upcoming_log);
                $html .=  '<div class="item">'
                    . '<span id="tabhead">Upcoming Logs(' . $upcomingCnt . ')</span>'
                    . '<table border class="table table-bordered table-striped mb-0">'
                    . '<thead><tr><th>S.N</th><th>Basil Report Number</th><th>Sample Desc</th><th>Due Date</th></tr></thead><tbody>';


                $i = 1;
                foreach ($upcoming_log as $row) {
                    $html .= "<tr>"
                        . "<td>" . $i . "</td>"
                        . "<td>" . $row->gc_no . "</td>"
                        . "<td>" . $row->sample_desc . "</td>"
                        . "<td>" . $row->due_date . " </td></tr>";
                    $i++;
                }
                $html .= '<tbody></table></div>';
            }

            $html .= '</div>';
            echo $html;
            ?>
            <a class="carousel-control left" href="#DemoCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="carousel-control right" href="#DemoCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
    </div>
</body>

</html>