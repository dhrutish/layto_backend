var earningschart = null;
var userschart = null;
var otphistorychart = null;
var jobspostchart = null;
$(function (params) {
    $("#earnings_filter").on("change", function () {
        if ($.trim($("#earnings_filter").val()) == "") {
            return false;
        }
        $.ajax({
            url: location.href,
            method: "GET",
            data: {
                er_filter: $("#earnings_filter").val(),
            },
            dataType: "JSON",
            success: function (data) {
                createEarningsChart(data.earning_labels, data.earning_data);
            },
            error: function (data) {
                console.log(data);
            },
        });
    }).change();
    $("#users_filter").on("change", function() {
        if ($.trim($(this).val()) == "") {
            return false;
        }
        $.ajax({
            url: location.href,
            method: "GET",
            data: {
                er_filter: $(this).val(),
            },
            dataType: "JSON",
            success: function(data) {
                createUsersChart(data.userlabels, data.providers, data.seekers);
            },
            error: function(data) {
                console.log(data);
            },
        });
    }).change();
    $("#otphistory_filter").on("change", function() {
        if ($.trim($(this).val()) == "") {
            return false;
        }
        $.ajax({
            url: location.href,
            method: "GET",
            data: {
                er_filter: $(this).val(),
            },
            dataType: "JSON",
            success: function(data) {
                createOtpHistoryChart(data.otp_history_labels, data.otp_history_data);
            },
            error: function(data) {
                console.log(data);
            },
        });
    }).change();
    $("#jobspost_filter").on("change", function() {
        if ($.trim($(this).val()) == "") {
            return false;
        }
        $.ajax({
            url: location.href,
            method: "GET",
            data: {
                er_filter: $(this).val(),
            },
            dataType: "JSON",
            success: function(data) {
                createJobsPostChart(data.jobs_labels, data.jobs_data);
            },
            error: function(data) {
                console.log(data);
            },
        });
    }).change();
});

function createEarningsChart(labels, earningsdata) {
    const chartdata = {
        labels: labels,
        datasets: [
            {
                label: " Earnings ",
                backgroundColor: ["#F69A0020"],
                borderColor: ["#F69A00"],
                pointStyle: "circle",
                pointRadius: 10,
                pointHoverRadius: 10,
                data: earningsdata,
                // cubicInterpolationMode: 'monotone'
                fill: true,
            },
        ],
    };
    const config = {
        type: "line",
        data: chartdata,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value, index, values) {
                            return "₹" + value.toFixed(2);
                        },
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var label =
                                (context.dataset.label
                                    ? context.dataset.label + ": "
                                    : "") +
                                "₹" +
                                context.parsed.y.toFixed(2);
                            return label;
                        },
                    },
                },
            },
        },
    };
    if (earningschart != null) {
        earningschart.destroy();
    }
    earningschart = new Chart(document.getElementById("earningschart"), config);
}
function createUsersChart(labels, providers, seekers) {
    const chartdata = {
        labels: labels,
        datasets: [{
                label: " Job Providers ",
                backgroundColor: ["#2355C420"],
                borderColor: ["#2355C4"],
                pointStyle: "circle",
                pointRadius: 5,
                pointHoverRadius: 5,
                data: providers,
                cubicInterpolationMode: 'monotone',
                fill: true,
            },
            {
                label: " Job Seekers ",
                backgroundColor: ["#F69A0020"],
                borderColor: ["#F69A00"],
                pointStyle: "circle",
                pointRadius: 5,
                pointHoverRadius: 5,
                data: seekers,
                cubicInterpolationMode: 'monotone',
                fill: true,
            },
        ],
    };
    const config = {
        type: "line",
        data: chartdata,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Chart.js Line Chart'
                }
            }
        }
    };
    if (userschart != null) {
        userschart.destroy();
    }
    userschart = new Chart(document.getElementById("userschart"), config);
}
function createOtpHistoryChart(labels, data) {
    const chartdata = {
        labels: labels,
        datasets: [{
            label: " Send OTP ",
            backgroundColor: ['rgba(54, 162, 235, 0.4)', 'rgba(255, 150, 86, 0.4)',
                'rgba(140, 162, 198, 0.4)', 'rgba(255, 206, 86, 0.4)', 'rgba(255, 99, 132, 0.4)',
                'rgba(255, 159, 64, 0.4)', 'rgba(255, 205, 86, 0.4)', 'rgba(75, 192, 192, 0.4)',
                'rgba(54, 170, 235, 0.4)', 'rgba(153, 102, 255, 0.4)', 'rgba(201, 203, 207, 0.4)',
                'rgba(255, 159, 64, 0.4)',
            ],
            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 150, 86, 1)', 'rgba(140, 162, 198, 1)',
                'rgba(255, 206, 86, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)',
                'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(54, 170, 235, 1)',
                'rgba(153, 102, 255, 1)', 'rgba(201, 203, 207, 1)', 'rgba(255, 159, 64, 1)',
            ],
            data: data,
            borderRadius: 5,
            borderWidth: 2,
            barThickness: 50,
            maxBarThickness: 50,
        }, ],
    };

    const config = {
        type: 'doughnut',
        data: chartdata,
        options: {
            responsive: true,
            // indexAxis: 'y',
            plugins: {
                // legend: {
                //     position: 'right',
                // },
                title: {
                    display: true,
                    text: 'Total send OTP : ' + data.reduce((acc, currentValue) => acc + currentValue, 0)
                },
            },
        },
    };
    if (otphistorychart != null) {
        otphistorychart.destroy();
    }
    otphistorychart = new Chart(document.getElementById("otphistorychart"), config);
}
function createJobsPostChart(labels, data) {
    const chartdata = {
        labels: labels,
        datasets: [{
            label: " Jobs Posted ",
            backgroundColor: ['rgba(54, 162, 235, 0.4)', ],
            borderColor: ['rgba(54, 162, 235, 1)', ],
            data: data,
            borderWidth: 2,
            borderRadius: 5,
            barThickness: 50,
            maxBarThickness: 50,
        }, ],
    };

    const config = {
        type: 'bar',
        data: chartdata,
        options: {
            responsive: true,
            // indexAxis: 'y',
            plugins: {
                // legend: {
                //     position: 'right',
                // },
                title: {
                    display: true,
                    text: 'Total Jobs Posted : ' + data.reduce((acc, currentValue) => acc + currentValue, 0)
                },
            },
        },
    };
    if (jobspostchart != null) {
        jobspostchart.destroy();
    }
    jobspostchart = new Chart(document.getElementById("jobspostchart"), config);
}
