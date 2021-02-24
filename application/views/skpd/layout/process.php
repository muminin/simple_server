<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>JustDo Admin</title>

    <link rel="stylesheet" href="<?php echo base_url("assets/skpd") ?>/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo base_url("assets/skpd") ?>/vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="<?php echo base_url("assets/skpd") ?>/css/style.css">
    <link rel="shortcut icon" href="<?php echo base_url("assets/skpd") ?>/images/favicon.png" />
</head>

<body>
    <?php
    if (isset($_view) && $_view)
        $this->load->view($_view);
    ?>
    <script src="<?php echo base_url("assets/skpd") ?>/vendors/js/vendor.bundle.base.js"></script>

    <script src="<?php echo base_url("assets/skpd") ?>/js/off-canvas.js"></script>
    <script src="<?php echo base_url("assets/skpd") ?>/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url("assets/skpd") ?>/js/template.js"></script>
    <script src="<?php echo base_url("assets/skpd") ?>/js/settings.js"></script>
    <script src="<?php echo base_url("assets/skpd") ?>/js/todolist.js"></script>

    <?php
    if (isset($_js) && $_js)
        $this->load->view($_js);
    ?>
</body>

</html>