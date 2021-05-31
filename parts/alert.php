<?php if (isset($_GET['message']) && isset($_GET['error'])): ?>
    <?php if($_GET['error']): ?>
        <div class="alert text-white bg-danger" role="alert">
            <div class="iq-alert-icon">
                <i class="ri-information-line"></i>
            </div>
            <div class="iq-alert-text"><?= $_GET['message'] ?></div>
        </div>
    <?php else: ?>
        <div class="alert text-white bg-success" role="alert">
            <div class="iq-alert-icon">
                <i class="ri-alert-line"></i>
            </div>
            <div class="iq-alert-text"><?= $_GET['message'] ?></div>
        </div>
    <?php endif; ?>
<?php endif; ?>