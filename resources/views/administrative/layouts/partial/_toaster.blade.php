<script>
    <?php if ($message = Session::get('success')) : ?>
    showToast({
        eleWrapper: '#hexa-toaster',
        msg: '<?php echo " $message"; ?>',
        // error warning info success
        theme: 'success',
        closeButton: true,
        autoClose: true
    });
    <?php endif ?>
    <?php if ($message = Session::get('error')) : ?>
    showToast({
        eleWrapper: '#hexa-toaster',
        msg: '<?php echo " $message"; ?>',
        // error warning info success
        theme: 'error',
        closeButton: true,
        autoClose: true
    });
    <?php endif ?>
    <?php if ($message = Session::get('warning')) : ?>
    showToast({
        eleWrapper: '#hexa-toaster',
        msg: '<?php echo " $message"; ?>',
        // error warning info success
        theme: 'warning',
        closeButton: true,
        autoClose: true
    });
    <?php endif ?>
    <?php if ($message = Session::get('info')) : ?>
    showToast({
        eleWrapper: '#hexa-toaster',
        msg: '<?php echo " $message"; ?>',
        // error warning info success
        theme: 'info',
        closeButton: true,
        autoClose: true
    });
    <?php endif ?>
</script>


