<script>
    $(document).ready(function() {
        $("#form_login").submit(function(event) {
            event.preventDefault();

            var uname = $("#username").val();
            var pword = $("#password").val();

            $.ajax({
                url: "<?php echo base_url("Dashboard/login_process"); ?>",
                data: {
                    username: uname,
                    password: pword,
                },
                type: "post",
                success: function(data) {
                    if (data != "") {
                        if (data.toLowerCase().indexOf("gagal") >= 0) {

                            data = data.replace('gagal,', '');

                            $("#notifikasi").html(data);
                            $("#notifikasi").addClass("text-youtube");

                            $("#password").val("");
                        } else {
                            window.location.href = data;
                        }
                    }
                }
            })
        });
    });
</script>