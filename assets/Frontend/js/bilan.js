$(function () {
    var chartcrjson = JSON.parse($('#bilan').attr('data-chart'));
    var chartcategoriesjson = JSON.parse($('#bilan').attr('data-categories'));
    Highcharts.chart('bilan', {
        chart: {
            type: 'column',
            style: {
                fontFamily: 'Lato',
                fontWeight: 'normal'
            }
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: chartcategoriesjson
        },
        yAxis: [
            {// Primary yAxis
                title: {
                    text: 'BFR',
                    style: {
                        color: Highcharts.getOptions().colors[4]
                    }
                },
                opposite: true,
                tickPositioner: function () {
                    var maxDeviation = Math.ceil(Math.max(Math.abs(this.dataMax), Math.abs(this.dataMin)));
                    var halfMaxDeviation = Math.ceil(maxDeviation / 2);

                    return [-maxDeviation, -halfMaxDeviation, 0, halfMaxDeviation, maxDeviation];
                }
            },
            {
                title: {
                    text: 'Bilan'
                },
                stackLabels: {
                    enabled: false,
                    style: {
                        fontWeight: 'normal',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray',
                        formatter: function () {
                            return Highcharts.numberFormat(this.y, 0, ',', ' ');
                        }
                    }
                },
                tickPositioner: function () {
                    var maxDeviation = Math.ceil(Math.max(Math.abs(this.dataMax), Math.abs(this.dataMin)));
                    var halfMaxDeviation = Math.ceil(maxDeviation / 2);

                    return [-maxDeviation, -halfMaxDeviation, 0, halfMaxDeviation, maxDeviation];
                }
            }
        ],
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            shadow: false,
            itemStyle: {
                fontFamily: 'Lato',
                fontWeight: 'normal'
            }
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            series: {
                animation: 0
            },
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
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
        series: chartcrjson
    });
});
