<?php 
include("../config/db.php");
session_start();

if(!isset($_SESSION['name']) || ($_SESSION['role'] !== 'user')){
    header("location: ../login.php");
    exit("Access Denied");

}

$id = $_SESSION['id'];

$sql = "SELECT COUNT(*) AS total FROM posts  WHERE userid = '$id'";
$result = $con->query($sql);
$row = $result->fetch_assoc();
$totalpost = $row["total"];

$id = $_SESSION['id'];
$usersql = "SELECT * FROM users WHERE id = '$id'";
$useresult = $con->query($usersql);
$user = $useresult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Dashboard</title>


    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >
<link rel="stylesheet" href="../css/userpanel.css">

</head>


<body>


<!-- ================================================= -->
<!-- SIDEBAR -->
<!-- ================================================= -->

<aside class="sidebar">


    <div class="brand">

        MY<span>SPACE</span>

    </div>


    <nav>


        <button
            class="nav-link active w-100 border-0 text-start"
            data-page="home"
        >

            <i class="bi bi-house-door-fill me-2"></i>

            <span>Home</span>

        </button>


        <button
            class="nav-link w-100 border-0 text-start"
            id="posts"
            data-page="posts"
        >

            <i class="bi bi-file-earmark-text-fill me-2"></i>

            <span>My Posts</span>

        </button>


        <button
            class="createPost nav-link w-100 border-0 text-start"
            data-page="create"
        >

            <i class="bi bi-plus-circle-fill me-2"></i>

            <span>Create/Edit Post</span>

        </button>

<!-- CONTRACTS -->

<button
class="nav-link w-100 border-0 text-start"
id="contractsNav"
data-page="contracts"

>

<i class="bi bi-briefcase-fill me-2"></i>

<span>Contracts</span>

<span
    class="badge bg-danger float-end d-none"
    id="contractNotificationBadge"
>
    0
</span>


</button>


        <button
            class="nav-link w-100 border-0 text-start"
            data-page="comments"
        >

            <i class="bi bi-chat-left-text-fill me-2"></i>

            <span>Comments</span>

        </button>


        <button
            class="nav-link w-100 border-0 text-start"
            data-page="notifications"
        >

            <i class="bi bi-bell-fill me-2"></i>

            <span>Notifications</span>

            <span class="badge bg-danger float-end">
                3
            </span>

        </button>


        <button
            class="nav-link w-100 border-0 text-start"
            data-page="categories"
        >

            <i class="bi bi-grid-fill me-2"></i>

            <span>Categories</span>

        </button>


        <hr class="border-secondary">


        <button
            class="nav-link w-100 border-0 text-start"
            data-page="profile"
        >

            <i class="bi bi-person-circle me-2"></i>

            <span>Profile</span>

        </button>


        <button
            class="nav-link w-100 border-0 text-start"
            data-page="settings"
        >

            <i class="bi bi-gear-fill me-2"></i>

            <span>Settings</span>

        </button>


        <a
            href="../logout.php"
            class="nav-link text-danger"
        >

            <i class="bi bi-box-arrow-right me-2"></i>

            <span>Logout</span>

        </a>


    </nav>

</aside>



<!-- ================================================= -->
<!-- MAIN -->
<!-- ================================================= -->

<div class="main">


    <!-- TOPBAR -->

    <header class="topbar">


        <div>

            <h5 class="mb-0 fw-bold">

                User Dashboard

            </h5>

            <small class="text-muted">

                Welcome back 

            </small>

        </div>


        <div class="dropdown">


            <button
                class="btn border-0 dropdown-toggle d-flex align-items-center gap-2"
                data-bs-toggle="dropdown"
            >

                <img
                    src="../uploads/<?php echo $user['pfp']; ?>"
                    class="profile-img"
                >


                <div class="text-start d-none d-md-block">

                    <strong><?php echo $user['name'] ?></strong>

                    <small class="d-block text-muted">

                        User

                    </small>

                </div>

            </button>


            <ul class="dropdown-menu dropdown-menu-end">


                <li>

                    <button
                        class="dropdown-item"
                        data-page="profile"
                    >

                        <i class="bi bi-person me-2"></i>

                        Profile

                    </button>

                </li>


                <li>

                    <button
                        class="dropdown-item"
                        data-page="settings"
                    >

                        <i class="bi bi-gear me-2"></i>

                        Settings

                    </button>

                </li>


                <li>

                    <hr class="dropdown-divider">

                </li>


                <li>

                    <a
                        href="../logout.php"
                        class="dropdown-item text-danger"
                    >

                        Logout

                    </a>

                </li>


            </ul>

        </div>


    </header>



    <main class="content">



        <!-- ================================================= -->
        <!-- HOME -->
        <!-- ================================================= -->

        <section
            id="homePage"
            class="page-section active-page"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    <?php echo $user['name'] ?>

                </h1>

                <p class="subtitle">

                    Here's what's happening with your account.

                </p>

            </div>



            <!-- STATS -->

            <div class="row g-4 mb-4">


                <div class="col-md-4">

                    <div class="stat-card">


                        <div class="d-flex justify-content-between">


                            <div>

                                <p class="text-muted mb-1">

                                    My Posts

                                </p>


                                <h2 class="fw-bold">

                                    <?php echo ($totalpost);  ?>

                                </h2>

                            </div>


                            <div
                                class="stat-icon bg-primary-subtle text-primary"
                            >

                                <i class="bi bi-file-text-fill"></i>

                            </div>


                        </div>


                    </div>

                </div>



                <div class="col-md-4">

                    <div class="stat-card">


                        <div class="d-flex justify-content-between">


                            <div>

                                <p class="text-muted mb-1">

                                    Comments

                                </p>


                                <h2 class="fw-bold">

                                    38

                                </h2>

                            </div>


                            <div
                                class="stat-icon bg-success-subtle text-success"
                            >

                                <i class="bi bi-chat-fill"></i>

                            </div>


                        </div>


                    </div>

                </div>



                <div class="col-md-4">

                    <div class="stat-card">


                        <div class="d-flex justify-content-between">


                            <div>

                                <p class="text-muted mb-1">

                                    Notifications

                                </p>


                                <h2 class="fw-bold">

                                    3

                                </h2>

                            </div>


                            <div
                                class="stat-icon bg-warning-subtle text-warning"
                            >

                                <i class="bi bi-bell-fill"></i>

                            </div>


                        </div>


                    </div>

                </div>


            </div>



            <!-- CREATE BOX -->

            <div class="create-box mb-4">


                <div class="d-flex align-items-center gap-3">


                    <img
                        src="../uploads/<?php echo $user['pfp']; ?>"
                        class="profile-img"
                    >


                    <button
                        class="btn btn-light border text-start flex-grow-1"
                        data-page="create"
                    >

                        What's on your mind?

                    </button>


                    <button
                        class="btn btn-primary"
                        data-page="create"
                    >

                        <i class="bi bi-plus-lg"></i>

                    </button>


                </div>


            </div>



            <!-- RECENT POSTS -->

            <h4 class="mb-3">

                Your Recent Posts

            </h4>


            <div class="row g-4" id="recentposts">

            </div>


        </section>



        <!-- ================================================= -->
        <!-- MY POSTS -->
        <!-- ================================================= -->

        <section
            id="postsPage"
            class="page-section"
        >


            <div class="d-flex justify-content-between align-items-center mb-4">


                <div>

                    <h1 class="page-title">

                        My Posts

                    </h1>

                    <p class="subtitle">

                        Manage everything you've published.

                    </p>

                </div>


                <button
                    class="btn btn-primary createPost"
                    data-page="create"
                    
                >

                    <i class="bi bi-plus-lg"></i>

                    Create Post

                </button>


            </div>



            <div class="row g-4" id="showposts">


           

            </div>


        </section>



        <!-- ================================================= -->
        <!-- CREATE POST -->
        <!-- ================================================= -->

        <section
            id="createPage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    Create/Edit Post ✨

                </h1>

                <p class="subtitle">

                    Share something with the community.

                </p>

            </div>


            <div class="card-box p-4">


                <form
                    id="createPostForm"
                    enctype="multipart/form-data"
                >

                    <input type="hidden"  id="postId">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Post Title

                        </label>


                        <input
                            type="text"
                            name="title"
                            id="postTitle"
                            class="form-control"
                            placeholder="Give your post a title..."
                            value=""
                        >

                    </div>



                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Category

                        </label>


                        <select
                            class="form-select"
                            name="category"
                            id="postCategory"
                        >

                            <option value="">
                                Select Category
                            </option>

                            <option value="technology">
                                Technology
                            </option>

                            <option value="programming">
                                Programming
                            </option>

                            <option value="education">
                                Education
                            </option>

                            <option value="entertainment">
                                Entertainment
                            </option>

                        </select>

                    </div>



                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Thumbnail

                        </label>


                        <input
                            type="file"
                            name="thumbnail"
                            id="postThumbnail"
                            class="form-control"
                        >

                    </div>



                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            Description

                        </label>


                        <textarea
                            name="description"
                            id="postDescription"
                            class="form-control"
                            rows="8"
                            placeholder="Write your post..."
                        ></textarea>

                    </div>



                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >

                        <i class="bi bi-send me-1"></i>

                        Publish Post

                    </button>
                    <div id="msg"></div>

                </form>


            </div>


        </section>

<!-- ================================================= -->

<!-- CONTRACTS -->

<!-- ================================================= -->

<section
    id="contractsPage"
    class="page-section"
>

<!-- PAGE HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title">
            My Contracts
        </h1>

        <p class="subtitle">
            Manage your assigned work and track contract progress.
        </p>
    </div>

    <div>
        <button
            class="btn btn-outline-primary"
            id="refreshContractsBtn"
            type="button"
        >
            <i class="bi bi-arrow-clockwise me-1"></i>
            Refresh
        </button>
    </div>

</div>


<!-- ============================================= -->
<!-- CONTRACT STATS -->
<!-- ============================================= -->

<div class="row g-4 mb-4">

    <!-- TOTAL -->
    <div class="col-md-3">

        <div class="contract-stat">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">
                        Total Contracts
                    </p>

                    <h3
                        class="fw-bold mb-0"
                        id="totalContracts"
                    >
                        0
                    </h3>
                </div>

                <div class="contract-stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-briefcase-fill"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- PENDING -->
    <div class="col-md-3">

        <div class="contract-stat">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">
                        Pending
                    </p>

                    <h3
                        class="fw-bold mb-0"
                        id="pendingContracts"
                    >
                        0
                    </h3>
                </div>

                <div class="contract-stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- IN PROGRESS -->
    <div class="col-md-3">

        <div class="contract-stat">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">
                        In Progress
                    </p>

                    <h3
                        class="fw-bold mb-0"
                        id="activeContracts"
                    >
                        0
                    </h3>
                </div>

                <div class="contract-stat-icon bg-info-subtle text-info">
                    <i class="bi bi-play-circle-fill"></i>
                </div>

            </div>

        </div>

    </div>


    <!-- COMPLETED -->
    <div class="col-md-3">

        <div class="contract-stat">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <p class="text-muted mb-1">
                        Completed
                    </p>

                    <h3
                        class="fw-bold mb-0"
                        id="completedContracts"
                    >
                        0
                    </h3>
                </div>

                <div class="contract-stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

            </div>

        </div>

    </div>

</div>


<!-- ============================================= -->
<!-- CONTRACT FILTER -->
<!-- ============================================= -->

<div class="card-box p-3 mb-4">

    <div class="row g-3 align-items-center">

        <div class="col-md-5">

            <div class="input-group">

                <span class="input-group-text bg-transparent">
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


        <div class="col-md-3">

            <select
                class="form-select"
                id="contractStatusFilter"
            >

                <option value="all">
                    All Statuses
                </option>

                <option value="pending">
                    Pending
                </option>

                <option value="accepted">
                    Accepted
                </option>

                <option value="in_progress">
                    In Progress
                </option>

                <option value="under_review">
                    Under Review
                </option>

                <option value="completed">
                    Completed
                </option>

            </select>

        </div>


        <div class="col-md-3">

            <select
                class="form-select"
                id="contractRoleFilter"
            >

                <option value="all">
                    All Roles
                </option>

                <option value="worker">
                    Worker
                </option>

                <option value="manager">
                    Manager
                </option>

            </select>

        </div>


        <div class="col-md-1 text-end">

            <button
                class="btn btn-light border w-100"
                id="clearContractFilters"
                type="button"
                title="Clear filters"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

    </div>

</div>


<!-- ============================================= -->
<!-- CONTRACT LIST -->
<!-- ============================================= -->

<div
    class="row g-4"
    id="contractsContainer"
>

    <!--
        AJAX WILL LOAD CONTRACT CARDS HERE.
    -->

    <div
        class="col-12"
        id="contractsLoading"
    >

        <div class="card-box p-5 text-center">

            <div
                class="spinner-border text-primary mb-3"
                role="status"
            ></div>

            <p class="text-muted mb-0">
                Loading your contracts...
            </p>

        </div>

    </div>


    <!-- EMPTY STATE -->
    <div
        class="col-12 d-none"
        id="contractsEmpty"
    >

        <div class="card-box contract-empty">

            <div class="contract-empty-icon mb-3">
                <i class="bi bi-briefcase"></i>
            </div>

            <h4>
                No Contracts Yet
            </h4>

            <p class="text-muted mb-0">
                You don't have any contracts assigned to you yet.
            </p>

        </div>

    </div>

</div>

</section>

<!-- ================================================= -->

<!-- VIEW CONTRACT MODAL -->

<!-- ================================================= -->

<div
    class="modal fade"
    id="userContractModal"
    tabindex="-1"
    aria-hidden="true"
>

<div class="modal-dialog modal-xl modal-dialog-centered">

    <div class="modal-content border-0 shadow">

        <!-- HEADER -->

        <div class="contract-view-header">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <div class="mb-2">

                        <span
                            class="badge bg-light text-dark"
                            id="viewContractRole"
                        >
                            ROLE
                        </span>

                        <span
                            class="badge bg-warning text-dark"
                            id="viewContractStatus"
                        >
                            STATUS
                        </span>

                    </div>

                    <h3
                        class="mb-1"
                        id="viewContractTitle"
                    >
                        Contract Title
                    </h3>

                    <small class="opacity-75">
                        Contract #
                        <span id="viewContractId">
                            -
                        </span>
                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

        </div>


        <!-- BODY -->

        <div class="modal-body p-4">

            <!-- DESCRIPTION -->

            <div class="mb-4">

                <h6 class="fw-bold">
                    <i class="bi bi-file-text me-1"></i>
                    Contract Description
                </h6>

                <div
                    class="contract-detail-box"
                    id="viewContractDescription"
                >
                    No description available.
                </div>

            </div>


            <!-- PEOPLE + DEADLINE -->

            <div class="row g-3 mb-4">

                <!-- WORKER -->

                <div class="col-md-4">

                    <div class="contract-detail-box">

                        <div class="contract-detail-label">
                            Worker
                        </div>

                        <div
                            class="contract-detail-value"
                            id="viewContractWorker"
                        >
                            -
                        </div>

                    </div>

                </div>


                <!-- MANAGER -->

                <div class="col-md-4">

                    <div class="contract-detail-box">

                        <div class="contract-detail-label">
                            Manager
                        </div>

                        <div
                            class="contract-detail-value"
                            id="viewContractManager"
                        >
                            -
                        </div>

                    </div>

                </div>


                <!-- DEADLINE -->

                <div class="col-md-4">

                    <div class="contract-detail-box">

                        <div class="contract-detail-label">
                            Deadline
                        </div>

                        <div
                            class="contract-detail-value"
                            id="viewContractDeadline"
                        >
                            -
                        </div>

                    </div>

                </div>

            </div>


            <!-- PROGRESS -->

            <div class="mb-4">

                <div class="d-flex justify-content-between mb-2">

                    <h6 class="fw-bold mb-0">
                        Progress
                    </h6>

                    <span
                        class="fw-bold"
                        id="viewContractProgressText"
                    >
                        0%
                    </span>

                </div>

                <div class="progress contract-progress">

                    <div
                        class="progress-bar"
                        id="viewContractProgressBar"
                        role="progressbar"
                        style="width: 0%;"
                    ></div>

                </div>

            </div>


            <!-- ACTIVITY PREVIEW -->

            <div class="mb-4">

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <h6 class="fw-bold mb-0">
                        Recent Activity
                    </h6>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                        id="viewAllActivityBtn"
                    >
                        View All
                    </button>

                </div>

                <div
                    class="contract-detail-box"
                    id="contractActivityPreview"
                >

                    <div class="text-muted text-center py-3">
                        No recent activity.
                    </div>

                </div>

            </div>


            <!-- ACTION AREA -->

            <div
                class="contract-action-area"
                id="contractActionArea"
            >

                <div
                    class="d-flex flex-wrap gap-2"
                    id="contractActions"
                >

                    <!--
                        AJAX WILL INSERT ROLE-BASED
                        ACTION BUTTONS HERE.

                        Worker example:
                        Accept / Start Work / Submit Work

                        Manager example:
                        Review / Request Changes / Approve

                    -->

                </div>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Close
            </button>

        </div>

    </div>

</div>

</div>

<!-- ================================================= -->

<!-- SUBMIT WORK MODAL -->

<!-- ================================================= -->

<div
    class="modal fade"
    id="submitWorkModal"
    tabindex="-1"
    aria-hidden="true"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">

        <div class="modal-header">

            <h5 class="modal-title">
                <i class="bi bi-cloud-arrow-up me-2"></i>
                Submit Work
            </h5>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
            ></button>

        </div>


        <form
            id="submitWorkForm"
            enctype="multipart/form-data"
        >

            <div class="modal-body">

                <!-- IMPORTANT:
                     AJAX will fill this -->
                <input
                    type="hidden"
                    name="contract_id"
                    id="submitContractId"
                >


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Submission Title
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="submission_title"
                        id="submissionTitle"
                        placeholder="e.g. Authentication module completed"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        name="submission_description"
                        id="submissionDescription"
                        rows="5"
                        placeholder="Describe what you completed..."
                        required
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Upload Work
                    </label>

                    <input
                        type="file"
                        class="form-control"
                        name="work_file"
                        id="workFile"
                    >

                    <small class="text-muted">
                        Upload your project files, ZIP, screenshots,
                        documents, or other work.
                    </small>

                </div>


                <div id="submitWorkMsg"></div>

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
                    id="submitWorkBtn"
                >
                    <i class="bi bi-send me-1"></i>
                    Submit Work
                </button>

            </div>

        </form>

    </div>

</div>

</div>


        <!-- ================================================= -->
        <!-- COMMENTS -->
        <!-- ================================================= -->

        <section
            id="commentsPage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    Comments 💬

                </h1>

                <p class="subtitle">

                    See what people are saying about your posts.

                </p>

            </div>



            <div class="card-box">


                <div class="p-3 border-bottom">

                    <strong>

                        Comments on your posts

                    </strong>

                </div>



                <div class="p-3 border-bottom">


                    <div class="d-flex gap-3">


                        <img
                            src="https://i.pravatar.cc/100?img=15"
                            class="profile-img"
                        >


                        <div>

                            <strong>

                                Hamza

                            </strong>


                            <p class="mb-1">

                                Great post! This was really helpful.

                            </p>


                            <small class="text-muted">

                                On: My First Post • 10 minutes ago

                            </small>

                        </div>


                    </div>


                </div>



                <div class="p-3">


                    <div class="d-flex gap-3">


                        <img
                            src="https://i.pravatar.cc/100?img=18"
                            class="profile-img"
                        >


                        <div>

                            <strong>

                                Ahmed

                            </strong>


                            <p class="mb-1">

                                Nice explanation!

                            </p>


                            <small class="text-muted">

                                On: Learning PHP • 1 hour ago

                            </small>

                        </div>


                    </div>


                </div>


            </div>


        </section>



        <!-- ================================================= -->
        <!-- NOTIFICATIONS -->
        <!-- ================================================= -->

        <section
            id="notificationsPage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    Notifications 🔔

                </h1>

                <p class="subtitle">

                    Stay updated with your activity.

                </p>

            </div>



            <div class="card-box">


                <div class="notification">


                    <div class="notification-icon">

                        <i class="bi bi-chat-fill"></i>

                    </div>


                    <div>

                        <strong>

                            Hamza commented on your post

                        </strong>


                        <p class="mb-1 text-muted">

                            "Great post! This was really helpful."

                        </p>


                        <small class="text-muted">

                            10 minutes ago

                        </small>

                    </div>


                </div>



                <div class="notification">


                    <div class="notification-icon">

                        <i class="bi bi-check-circle-fill"></i>

                    </div>


                    <div>

                        <strong>

                            Your post was published

                        </strong>


                        <p class="mb-1 text-muted">

                            My First Post is now visible.

                        </p>


                        <small class="text-muted">

                            2 hours ago

                        </small>

                    </div>


                </div>



                <div class="notification">


                    <div class="notification-icon">

                        <i class="bi bi-heart-fill"></i>

                    </div>


                    <div>

                        <strong>

                            Someone liked your post

                        </strong>


                        <p class="mb-1 text-muted">

                            Your post received a new like.

                        </p>


                        <small class="text-muted">

                            Yesterday

                        </small>

                    </div>


                </div>


            </div>


        </section>



        <!-- ================================================= -->
        <!-- CATEGORIES -->
        <!-- ================================================= -->

        <section
            id="categoriesPage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    Categories 🏷️

                </h1>

                <p class="subtitle">

                    Explore content by category.

                </p>

            </div>



            <div class="row g-4">


                <div class="col-md-3">


                    <div class="category-card">


                        <div class="category-icon mb-3">

                            <i class="bi bi-cpu-fill"></i>

                        </div>


                        <h5>

                            Technology

                        </h5>


                        <small class="text-muted">

                            42 Posts

                        </small>


                    </div>


                </div>



                <div class="col-md-3">


                    <div class="category-card">


                        <div class="category-icon mb-3">

                            <i class="bi bi-code-slash"></i>

                        </div>


                        <h5>

                            Programming

                        </h5>


                        <small class="text-muted">

                            36 Posts

                        </small>


                    </div>


                </div>



                <div class="col-md-3">


                    <div class="category-card">


                        <div class="category-icon mb-3">

                            <i class="bi bi-book-fill"></i>

                        </div>


                        <h5>

                            Education

                        </h5>


                        <small class="text-muted">

                            25 Posts

                        </small>


                    </div>


                </div>



                <div class="col-md-3">


                    <div class="category-card">


                        <div class="category-icon mb-3">

                            <i class="bi bi-controller"></i>

                        </div>


                        <h5>

                            Entertainment

                        </h5>


                        <small class="text-muted">

                            18 Posts

                        </small>


                    </div>


                </div>


            </div>


        </section>



        <!-- ================================================= -->
        <!-- PROFILE -->
        <!-- ================================================= -->

        <section
            id="profilePage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    My Profile

                </h1>

                <p class="subtitle">

                    Manage your account information.

                </p>

            </div>



            <div class="card-box p-4">


                <div class="text-center mb-4">
                    
                    
                    <div class="position-relative d-inline-block">
                        
                        <img 
                        src="../uploads/<?php echo $user['pfp']; ?>"
                        class="rounded-circle profile-large"
                        width="100"
                        height="100"
                        id="profilePic"
                        >
                        
                        <form>
    <label 
        for="profileImage"
        class="btn btn-sm btn-dark position-absolute bottom-0 end-0"
    >
        Change
    </label>

    <input 
        type="file"
        id="profileImage"
        name="img"
        accept="image/*"
        hidden
    >

</div>

                    <h4 class="mt-3 mb-1">

                        <?php echo $user['name'] ?>

                    </h4>

                    <input type="hidden" name="id" id="userid" value="<?php $_SESSION['id'] ?>">

                    <p class="text-muted">

                        User

                    </p>


                </div>





                    <div class="row g-4">

                                       
                        <div class="col-md-6">

                            <label class="form-label">

                                Name

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="username"
                                value="<?php echo $user['name']; ?>"
                            >

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">

                                Email

                            </label>


                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="<?php echo $user['email']; ?>"
                            >

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">

                                New Password

                            </label>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                value="<?php echo $user['password'] ?>"
                                class="form-control"
                            >

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">

                                Confirm Password

                            </label>


                            <input
                                type="password"
                                name="password2"
                                class="form-control"
                            >

                        </div>


                        <div class="col-12">

                            <button
                                class="btn btn-primary"
                            >

                                Update Profile

                            </button>

                        </div>


                    </div>


                </form>


            </div>


        </section>



        <!-- ================================================= -->
        <!-- SETTINGS -->
        <!-- ================================================= -->

        <section
            id="settingsPage"
            class="page-section"
        >


            <div class="mb-4">

                <h1 class="page-title">

                    Settings ⚙️

                </h1>

                <p class="subtitle">

                    Manage your account preferences.

                </p>

            </div>



            <div class="card-box p-4">


                <div class="form-check form-switch mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        checked
                    >

                    <label class="form-check-label">

                        Email Notifications

                    </label>

                </div>


                <div class="form-check form-switch mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        checked
                    >

                    <label class="form-check-label">

                        Comment Notifications

                    </label>

                </div>


                <div class="form-check form-switch">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="darkMode"
                    >

                    <label class="form-check-label">

                        Dark Mode

                    </label>

                </div>


            </div>


        </section>


    </main>

</div>



<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>


<!-- jQuery -->

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
></script>



<script>


/* ==========================================
   PAGE SWITCHING
========================================== */


$("[data-page]").on("click", function(){

    let page = $(this).attr("data-page");


    if(!page){
        return;
    }


    $(".page-section")
        .removeClass("active-page");


    $("#" + page + "Page")
        .addClass("active-page");


    $(".nav-link")
        .removeClass("active");


    $('.nav-link[data-page="' + page + '"]')
        .addClass("active");

});



/* ==========================================
   CREATE POST
========================================== */


// $("#createPostForm").on("submit", function(e){

//     e.preventDefault();


//     let formData = new FormData(this);


//     /*
//         Later:

//         $.ajax({
//             url: "addpost.php",
//             method: "POST",
//             data: formData,
//             contentType: false,
//             processData: false,
//             success: function(data){
//                 ...
//             }
//         });
//     */


//     console.log("Post ready to send");

// });


</script>

<script src="../js/userajax.js"></script>
</body>

</html>