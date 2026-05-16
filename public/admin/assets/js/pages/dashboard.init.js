// Dashboard initialization script for content statistics chart
$(document).ready(function () {
    $.ajax({
        url: '/dashboard/site-view-statistics', // Update with your actual route
        method: 'GET',
        success: function (data) {
            // Remove any existing chart instance if needed
            if (window.siteViewChart) {
                window.siteViewChart.destroy();
            }

            var ctx = $("#site-view-history").get(0).getContext("2d");
            window.siteViewChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.last_ten_days,
                    datasets: [{
                        label: "Site View Statistics",
                        backgroundColor: "#3ec396",
                        borderColor: "#3ec396",
                        borderWidth: 1,
                        hoverBackgroundColor: "#3ec396",
                        hoverBorderColor: "#3ec396",
                        data: data.last_ten_days_content
                    }]
                },
                options: {
                    maintainAspectRatio: false, // <-- Add this line
                    scales: {
                        yAxes: [{
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: "#fff"
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                color: "rgba(0,0,0,0.1)"
                            }
                        }]
                    }
                }
            });
        }
    });
});