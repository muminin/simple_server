<script src="<?php echo base_url("assets/skpd"); ?>/vendors/chart.js/Chart.min.js"></script>
<script>
    $(document).ready(function() {
        var data = {
            // labels: ["Dinas Pendidikan", "2014", "2014", "2015", "2016", "2017"],
            labels: [<?php foreach ($diagram as $val) { ?> "<?php echo $val["nama"] . ' (' . $val["jumlah_input"] . '/' . $val['jumlah_data'] . ')'; ?>", <?php } ?>],
            datasets: [{
                label: "Persentase dari",
                data: [<?php foreach ($diagram as $val) { ?> "<?php echo $val["persentase"]; ?>", <?php } ?>],
                backgroundColor: [<?php foreach ($diagram as $val) { ?> "<?php echo $val["warna"]; ?>", <?php } ?>],
                borderColor: [<?php foreach ($diagram as $val) { ?> "<?php echo $val["border"]; ?>", <?php } ?>],
                borderWidth: 1,
                fill: false
            }]
        };

        var options = {
            responsive: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    stacked: true,
                }],
            },
            legend: {
                display: false
            },
            elements: {
                point: {
                    radius: 0
                }
            }

        };

        // Get context with jQuery - using jQuery's .get() method.
        if ($("#barChart").length) {
            var barChartCanvas = $("#barChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'horizontalBar',
                data: data,
                options: options
            });
        }

        $("#tahun").change(function() {
            var tahun = $(this).val();

            window.location.href = "<?php echo base_url("skpd/Dashboard?tahun=") ?>" + tahun;
        });

        $("#refresh").click(function() {
            var tahun = $("#tahun").val();

            $.ajax({
                url: "<?php echo base_url('skpd/Dashboard/counter_data?tahun='); ?>" + tahun,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == "exist") {
                        showDangerToast("Data sudah ada.");
                        $(".loading").hide();
                    } else if (data == 1) {
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Dashboard?tahun="); ?>" + tahun);
                    } else {
                        $(".loading").hide();
                    }
                },
            });
        });
    });
</script>