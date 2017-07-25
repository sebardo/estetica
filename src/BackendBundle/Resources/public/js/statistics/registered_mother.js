jQuery.fn.extend({
    registered_mother: function () {
        var $this=this;
        var form=$this.find('form');

        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };

        var buildChart=function(data)
        {
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#registered-mother-canvas').get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(data, pieOptions);
        }

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
