jQuery.fn.extend({
    point_get_mother: function () {
        var $this=this;
        var form=$this.find('form');

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: false,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
        var color1=getRandomColor();
        var color2=getRandomColor();
        var areaChartData = {
            labels: ["Enero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            datasets: [
                {
                    label: "Registro",
                    data: [65, 59, 80, 81, 56, 55, 40, 12, 30, 30, 45, 90],
                    fillColor: color1,
                    strokeColor: color1,
                    pointColor: color1,
                    pointStrokeColor: color1,
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: color1
                },
                {
                    label: "Códigos",
                    data: [28, 48, 40, 19, 86, 27, 90, 32, 86, 67, 12, 55],
                    fillColor: color2,
                    strokeColor: color2,
                    pointColor: color2,
                    pointStrokeColor: color2,
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: color2
                }
            ]
        };

        var buildChart=function(data)
        {
            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $("#point-get-mother-canvas").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            lineChart.Line(data, lineChartOptions);
        }
        buildChart({});

        $this.find('button').on('click',function(e){
            e.preventDefault();
            var values={};
            $.each(form.serializeArray(), function (i, field) {
                values[field.name] = field.value;
            });
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: values,
                success: function (data) {
                    buildChart(data);

                },
                error: function () {

                }
            });

        });



    }
});

