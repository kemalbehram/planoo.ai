$(function () {
    var chartcrjson = JSON.parse($('#cr').attr('data-chart'));
    Highcharts.chart('cr', {
        chart: {
            type: 'waterfall',
            style: {
                fontFamily: 'Lato',
                fontWeight: 'normal'
            }
        },

        title: {
            text: ''
        },

        xAxis: {
            type: 'category'
        },

        yAxis: {
            title: {
                text: 'EUR'
            }
        },

        legend: {
            enabled: false
        },
        plotOptions: {
            waterfall: {
                animation: 0,
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return Highcharts.numberFormat(this.y, 0, ',', ' ');
                    },
                    style: {
                        fontFamily: 'Lato',
                        fontWeight: 'normal'
                    }
                }
            }
        },

        tooltip: {
            pointFormat: '<b>{point.y:,.0f}€</b>'
        },

        upColor: Highcharts.getOptions().colors[2],
        color: Highcharts.getOptions().colors[3],

        series: [{
                upColor: Highcharts.getOptions().colors[2],
                color: Highcharts.getOptions().colors[3],
                data: chartcrjson
            }]

    });
});
