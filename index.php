<?php 
include("config/db.php");
session_start();
if(!isset($_SESSION["name"]) && ($_SESSION["role"] !== 'admin')) {

header("location: login.php");
exit("Access Denied");
}


$sql = "SELECT * FROM users  WHERE role ='admin'";
$result = $con->query($sql);
$row = $result->fetch_assoc();



$SQL = "SELECT COUNT(*) AS total FROM users WHERE role = 'user'";
$RESULT = $con->query($SQL);
$userdata = $RESULT->fetch_assoc();
$totalUsers = $userdata["total"];


$postsql = "SELECT COUNT(*) AS total FROM posts ";
$postresult = $con->query($postsql);
$postdata = $postresult->fetch_assoc();
$totalPosts = $postdata["total"];

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>


    <!-- =====================================================
         BOOTSTRAP 5 CSS
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    <!-- =====================================================
         YOUR CSS
    ====================================================== -->

    <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="d-flex min-vh-100">

    <aside
        class="bg-dark text-white p-3 d-flex flex-column"
        style="width: 250px;"
    >

        <!-- LOGO -->

        <div class="text-center mb-4">

            <h3>
                <i class="bi bi-speedometer2"></i>
                MyPanel
            </h3>

            <small class="text-secondary">
                Admin Dashboard
            </small>

        </div>


        <!-- MENU -->

        <div class="nav nav-pills flex-column gap-2">


            <button
                class="nav-link active text-start"
                data-page="dashboard"
            >

                <i class="bi bi-house-door me-2"></i>

                Dashboard

            </button>


            <button
                class="nav-link text-white text-start"
                data-page="users"
                id="userbtn"
            >

                <i class="bi bi-people me-2"></i>

                Users

            </button>


            <button
                class="nav-link text-white text-start"
                data-page="posts"
                >

                <i class="bi bi-file-earmark-text me-2"></i>

                Posts

            </button>

            <button class=" nav-link text-white text-start "
             data-page="contracts">

                <i class="bi bi-file-earmark-text"></i>

                Contracts

            </button>

            <button
                class="nav-link text-white text-start"
                data-page="comments"
            >

                <i class="bi bi-chat-left-text me-2"></i>

                Comments

            </button>


            <button
                class="nav-link text-white text-start"
                data-page="categories"
            >

                <i class="bi bi-grid me-2"></i>

                Categories

            </button>


            <button
                class="nav-link nav-setting text-white text-start"
                data-page="settings"
            >

                <i class="bi bi-gear me-2"></i>

                Settings

            </button>


        </div>


        <!-- SIDEBAR BOTTOM -->

        <div class="mt-auto">


            <hr>


            <button
                class="btn btn-outline-light w-100"
                id="logoutBtn"
            >

                <i class="bi bi-box-arrow-right me-2"></i>

                Logout

            </button>


        </div>


    </aside>




    <!-- =====================================================
         RIGHT SIDE
    ====================================================== -->

    <div class="flex-grow-1">


        <!-- =================================================
             TOP NAVBAR
        ================================================== -->

        <nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3">


            <div class="container-fluid">


                <!-- MOBILE SIDEBAR BUTTON -->

                <button
                    class="btn btn-dark d-lg-none me-3"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar"
                >

                    <i class="bi bi-list"></i>

                </button>


                <!-- PAGE TITLE -->

                <span
                    class="navbar-brand fw-bold"
                    id="pageTitle"
                >

                    Dashboard

                </span>


                <!-- RIGHT SIDE -->

                <div class="d-flex align-items-center gap-3">


                    <!-- NOTIFICATION -->

                    <button
                        class="btn btn-light position-relative"
                    >

                        <i class="bi bi-bell fs-5"></i>

                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        >
                            3
                        </span>

                    </button>


                    <!-- ADMIN PROFILE -->

                    <div class="dropdown">


                        <button
                            class="btn dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown"
                        >

                            <img
                                src="uploads/<?php echo $row['pfp']; ?>"
                                width="40"
                                height="40"
                                class="rounded-circle object-fit-cover"
                                alt="Admin"
                            >

                            <span>
                                <?php echo $row['name'] ?>
                            </span>

                        </button>


                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="admin/profile.php"
                                    data-page="profile"
                                >
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="#"
                                    id="settingbtn"
                                    data-page="settings"
                                >
                                    <i class="bi bi-gear me-2"></i>
                                    Settings
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a
                                    class="dropdown-item text-danger"
                                    href="#"
                                    id="logoutDropdown"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </a>
                            </li>

                        </ul>


                    </div>


                </div>


            </div>


        </nav>




        <!-- =================================================
             MAIN CONTENT
        ================================================== -->

        <main class="p-4">


            <!-- =================================================
                 DASHBOARD PAGE
            ================================================== -->

            <section id="dashboardPage">


                <div class="mb-4">

                    <h2>
                        Dashboard Overview
                    </h2>

                    <p class="text-muted">
                        Welcome back, Admin.
                    </p>

                </div>



                <!-- STAT CARDS -->

                <div class="row g-4 mb-4">


                    <!-- USERS -->

                    <div class="col-md-6 col-xl-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <p class="text-muted mb-1">
                                            Total Users
                                        </p>

                                        <h2 id="totalUsers">
                                            <?php echo $totalUsers; ?>
                                        </h2>

                                    </div>

                                    <div
                                        class="fs-1 text-primary"
                                    >
                                        <i class="bi bi-people"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- POSTS -->

                    <div class="col-md-6 col-xl-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <p class="text-muted mb-1">
                                            Total Posts
                                        </p>

                                        <h2 id="totalPosts">
                                            <?php echo $totalPosts ; ?>
                                        </h2>

                                    </div>

                                    <div
                                        class="fs-1 text-success"
                                    >
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- COMMENTS -->

                    <div class="col-md-6 col-xl-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <p class="text-muted mb-1">
                                            Comments
                                        </p>

                                        <h2 id="totalComments">
                                            0
                                        </h2>

                                    </div>

                                    <div
                                        class="fs-1 text-warning"
                                    >
                                        <i class="bi bi-chat-left-text"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- CATEGORIES -->

                    <div class="col-md-6 col-xl-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <p class="text-muted mb-1">
                                            Categories
                                        </p>

                                        <h2 id="totalCategories">
                                            0
                                        </h2>

                                    </div>

                                    <div
                                        class="fs-1 text-danger"
                                    >
                                        <i class="bi bi-grid"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>




                <!-- =================================================
                     RECENT POSTS
                ================================================== -->

                <div class="card shadow-sm border-0">


                    <div
                        class="card-header bg-white d-flex justify-content-between align-items-center"
                    >

                        <h5 class="mb-0">
                            Recent Posts
                        </h5>

                        <button
                            class="btn btn-primary btn-sm"
                            data-page="posts"
                            id="viewall"
                        >
                            View All
                        </button>

                    </div>


                    <div class="card-body">


                        <div class="table-responsive">


                            <table class="table table-hover align-middle">


                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>User</th>

                                        <th>Content</th>

                                        <th>Date</th>
                                        
                                        <th>Status</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>


                                <tbody id="recentPostsTable">

                                    <!-- AJAX DATA -->

                                </tbody>


                            </table>


                        </div>


                    </div>


                </div>


            </section>




            <!-- =================================================
                 USERS PAGE
            ================================================== -->

            <section
                id="usersPage"
                class="d-none"
            >


                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >

                    <div>

                        <h2>
                            Users
                        </h2>

                        <p class="text-muted">
                            Manage registered users.
                        </p>

                    </div>


                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addUserModal"
                        id="addUser"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add User

                    </button>

                </div>



                <!-- SEARCH -->

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="row g-2">

                            <div class="col-md-10">

                                <input
                                    type="text"
                                    id="userSearch"
                                    class="form-control"
                                    placeholder="Search users..."
                                >

                            </div>

                            <div class="col-md-2">

                                <button
                                    class="btn btn-primary w-100"
                                    id="searchUsers"
                                >

                                    <i class="bi bi-search"></i>

                                    Search

                                </button>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- USERS TABLE -->

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table
                                class="table table-hover align-middle"
                            >

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>User</th>

                                        <th>Email</th>

                                        <th>Role</th>

                                        <th>Status</th>

                                        <th>Actions</th>

                                    </tr>

                                </thead>


                                <tbody id="usersTable">

                                    <!-- PHP/AJAX USERS -->

                                </tbody>


                            </table>

                        </div>

                    </div>

                </div>


            </section>




            <!-- =================================================
                 POSTS PAGE
            ================================================== -->

            <section
                id="postsPage"
                class="d-none"
            >


                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >

                    <div>

                        <h2>
                            Posts
                        </h2>

                        <p class="text-muted">
                            Manage all posts.
                        </p>

                    </div>


                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addPostModal"
                        id="addPost"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add Post

                    </button>

                </div>



                <!-- POSTS TABLE -->

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table
                                class="table table-hover align-middle"
                            >

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Author</th>

                                        <th>Title</th>

                                        <th>Category</th>

                                        <th>Date</th>

                                        <th>Status</th>

                                        <th>Actions</th>

                                    </tr>

                                </thead>


                                <tbody id="postsTable">

                                    <!-- PHP/AJAX POSTS -->

                                </tbody>


                            </table>

                        </div>

                    </div>

                </div>


            </section>

<!-- ======================================================================== 
                CONTRACT PAGE  
                ===================================================-->



                <section class="d-none" id="contractsPage">


    


<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">Contracts</h2>
        <p class="text-muted mb-0">
            Manage and track all contracts from one place.
        </p>
    </div>

    <button class="btn btn-primary" id="createContractBtn">
        <i class="bi bi-plus-lg me-1"></i>
        Create Contract
    </button>

</div>


<!-- Contract Statistics -->
<div class="row g-3 mb-4">

    <!-- Total -->
    <div class="col-md-6 col-xl-3">

        <div class="stat-card contract-filter active"
             data-status="all">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">Total Contracts</p>
                    <h3 class="fw-bold mb-0">44</h3>
                </div>

                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- Pending -->
    <div class="col-md-6 col-xl-3">

        <div class="stat-card contract-filter"
             data-status="pending">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">Pending</p>
                    <h3 class="fw-bold mb-0">12</h3>
                </div>

                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- Ongoing -->
    <div class="col-md-6 col-xl-3">

        <div class="stat-card contract-filter"
             data-status="ongoing">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">Ongoing</p>
                    <h3 class="fw-bold mb-0">8</h3>
                </div>

                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-arrow-repeat"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- Completed -->
    <div class="col-md-6 col-xl-3">

        <div class="stat-card contract-filter"
             data-status="completed">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">Completed</p>
                    <h3 class="fw-bold mb-0">24</h3>
                </div>

                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>

            </div>

        </div>

    </div>

</div>


<!-- Contract List -->
<div class="card-box">

    <!-- List Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="fw-bold mb-1" id="contractListTitle">
                All Contracts
            </h5>

            <small class="text-muted">
                View and manage contracts
            </small>
        </div>


        <!-- Search -->
        <div class="input-group"
             style="max-width: 280px;">

            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>

            <input
                type="text"
                class="form-control"
                id="contractSearch"
                placeholder="Search contracts..."
            >

        </div>

    </div>


    <!-- Contracts -->
    <div id="contractList">


        <!-- Contract 1 -->
        <!-- <div class="contract-item contract-card"
             data-status="ongoing">

            <div class="row align-items-center g-3">

                <div class="col-lg-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="contract-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Website Development
                            </h6>

                            <small class="text-muted">
                                Contract #001
                            </small>

                        </div>

                    </div>

                </div>


                <div class="col-lg-2">

                    <small class="text-muted d-block">
                        Worker
                    </small>

                    <strong>
                        Jeff
                    </strong>

                </div>


                <div class="col-lg-2">

                    <small class="text-muted d-block">
                        Manager
                    </small>

                    <strong>
                        Ahmed
                    </strong>

                </div>


                <div class="col-lg-2">

                    <small class="text-muted d-block">
                        Deadline
                    </small>

                    <strong>
                        20 Oct 2026
                    </strong>

                </div>


                <div class="col-lg-2 text-lg-end">

                    <span class="badge bg-info-subtle text-info mb-2">
                        Ongoing
                    </span>

                    <br>

                    <button class="btn btn-sm btn-outline-primary">
                        View
                    </button>

                </div>

            </div>

        </div>

 -->
</div>


<!-- Create Contract Modal -->
<div class="modal fade"
     id="contractModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-bold">
                        Create Contract
                    </h5>

                    <small class="text-muted">
                        Create and assign a new contract.
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form id="contractForm">

                    <div class="row g-3">

                            <!-- -----ID ---- -->

                            <input type="hidden" name="id" id="contractID">

                        <!-- Title -->
                        <div class="col-md-12">

                            <label class="form-label">
                                Contract Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control"
                                placeholder="Enter contract title"
                            >

                        </div>


                        <!-- Description -->
                        <div class="col-md-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="4"
                                placeholder="Describe the contract..."
                            ></textarea>

                        </div>


                        <!-- Worker -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Assign Worker
                            </label>

                            <select
                                name="worker_id"
                                class=" users form-select">
<!-- 
                                <option value="">
                                    Select Worker
                                </option>

                                <option value="1">
                                    Jeff
                                </option>

                                <option value="2">
                                    Ali
                                </option>

                                <option value="3">
                                    Sara
                                </option> -->

                            </select>

                        </div>


                        <!-- Manager -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Assign Manager
                            </label>

                            <select
                                name="manager_id"
                                class=" users form-select">

                                <option value="">
                                    Select Manager
                                </option>

                                <option value="4">
                                    Ahmed
                                </option>

                                <option value="5">
                                    Hamza
                                </option>

                            </select>

                        </div>


                        <!-- Deadline -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Deadline
                            </label>

                            <input
                                type="date"
                                name="deadline"
                                class="form-control"
                            >

                        </div>


                        <!-- Status -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option value="pending">
                                    Pending
                                </option>

                                <option value="ongoing">
                                    Ongoing
                                </option>

                                <option value="completed">
                                    Completed
                                </option>

                            </select>

                        </div>


                    </div>

                </form>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <button
                    type="submit"
                    form="contractForm"
                    id="contractForm"
                    class="btn btn-primary">
                    Submit
                </button>

            </div>

        </div>

    </div>

</div>



<!-- =====================================================
     VIEW CONTRACT MODAL
     ===================================================== -->

<div class="modal fade"
     id="viewContractModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg overflow-hidden">


            <!-- ================= HEADER ================= -->

            <div class="modal-header border-0 p-4"
                 style="background: linear-gradient(135deg, #0d6efd, #084298); color: white;">

                <div class="d-flex align-items-center gap-3">

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; background:rgba(255,255,255,.15);">

                        <i class="bi bi-file-earmark-text fs-3"></i>

                    </div>

                    <div>

                        <div class="small opacity-75 mb-1">
                            CONTRACT DETAILS
                        </div>

                        <h4 class="fw-bold mb-0"
                            id="viewContractTitle">
                            Contract Title
                        </h4>

                        <small class="opacity-75"
                               id="viewContractNumber">
                            Contract #--
                        </small>

                    </div>

                </div>


                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <!-- ================= BODY ================= -->

            <div class="modal-body p-4">


                <!-- STATUS + DEADLINE -->

                <div class="row g-3 mb-4">


                    <!-- STATUS -->

                    <div class="col-md-6">

                        <div class="p-3 rounded-3 border bg-light">

                            <div class="d-flex align-items-center gap-3">

                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-info-subtle text-info"
                                     style="width:42px; height:42px;">

                                    <i class="bi bi-activity fs-5"></i>

                                </div>

                                <div>

                                    <small class="text-muted d-block">
                                        Status
                                    </small>

                                    <span class="badge bg-info-subtle text-info"
                                          id="viewContractStatus">
                                        --
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- DEADLINE -->

                    <div class="col-md-6">

                        <div class="p-3 rounded-3 border bg-light">

                            <div class="d-flex align-items-center gap-3">

                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning"
                                     style="width:42px; height:42px;">

                                    <i class="bi bi-calendar-event fs-5"></i>

                                </div>

                                <div>

                                    <small class="text-muted d-block">
                                        Deadline
                                    </small>

                                    <strong id="viewContractDeadline">
                                        --
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- ================= DESCRIPTION ================= -->

                <div class="mb-4">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <i class="bi bi-card-text text-primary"></i>

                        <h6 class="fw-bold mb-0">
                            Description
                        </h6>

                    </div>

                    <div class="p-3 rounded-3 border bg-light">

                        <p class="text-muted mb-0"
                           id="viewContractDescription">
                            --
                        </p>

                    </div>

                </div>



                <!-- ================= PEOPLE ================= -->

                <div class="mb-4">

                    <div class="d-flex align-items-center gap-2 mb-3">

                        <i class="bi bi-people text-primary"></i>

                        <h6 class="fw-bold mb-0">
                            Assigned People
                        </h6>

                    </div>


                    <div class="row g-3">


                        <!-- WORKER -->

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary"
                                         style="width:45px; height:45px;">

                                        <i class="bi bi-person fs-5"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Worker
                                        </small>

                                        <strong id="viewContractWorker">
                                            --
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- MANAGER -->

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success"
                                         style="width:45px; height:45px;">

                                        <i class="bi bi-person-badge fs-5"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Manager
                                        </small>

                                        <strong id="viewContractManager">
                                            --
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- ================= EXTRA INFO ================= -->

                <div>

                    <div class="d-flex align-items-center gap-2 mb-3">

                        <i class="bi bi-info-circle text-primary"></i>

                        <h6 class="fw-bold mb-0">
                            Contract Information
                        </h6>

                    </div>


                    <div class="row g-3">


                        <!-- CONTRACT ID -->

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <small class="text-muted d-block mb-1">
                                    Contract ID
                                </small>

                                <strong id="viewContractId">
                                    --
                                </strong>

                            </div>

                        </div>


                        <!-- CREATED DATE -->

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3">

                                <small class="text-muted d-block mb-1">
                                    Created
                                </small>

                                <strong id="viewContractCreated">
                                    --
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


            </div>


            <!-- ================= FOOTER ================= -->

            <div class="modal-footer border-0 bg-light p-3">


                <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg me-1"></i>
                    Close

                </button>


                <div class="ms-auto d-flex gap-2">


                    <!-- DELETE -->

                    <button type="button"
                            class="btn btn-outline-danger"
                            id="deleteContractBtn">

                        <i class="bi bi-trash3 me-1"></i>
                        Delete

                    </button>


                    <!-- EDIT -->

                    <button type="button"
                            class="btn btn-primary"
                            id="editContractBtn">

                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Contract

                    </button>


                </div>

            </div>


        </div>

    </div>

</div>

</section>


            <!-- =================================================
                 COMMENTS PAGE
            ================================================== -->

            <section
                id="commentsPage"
                class="d-none"
            >


                <h2>
                    Comments
                </h2>

                <p class="text-muted mb-4">
                    Manage user comments.
                </p>


                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>User</th>

                                        <th>Post</th>

                                        <th>Comment</th>

                                        <th>Date</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>


                                <tbody id="commentsTable">

                                    <!-- AJAX COMMENTS -->

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


            </section>




            <!-- =================================================
                 CATEGORIES PAGE
            ================================================== -->

            <section
                id="categoriesPage"
                class="d-none"
            >


                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >

                    <div>

                        <h2>
                            Categories
                        </h2>

                        <p class="text-muted">
                            Manage post categories.
                        </p>

                    </div>


                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add Category

                    </button>

                </div>



                <div class="row g-4" id="categoriesContainer">

                    <!-- AJAX CATEGORIES -->

                </div>


            </section>




            <!-- =================================================
                 SETTINGS PAGE
            ================================================== -->

            <section
                id="settingsPage"
                class="d-none"
            >


                <h2>
                    Settings
                </h2>

                <p class="text-muted mb-4">
                    Manage dashboard settings.
                </p>



                <div class="row">


                    <div class="col-lg-8">


                        <div class="card border-0 shadow-sm">


                            <div class="card-header bg-white">

                                <h5 class="mb-0">
                                    Account Settings
                                </h5>

                            </div>


                            <div class="card-body">


                                <form id="settingsForm">


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Username
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="adminUsername"
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Email
                                        </label>

                                        <input
                                            type="email"
                                            class="form-control"
                                            id="adminEmail"
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Profile Picture
                                        </label>

                                        <input
                                            type="file"
                                            class="form-control"
                                            id="adminImage"
                                        >

                                    </div>


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        Save Changes

                                    </button>


                                </form>


                            </div>


                        </div>


                    </div>


                </div>


            </section>




            <!-- =================================================
                 PROFILE PAGE
            ================================================== -->

            <section
                id="profilePage"
                class="d-none"
            >


                <h2>
                    My Profile
                </h2>


                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">


                        <div class="text-center">

                            <img
                                src=""
                                width="120"
                                height="120"
                                class="rounded-circle object-fit-cover mb-3"
                                alt="Profile"
                            >

                            <h3>
                                Admin
                            </h3>

                            <p class="text-muted">
                                Administrator
                            </p>

                        </div>


                    </div>

                </div>


            </section>


        </main>


    </div>


</div>




<!-- =========================================================
     ADD USER MODAL
========================================================= -->

<div
    class="modal fade"
    id="addUserModal"
    tabindex="-1"
>


    <div class="modal-dialog">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add User
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="addUserForm" >


                <div class="modal-body">


                    <div class="mb-3">

                            <input type="hidden"
                                    id="userId"
                            >


                       <label class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            profile-picture
                        </label>

                        <input type="file" id="profilePic">
                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save User
                    </button>

                </div>


            </form>


        </div>

    </div>

</div>




<!-- =========================================================
     ADD POST MODAL
========================================================= -->

<div
    class="modal fade"
    id="addPostModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add Post
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="addPostForm">


                <div class="modal-body">


                    <div class="mb-3">

                        <label class="form-label">
                            Title
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="postTitle"
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            class="form-select"
                            id="postCategory"
                        >

                            <option>
                                Technology
                            </option>

                            <option>
                                News
                            </option>

                            <option>
                                Education
                            </option>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Content
                        </label>

                        <textarea
                            class="form-control"
                            id="postContent"
                            rows="6"
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Featured Image
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="postImage"
                        >
                        <input
                         type="hidden"
                          id="postId"
                        >
                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Publish Post
                    </button>

                </div>


            </form>


        </div>

    </div>

</div>




<!-- =========================================================
     ADD CATEGORY MODAL
========================================================= -->

<div
    class="modal fade"
    id="addCategoryModal"
    tabindex="-1"
>


    <div class="modal-dialog">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add Category
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="addCategoryForm">


                <div class="modal-body">


                    <label class="form-label">
                        Category Name
                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="categoryName"
                    >


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Add Category
                    </button>

                </div>


            </form>


        </div>

    </div>

</div>




<!-- =========================================================
     JQUERY
========================================================= -->

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>



<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>



<!-- =========================================================
     YOUR JS / AJAX
========================================================= -->

<script src="js/ajax.js"></script>


</body>

</html>