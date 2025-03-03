function hexToRGB(a, r) {
    var e = parseInt(a.slice(1, 3), 16),
        t = parseInt(a.slice(3, 5), 16),
        a = parseInt(a.slice(5, 7), 16);
    return r ? "rgba(" + e + ", " + t + ", " + a + ", " + r + ")" : "rgb(" + e + ", " + t + ", " + a + ")"
}! function (s) {
    "use strict";

    function a() {
        this.$body = s("body"), this.charts = []
    }
    a.prototype.respChart = function (a, r, e, t) {
        var o, l = a.get(0).getContext("2d"),
            n = s(a).parent();
        switch (Chart.defaults.global.defaultFontColor = "#8391a2", Chart.defaults.scale.gridLines.color = "#8391a2", a.attr("width", s(n).width()), r) {
            case "Line":
                o = new Chart(l, {
                    type: "line",
                    data: e,
                    options: t
                });
                break;
            case "Doughnut":
                o = new Chart(l, {
                    type: "doughnut",
                    data: e,
                    options: t
                });
                break;
            case "Pie":
                o = new Chart(l, {
                    type: "pie",
                    data: e,
                    options: t
                });
                break;
            case "Bar":
                o = new Chart(l, {
                    type: "bar",
                    data: e,
                    options: t
                });
                break;
            case "Radar":
                o = new Chart(l, {
                    type: "radar",
                    data: e,
                    options: t
                });
                break;
            case "PolarArea":
                o = new Chart(l, {
                    data: e,
                    type: "polarArea",
                    options: t
                })
        }
        return o
    }, a.prototype.initCharts = function () {
        var a, r, e, t = [],
            o = ["#1abc9c", "#f1556c", "#4a81d4", "#e3eaef"];
        return 0 < s("#line-chart-example").length && (e = {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [{
                label: "Current Week",
                backgroundColor: hexToRGB((r = (a = s("#line-chart-example").data("colors")) ? a.split(",") : o.concat())[0], .3),
                borderColor: r[0],
                data: [32, 42, 42, 62, 52, 75, 62]
            }, {
                label: "Previous Week",
                fill: !0,
                backgroundColor: "transparent",
                borderColor: r[1],
                borderDash: [5, 5],
                data: [42, 58, 66, 93, 82, 105, 92]
            }]
        }, t.push(this.respChart(s("#line-chart-example"), "Line", e, {
            maintainAspectRatio: !1,
            legend: {
                display: !1
            },
            tooltips: {
                intersect: !1
            },
            hover: {
                intersect: !0
            },
            plugins: {
                filler: {
                    propagate: !1
                }
            },
            scales: {
                xAxes: [{
                    reverse: !0,
                    gridLines: {
                        color: "rgba(0,0,0,0.05)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 20
                    },
                    display: !0,
                    borderDash: [5, 5],
                    gridLines: {
                        color: "rgba(0,0,0,0)",
                        fontColor: "#fff"
                    }
                }]
            }
        }))), 0 < s("#bar-chart-example").length && (e = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Sales Analytics",
                backgroundColor: (r = (a = s("#bar-chart-example").data("colors")) ? a.split(",") : o.concat())[0],
                borderColor: r[0],
                hoverBackgroundColor: r[0],
                hoverBorderColor: r[0],
                data: [65, 59, 80, 81, 56, 89, 40, 32, 65, 59, 80, 81]
            }, {
                label: "Dollar Rate",
                backgroundColor: r[1],
                borderColor: r[1],
                hoverBackgroundColor: r[1],
                hoverBorderColor: r[1],
                data: [89, 40, 32, 65, 59, 80, 81, 56, 89, 40, 65, 59]
            }]
        }, t.push(this.respChart(s("#bar-chart-example"), "Bar", e, {
            maintainAspectRatio: !1,
            legend: {
                display: !1
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: !1
                    },
                    stacked: !1,
                    ticks: {
                        stepSize: 20
                    }
                }],
                xAxes: [{
                    barPercentage: .7,
                    categoryPercentage: .5,
                    stacked: !1,
                    gridLines: {
                        color: "rgba(0,0,0,0.01)"
                    }
                }]
            }
        }))), 0 < s("#pie-chart-example").length && (e = {
            labels: ["Direct", "Affilliate", "Sponsored", "E-mail"],
            datasets: [{
                data: [300, 135, 48, 154],
                backgroundColor: r = (a = s("#pie-chart-example").data("colors")) ? a.split(",") : o.concat(),
                borderColor: "transparent"
            }]
        }, t.push(this.respChart(s("#pie-chart-example"), "Pie", e, {
            maintainAspectRatio: !1,
            legend: {
                display: !1
            }
        }))), 0 < s("#donut-chart-example").length && (e = {
            labels: ["Direct", "Affilliate", "Sponsored"],
            datasets: [{
                data: [128, 78, 48],
                backgroundColor: r = (a = s("#donut-chart-example").data("colors")) ? a.split(",") : o.concat(),
                borderColor: "transparent",
                borderWidth: "3"
            }]
        }, t.push(this.respChart(s("#donut-chart-example"), "Doughnut", e, {
            maintainAspectRatio: !1,
            cutoutPercentage: 60,
            legend: {
                display: !1
            }
        }))), 0 < s("#polar-chart-example").length && (e = {
            labels: ["Direct", "Affilliate", "Sponsored", "E-mail"],
            datasets: [{
                data: [251, 135, 48, 154],
                backgroundColor: r = (a = s("#polar-chart-example").data("colors")) ? a.split(",") : o.concat(),
                borderColor: "transparent"
            }]
        }, t.push(this.respChart(s("#polar-chart-example"), "PolarArea", e))), 0 < s("#radar-chart-example").length && (e = {
            labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
            datasets: [{
                label: "Desktops",
                backgroundColor: hexToRGB((r = (a = s("#radar-chart-example").data("colors")) ? a.split(",") : o.concat())[0], .3),
                borderColor: r[0],
                pointBackgroundColor: r[0],
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: r[0],
                data: [65, 59, 90, 81, 56, 55, 40]
            }, {
                label: "Tablets",
                backgroundColor: hexToRGB(r[1], .3),
                borderColor: r[1],
                pointBackgroundColor: r[1],
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: r[1],
                data: [28, 48, 40, 19, 96, 27, 100]
            }]
        }, t.push(this.respChart(s("#radar-chart-example"), "Radar", e, {
            maintainAspectRatio: !1
        }))), t
    }, a.prototype.init = function () {
        var r = this;
        Chart.defaults.global.defaultFontFamily = "Nunito,sans-serif", r.charts = this.initCharts(), s(window).on("resize", function (a) {
            s.each(r.charts, function (a, r) {
                try {
                    r.destroy()
                } catch (a) {}
            }), r.charts = r.initCharts()
        })
    }, s.ChartJs = new a, s.ChartJs.Constructor = a
}(window.jQuery),
function () {
    "use strict";
    window.jQuery.ChartJs.init()
}();
