<script>
    $(document).ready(function() {
        $('#tab_jenis').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sUrl": "<?php echo base_url("assets/skpd/vendors/datatables.net/datatable_lang_indo.json") ?>",
            },
            "columnDefs": [{
                "visible": false, // hide the main grouping head rows trigger
                "targets": 1
            }],
            "drawCallback": function(settings) {
                // this function using for grouping rows
                var api = this.api();
                var last = null;
                var rows = api.rows({
                    page: 'current'
                }).nodes();

                api.column(1, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="4" style="padding-left: 10px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        $(".edit").click(function() {
            var id = $(this).attr("data-id");
            window.location.href = "<?php echo base_url("skpd/Jenis_utama/ubah?id="); ?>" + id;
        });

        $(".delete").click(function() {
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo base_url("skpd/Jenis_utama/hapus?id=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data Jenis Utama berhasil dihapus.",
                            "<?php echo base_url("skpd/Jenis_utama"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $(".add-urusan").click(function() {
            var id = $(this).attr("data-id");
            window.location.href = "<?php echo base_url("skpd/Jenis_utama/tambah_urusan?id="); ?>" + id;
        });
    });
</script>