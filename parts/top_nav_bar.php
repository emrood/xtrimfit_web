<?php

require_once('db/Rate.php');
require_once('db/Constants.php');

$rate = Rate::getById(2);

$from_date_filter =  date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
$to_date_filter = date('Y-m-d');

if(isset($_GET)){
    if(isset($_GET['from_date'])){
        $from_date_filter = $_GET['from_date'];
    }

    if(isset($_GET['to_date'])){
        $to_date_filter = $_GET['to_date'];
    }

}


?>


<div class="iq-top-navbar" style="margin-right: 0 !important;">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="index.php" class="logo">
                    <img src="images/logo.gif" class="img-fluid" alt="">
                    <span></span>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="navbar-left" style="display: flex; flex-grow: 1.5;">
                <button style="margin-left: 20px; display: inline;" type="button" class="btn mb-1 btn-success">
                    Taux du jour <span class="badge badge-light ml-2"><?= $rate['value'] . ' HTG' ?></span>
                </button>

                <div style="display: inline; margin-left: 20px;">

                </div>

                <div class="iq-search-bar" style="flex-grow: 3">
                    <div class="row col-md-16" style="padding-left: 5px; border-left: 2px solid var(--iq-primary);">
                        <div class="col col-md-4" style="display: flex">
                            <input type="date" name="filter_date_from" class="form-control" id="exampleInputdate" value="<?= $from_date_filter ?>">
                        </div>
                        <i class="ri ri-arrow-right-line" style="padding-top: 10px;"></i>
                        <div class="col col-md-4" style="display: flex">
                            <input type="date" onchange="filterByDate()" name="filter_date_to" class="form-control" id="exampleInputdate" value="<?= $to_date_filter ?>">
                        </div>
                    </div>
                </div>


                <!--                    <ul id="topbar-data-icon" class="d-flex p-0 topbar-menu-icon">-->
                <!--                        <li class="nav-item">-->
                <!--                            <a href="index.html.backup" class="nav-link font-weight-bold search-box-toggle"><i class="ri-home-4-line"></i></a>-->
                <!--                        </li>-->
                <!--                        <li><a href="chat.html" class="nav-link"><i class="ri-message-line"></i></a></li>-->
                <!--                        <li><a href="e-commerce-product-list.html" class="nav-link"><i class="ri-file-list-line"></i></a></li>-->
                <!--                        <li><a href="profile.html" class="nav-link"><i class="ri-question-answer-line"></i></a></li>-->
                <!--                        <li><a href="todo.html" class="nav-link router-link-exact-active router-link-active"><i class="ri-chat-check-line"></i></a></li>-->
                <!--                        <li><a href="app/index.html" class="nav-link"><i class="ri-inbox-line"></i></a></li>-->
                <!--                    </ul>-->
                <!--                                    <div class="iq-search-bar">-->
                <!--                                        <form action="#" class="searchbox">-->
                <!--                                            <input type="text" class="text search-input" placeholder="Type here to search...">-->
                <!--                                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>-->
                <!--                                            <div class="searchbox-datalink">-->
                <!--                                                <h6 class="pl-3 pt-3">Pages</h6>-->
                <!--                                                <ul class="m-0 pl-3 pr-3 pb-3">-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="index.html.backup" class="nav-link router-link-exact-active router-link-active pr-2"><i class="ri-home-4-line pr-2"></i>Dashboard</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="dashboard-1.html" class="nav-link router-link-exact-active router-link-active pr-2"><i class="ri-home-3-line pr-2"></i>Dashboard-1</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="chat.html" class="nav-link"><i class="ri-message-line pr-2"></i>Chat</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="calendar.html" class="nav-link"><i class="ri-calendar-2-line pr-2"></i>Calendar</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="profile.html" class="nav-link"><i class="ri-profile-line pr-2"></i>Profile</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="todo.html" class="nav-link"><i class="ri-chat-check-line pr-2"></i>Todo</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="app/index.html" class="nav-link"><i class="ri-mail-line pr-2"></i>Email</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="e-commerce-product-list.html" class="nav-link"><i class="ri-message-line pr-2"></i>Product Listing</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="e-commerce-product-detail.html" class="nav-link"><i class="ri-file-list-line pr-2"></i>Product Details</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="pages-faq.html" class="nav-link"><i class="ri-compasses-line pr-2"></i>Faq</a></li>-->
                <!--                                                    <li class="iq-bg-primary-hover rounded"><a href="form-wizard.html" class="nav-link"><i class="ri-clockwise-line pr-2"></i>Form-wizard</a></li>-->
                <!--                                                </ul>-->
                <!--                                            </div>-->
                <!--                                        </form>-->
                <!--                                    </div>-->
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-label="Toggle navigation">
                <i class="ri-menu-3-line"></i>
            </button>
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                    <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                </div>
            </div>
            <div class="collapse navbar-collapse" style="width: 70px !important; flex-grow: 0.5" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-list">
                    <li class="nav-item">
                        <!--                            <a class="search-toggle iq-waves-effect language-title" href="#"><img src="images/small/flag-01.png" alt="img-flaf" class="img-fluid mr-1" style="height: 16px; width: 16px;" /> English <i class="ri-arrow-down-s-line"></i></a>-->
                        <!--                            <div class="iq-sub-dropdown">-->
                        <!--                                <a class="iq-sub-card" href="#"><img src="images/small/flag-02.png" alt="img-flaf" class="img-fluid mr-2" />French</a>-->
                        <!--                                <a class="iq-sub-card" href="#"><img src="images/small/flag-03.png" alt="img-flaf" class="img-fluid mr-2" />Spanish</a>-->
                        <!--                                <a class="iq-sub-card" href="#"><img src="images/small/flag-04.png" alt="img-flaf" class="img-fluid mr-2" />Italian</a>-->
                        <!--                                <a class="iq-sub-card" href="#"><img src="images/small/flag-05.png" alt="img-flaf" class="img-fluid mr-2" />German</a>-->
                        <!--                                <a class="iq-sub-card" href="#"><img src="images/small/flag-06.png" alt="img-flaf" class="img-fluid mr-2" />Japanese</a>-->
                        <!---->
                        <!--                            </div>-->
                    </li>

                    <li class="nav-item">
                        <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                            <a href="#" class="search-toggle iq-waves-effect">
                                <div id="lottie-beil"></div>
                                <span class="bg-danger dots"></span>
                            </a>
                            <div class="iq-sub-dropdown">
                                <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                        <div class="bg-primary p-3">
                                            <h5 class="mb-0 text-white">Notifications
                                                <small class="badge  badge-light float-right pt-1">0</small>
                                            </h5>
                                        </div>

                                        <!--                                    <a href="#" class="iq-sub-card">-->
                                        <!--                                        <div class="media align-items-center">-->
                                        <!--                                            <div class="">-->
                                        <!--                                                <img class="avatar-40 rounded" src="../images/user/01.jpg" alt="">-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="media-body ml-3">-->
                                        <!--                                                <h6 class="mb-0 ">Emma Watson Nik</h6>-->
                                        <!--                                                <small class="float-right font-size-12">Just Now</small>-->
                                        <!--                                                <p class="mb-0">95 MB</p>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    </a>-->
                                        <!--                                    <a href="#" class="iq-sub-card">-->
                                        <!--                                        <div class="media align-items-center">-->
                                        <!--                                            <div class="">-->
                                        <!--                                                <img class="avatar-40 rounded" src="../images/user/02.jpg" alt="">-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="media-body ml-3">-->
                                        <!--                                                <h6 class="mb-0 ">New customer is join</h6>-->
                                        <!--                                                <small class="float-right font-size-12">5 days ago</small>-->
                                        <!--                                                <p class="mb-0">Jond Nik</p>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    </a>-->
                                        <!--                                    <a href="#" class="iq-sub-card">-->
                                        <!--                                        <div class="media align-items-center">-->
                                        <!--                                            <div class="">-->
                                        <!--                                                <img class="avatar-40 rounded" src="../images/user/03.jpg" alt="">-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="media-body ml-3">-->
                                        <!--                                                <h6 class="mb-0 ">Two customer is left</h6>-->
                                        <!--                                                <small class="float-right font-size-12">2 days ago</small>-->
                                        <!--                                                <p class="mb-0">Jond Nik</p>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    </a>-->
                                        <!--                                    <a href="#" class="iq-sub-card">-->
                                        <!--                                        <div class="media align-items-center">-->
                                        <!--                                            <div class="">-->
                                        <!--                                                <img class="avatar-40 rounded" src="../images/user/04.jpg" alt="">-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="media-body ml-3">-->
                                        <!--                                                <h6 class="mb-0 ">New Mail from Fenny</h6>-->
                                        <!--                                                <small class="float-right font-size-12">3 days ago</small>-->
                                        <!--                                                <p class="mb-0">Jond Nik</p>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    </a>-->
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                        <?php if (!empty($_SESSION['user']['picture'])): ?>
                            <img src="images/user/<?= $_SESSION['user']['picture'] ?>" class="img-fluid rounded mr-3"
                                 alt="user">
                        <?php else: ?>
                            <img src="images/logo.jpeg" class="img-fluid rounded mr-3" alt="user">
                        <?php endif; ?>
                        <div class="caption">
                            <h6 class="mb-0 line-height text-white">
                                <?php
                                echo $_SESSION['user']['name']
                                ?>
                            </h6>
                            <span class="font-size-12 text-white"></span>
                        </div>
                    </a>
                    <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                            <div class="iq-card-body p-0 ">
                                <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white line-height">
                                        Hello <?php echo $_SESSION['user']['name'] ?></h5>
                                    <span class="text-white font-size-12"></span>
                                </div>
                                <!--                                <a href="../profile.html" class="iq-sub-card iq-bg-primary-hover">-->
                                <!--                                    <div class="media align-items-center">-->
                                <!--                                        <div class="rounded iq-card-icon iq-bg-primary">-->
                                <!--                                            <i class="ri-file-user-line"></i>-->
                                <!--                                        </div>-->
                                <!--                                        <div class="media-body ml-3">-->
                                <!--                                            <h6 class="mb-0 ">Mon profile</h6>-->
                                <!--                                            <p class="mb-0 font-size-12">Afficher les détails du profil personnel.</p>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                </a>-->
                                <a href="add-user.php?user_id=<?= $_SESSION['user']['id'] ?>"
                                   class="iq-sub-card iq-bg-primary-hover">
                                    <div class="media align-items-center">
                                        <div class="rounded iq-card-icon iq-bg-primary">
                                            <i class="ri-profile-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0 ">Editer le profil</h6>
                                            <p class="mb-0 font-size-12">Modifiez vos données personnelles.</p>
                                        </div>
                                    </div>
                                </a>
                                <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                                    <a href="setting.php" class="iq-sub-card iq-bg-primary-hover">
                                        <div class="media align-items-center">
                                            <div class="rounded iq-card-icon iq-bg-primary">
                                                <i class="ri-account-box-line"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Paramètres </h6>
                                                <p class="mb-0 font-size-12">Gérez les paramètres de l'application.</p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <div class="d-inline-block w-100 text-center p-3">
                                    <a class="bg-primary iq-sign-btn" href="login.php" role="button">Déconnexion<i
                                                class="ri-login-box-line ml-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>


    </div>
</div>
