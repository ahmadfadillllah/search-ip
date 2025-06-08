import './bootstrap';
import * as echarts from 'echarts';

window.renderGaugeChart = function (data) {
    const chartDom = document.getElementById('main');
    const myChart = echarts.init(chartDom);

    const option = {
        series: [
            {
                type: 'gauge',
                min: 0,             // batas bawah
                max: 200,           // batas atas (ubah dari default 100 ke 200)
                progress: {
                    show: true,
                    width: 18
                },
                axisLine: {
                    lineStyle: {
                        width: 18
                    }
                },
                axisTick: {
                    show: false
                },
                splitLine: {
                    length: 10,
                    lineStyle: {
                        width: 2,
                        color: '#999'
                    }
                },
                axisLabel: {
                    distance: 25,
                    color: 'black',
                    fontSize: 6
                },
                anchor: {
                    show: true,
                    showAbove: true,
                    size: 25,
                    itemStyle: {
                        borderWidth: 10
                    }
                },
                title: {
                    show: false
                },
                detail: {
                    valueAnimation: true,
                    fontSize: 40,
                    offsetCenter: [0, '70%']
                },
                data: [
                    {
                        value: 183 // pastikan nilai ini < 200
                    }
                ]
            }
        ]
    };

    myChart.setOption(option);
    window.addEventListener('resize', () => {
        myChart.resize();
    });
};

