<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="index.php">
            <img src="images/logo.gif" class="img-fluid" alt="">
            <span></span>
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                    <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <!--                <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Tableau de bord</span></li>-->
                <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                    <li class="<?php if ($_SESSION['active'] === 'index'): echo 'active'; endif; ?>">
                        <a href="index.php" class="iq-waves-effect"><i
                                    class="ri-home-4-line"></i><span>Tableau de bord</span></a>
                    </li>
                <?php endif; ?>
                <li class="<?php if ($_SESSION['active'] === 'list-invoice'): echo 'active'; endif; ?>"><a
                            href="list-invoice.php" class="iq-waves-effect"><i class="ri-profile-line"></i><span>Factures</span></a>
                </li>
                <li class="<?php if (strpos($_SESSION['active'], 'customer') !== false): echo 'active'; endif; ?>">
                    <a href="#customers" class="iq-waves-effect collapse" data-toggle="collapse"
                       aria-expanded="false"><i class="ri-user-line"></i><span>Clients</span><i
                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                    <ul id="customers" class="iq-submenu collapse " data-parent="#iq-sidebar-toggle">
                        <li class="<?php if ($_SESSION['active'] === 'list-customer'): echo 'active'; endif; ?>"><a
                                    href="list-customer.php"><i class="ri-file-list-line "></i>Liste</a></li>
                        <li class="<?php if ($_SESSION['active'] === 'add-customer'): echo 'active'; endif; ?>"><a
                                    href="add-customer.php"><i class="ri-user-add-line"></i>Ajouter un client</a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                    <li>
                        <a href="#icons" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i
                                    class="ri-list-check"></i><span>Rapports</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="icons" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li><a href="#"><i class="ri-stack-line"></i>Rapport 1</a></li>
                            <li><a href="#"><i class="ri-stack-line"></i>Rapport 2</a></li>
                            <li><a href="#"><i class="ri-stack-line"></i>Rapport 3</a></li>
                        </ul>
                    </li>

                    <li class="<?php if (strpos($_SESSION['active'], 'user') !== false): echo 'active'; endif; ?>">
                        <a href="#userinfo" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false"><i class="ri-link-unlink"></i><span>Utilisateurs</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="userinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="<?php if ($_SESSION['active'] === 'list-user'): echo 'active'; endif; ?>"><a
                                        href="list-user.php"><i class="ri-file-list-line"></i>Liste</a></li>
                            <li class="<?php if ($_SESSION['active'] === 'add-user'): echo 'active'; endif; ?>"><a
                                        href="add-user.php"><i class="ri-user-add-line"></i>Nouvel utilisateur</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Apps</span></li>
                <li class="<?php if ($_SESSION['active'] === 'email'): echo 'active'; endif; ?>"><a href="email.php"
                                                                                                    class="iq-waves-effect"
                                                                                                    aria-expanded="false"><i
                                class="ri-mail-line"></i><span>Email</span></a></li>
                <li class="<?php if ($_SESSION['active'] === 'calendar'): echo 'active'; endif; ?>"><a
                            href="calendar.php" class="iq-waves-effect"><i class="ri-calendar-2-line"></i><span>Calendrier</span></a>
                </li>
                <li class="<?php if ($_SESSION['active'] === 'list-pricing'): echo 'active'; endif; ?>"><a
                            href="list-pricing.php" class="iq-waves-effect"><i class="ri-price-tag-line"></i><span>Pricing</span></a>
                </li>
                <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                    <li class="<?php if ($_SESSION['active'] === 'settings'): echo 'active'; endif; ?>"><a href="#"
                                                                                                           class="iq-waves-effect"><i
                                    class="ri-settings-2-line"></i><span>Paramètres</span></a></li>
                <?php endif; ?>
                <li class="<?php if ($_SESSION['active'] === 'mobile-app'): echo 'active'; endif; ?>"><a href="soon.php"
                                                                                                         class="iq-waves-effect"><i
                                class="ri-phone-line"></i><span>Appli mobile</span></a></li>
                <li class="<?php if ($_SESSION['active'] === 'mobile-app'): echo 'active'; endif; ?>"><a
                            href="login.php" class="iq-waves-effect"><i class="ri-login-circle-line"></i><span>Déconnexion</span></a>
                </li>
                <!--                <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Todo</span></a></li>-->
                <!--                <li><a href="chat.html" class="iq-waves-effect"><i class="ri-message-line"></i><span>Chat</span></a></li>-->
                <!--                <li>-->
                <!--                    <a href="#ecommerce" class="iq-waves-effect collapsed" data-toggle="collapse"-->
                <!--                       aria-expanded="false"><i class="ri-shopping-cart-line"></i><span>E-commerce</span><i-->
                <!--                            class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
                <!--                    <ul id="ecommerce" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
                <!--                        <li><a href="e-commerce-product-list.html"><i class="ri-file-list-line"></i>Product Listing</a>-->
                <!--                        </li>-->
                <!--                        <li><a href="e-commerce-product-detail.html"><i class="ri-pages-line"></i>Product-->
                <!--                                Details</a></li>-->
                <!--                        <li><a href="e-commerce-checkout.html"><i class="ri-shopping-cart-2-line"></i>Checkout</a>-->
                <!--                        </li>-->
                <!--                        <li><a href="e-commerce-wishlist.html"><i class="ri-heart-line"></i>Wishlist</a></li>-->
                <!--                    </ul>-->
                <!--                </li>-->
<!--                <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Components</span></li>-->
<!--                <li>-->
<!--                    <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-pencil-ruler-line"></i><span>UI Elements</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="ui-elements" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="ui-colors.html"><i class="ri-font-color"></i>colors</a></li>-->
<!--                        <li><a href="ui-typography.html"><i class="ri-text"></i>Typography</a></li>-->
<!--                        <li><a href="ui-alerts.html"><i class="ri-alert-line"></i>Alerts</a></li>-->
<!--                        <li><a href="ui-badges.html"><i class="ri-building-3-line"></i>Badges</a></li>-->
<!--                        <li><a href="ui-breadcrumb.html"><i class="ri-menu-2-line"></i>Breadcrumb</a></li>-->
<!--                        <li><a href="ui-buttons.html"><i class="ri-checkbox-blank-line"></i>Buttons</a></li>-->
<!--                        <li><a href="ui-cards.html"><i class="ri-bank-card-line"></i>Cards</a></li>-->
<!--                        <li><a href="ui-carousel.html"><i class="ri-slideshow-line"></i>Carousel</a></li>-->
<!--                        <li><a href="ui-embed-video.html"><i class="ri-slideshow-2-line"></i>Video</a></li>-->
<!--                        <li><a href="ui-grid.html"><i class="ri-grid-line"></i>Grid</a></li>-->
<!--                        <li><a href="ui-images.html"><i class="ri-image-line"></i>Images</a></li>-->
<!--                        <li><a href="ui-list-group.html"><i class="ri-file-list-3-line"></i>list Group</a></li>-->
<!--                        <li><a href="ui-media-object.html"><i class="ri-camera-line"></i>Media</a></li>-->
<!--                        <li><a href="ui-modal.html"><i class="ri-stop-mini-line"></i>Modal</a></li>-->
<!--                        <li><a href="ui-notifications.html"><i class="ri-notification-line"></i>Notifications</a>-->
<!--                        </li>-->
<!--                        <li><a href="ui-pagination.html"><i class="ri-pages-line"></i>Pagination</a></li>-->
<!--                        <li><a href="ui-popovers.html"><i class="ri-folder-shield-2-line"></i>Popovers</a></li>-->
<!--                        <li><a href="ui-progressbars.html"><i class="ri-battery-low-line"></i>Progressbars</a></li>-->
<!--                        <li><a href="ui-tabs.html"><i class="ri-database-line"></i>Tabs</a></li>-->
<!--                        <li><a href="ui-tooltips.html"><i class="ri-record-mail-line"></i>Tooltips</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#forms" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i-->
<!--                                class="ri-profile-line"></i><span>Forms</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="forms" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="form-layout.html"><i class="ri-tablet-line"></i>Form Elements</a></li>-->
<!--                        <li><a href="form-validation.html"><i class="ri-device-line"></i>Form Validation</a></li>-->
<!--                        <li><a href="form-switch.html"><i class="ri-toggle-line"></i>Form Switch</a></li>-->
<!--                        <li><a href="form-chechbox.html"><i class="ri-checkbox-line"></i>Form Checkbox</a></li>-->
<!--                        <li><a href="form-radio.html"><i class="ri-radio-button-line"></i>Form Radio</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#wizard-form" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-archive-drawer-line"></i><span>Forms Wizard</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="wizard-form" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="form-wizard.html"><i class="ri-clockwise-line"></i>Simple Wizard</a></li>-->
<!--                        <li><a href="form-wizard-validate.html"><i class="ri-clockwise-2-line"></i>Validate-->
<!--                                Wizard</a></li>-->
<!--                        <li><a href="form-wizard-vertical.html"><i class="ri-anticlockwise-line"></i>Vertical Wizard</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#tables" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-table-line"></i><span>Table</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="tables" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="tables-basic.html"><i class="ri-table-line"></i>Basic Tables</a></li>-->
<!--                        <li><a href="data-table.html"><i class="ri-database-line"></i>Data Table</a></li>-->
<!--                        <li><a href="table-editable.html"><i class="ri-refund-line"></i>Editable Table</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#charts" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-pie-chart-box-line"></i><span>Charts</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="charts" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="chart-morris.html"><i class="ri-file-chart-line"></i>Morris Chart</a></li>-->
<!--                        <li><a href="chart-high.html"><i class="ri-bar-chart-line"></i>High Charts</a></li>-->
<!--                        <li><a href="chart-am.html"><i class="ri-folder-chart-line"></i>Am Charts</a></li>-->
<!--                        <li><a href="chart-apex.html"><i class="ri-folder-chart-2-line"></i>Apex Chart</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#icons" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i-->
<!--                                class="ri-list-check"></i><span>Icons</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="icons" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="icon-dripicons.html"><i class="ri-stack-line"></i>Dripicons</a></li>-->
<!--                        <li><a href="icon-fontawesome-5.html"><i class="ri-facebook-fill"></i>Font Awesome 5</a>-->
<!--                        </li>-->
<!--                        <li><a href="icon-lineawesome.html"><i class="ri-keynote-line"></i>line Awesome</a></li>-->
<!--                        <li><a href="icon-remixicon.html"><i class="ri-remixicon-line"></i>Remixicon</a></li>-->
<!--                        <li><a href="icon-unicons.html"><i class="ri-underline"></i>unicons</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Pages</span></li>-->
<!--                <li>-->
<!--                    <a href="#authentication" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-pages-line"></i><span>Authentication</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="authentication" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="sign-in.html"><i class="ri-login-box-line"></i>Login</a></li>-->
<!--                        <li><a href="sign-up.html"><i class="ri-login-circle-line"></i>Register</a></li>-->
<!--                        <li><a href="pages-recoverpw.html"><i class="ri-record-mail-line"></i>Recover Password</a>-->
<!--                        </li>-->
<!--                        <li><a href="pages-confirm-mail.html"><i class="ri-file-code-line"></i>Confirm Mail</a></li>-->
<!--                        <li><a href="pages-lock-screen.html"><i class="ri-lock-line"></i>Lock Screen</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#map-page" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-map-pin-user-line"></i><span>Maps</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="map-page" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="pages-map.html"><i class="ri-google-line"></i>Google Map</a></li>-->
<!--                        <li><a href="#"><i class="ri-map-pin-range-line"></i>Vector Map</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#extra-pages" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-pantone-line"></i><span>Extra Pages</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="extra-pages" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="pages-timeline.html"><i class="ri-map-pin-time-line"></i>Timeline</a></li>-->
<!--                        <li><a href="pages-invoice.html"><i class="ri-question-answer-line"></i>Invoice</a></li>-->
<!--                        <li><a href="blank-page.html"><i class="ri-invision-line"></i>Blank Page</a></li>-->
<!--                        <li><a href="pages-error.html"><i class="ri-error-warning-line"></i>Error 404</a></li>-->
<!--                        <li><a href="pages-error-500.html"><i class="ri-error-warning-line"></i>Error 500</a></li>-->
<!--                        <li><a href="pages-pricing.html"><i class="ri-price-tag-line"></i>Pricing</a></li>-->
<!--                        <li><a href="pages-pricing-one.html"><i class="ri-price-tag-2-line"></i>Pricing 1</a></li>-->
<!--                        <li><a href="pages-maintenance.html"><i class="ri-archive-line"></i>Maintenance</a></li>-->
<!--                        <li><a href="pages-comingsoon.html"><i class="ri-mastercard-line"></i>Coming Soon</a></li>-->
<!--                        <li><a href="pages-faq.html"><i class="ri-compasses-line"></i>Faq</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                       aria-expanded="false"><i class="ri-record-circle-line"></i><span>Menu Level</span><i-->
<!--                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                    <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">-->
<!--                        <li><a href="#"><i class="ri-record-circle-line"></i>Menu 1</a></li>-->
<!--                        <li><a href="#"><i class="ri-record-circle-line"></i>Menu 2</a>-->
<!--                            <ul>-->
<!--                                <li>-->
<!--                                    <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"-->
<!--                                       aria-expanded="false"><i-->
<!--                                                class="ri-play-circle-line"></i><span>Sub-menu</span><i-->
<!--                                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>-->
<!--                                    <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">-->
<!--                                        <li><a href="#"><i class="ri-record-circle-line"></i>Sub-menu 1</a></li>-->
<!--                                        <li><a href="#"><i class="ri-record-circle-line"></i>Sub-menu 2</a></li>-->
<!--                                        <li><a href="#"><i class="ri-record-circle-line"></i>Sub-menu 3</a></li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li><a href="#"><i class="ri-record-circle-line"></i>Menu 3</a></li>-->
<!--                        <li><a href="#"><i class="ri-record-circle-line"></i>Menu 4</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>