$(function () {
    if ($('#mbm').length > 0) {
        var chartcrjson = JSON.parse($('#mbm').attr('data-chart'));
        var chartcategoriesjson = JSON.parse($('#mbm').attr('data-categories'));
        Highcharts.chart('mbm', {
            chart: {
                type: 'bar',
                style: {
                    fontFamily: 'Lato',
                    fontWeight: 'normal'
                }
            },
            title: {
                text: '',
                style: {
                    fontFamily: 'Lato',
                    fontWeight: 'normal'
                }
            },
            xAxis: {
                categories: chartcategoriesjson
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true,
                itemStyle: {
                    fontFamily: 'Lato',
                    fontWeight: 'normal'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                bar: {
                    animation: 0,
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontFamily: 'Lato',
                            fontWeight: 'normal'
                        },
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                },
                series: {
                    animation: 0,
                    stacking: 'normal'
                }
            },
            series: chartcrjson
        });
    }
});