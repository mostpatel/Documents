<?php
/**
 *
 *
 * @link          https://github.com/mzm-dev 
 * @demo          http://highcharts-mzm.rhcloud.com
 * @created       September 2014
 * @fb            https://www.facebook.com/zakimedia
 * @email         mohdzaki04@gmail.com
 */
$cakeDescription = "Highcharts Pie Chart";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $cakeDescription ?></title>
        <link href="../webroot/css/cake.generic.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="../../../js/jquery.min.js"></script>
        <script type="text/javascript">
		
            $(document).ready(function() {
                var options = {
                    chart: {
                        renderTo: 'container',
                        type: 'column'
                    },
                    title: {
                        text: 'Month wise Generated Enquiries Graph Report',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'Month',
                        x: -20
                    },
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        title: {
                            text: 'Total No. of Enquiries'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>:<b>{point.y}</b> of total<br/>'
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -40,
                        y: 100,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                        shadow: true
                    },
                    series: []
                };
                $.getJSON("data/data-basic-colm.php", function(json) {
                    options.xAxis.categories = json[0]['data']; //xAxis: {categories: []}
                    options.series[0] = json[1];
					options.series[1] = json[2];
                    chart = new Highcharts.Chart(options);
                });
            });
        </script>
        <script src="../../../js/highcharts.js"></script>
        <script src="../../../js/exporting.js"></script>
    </head>
    <body>
        
        <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </body>
</html>
