<?php if (isset($_GET['message']) && isset($_GET['error'])): ?>
    <?php if((bool) $_GET['error']): ?>
        <div class="alert text-white bg-success" role="alert">
            <div class="iq-alert-text"><?= $_GET['message'] ?></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    <?php else: ?>
        <div class="alert text-white bg-danger" role="alert">
            <div class="iq-alert-text"><?= $_GET['message'] ?></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    <?php endif; ?>
<?php endif; ?>