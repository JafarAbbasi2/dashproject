$(document).ready(function(){
// alert("hello");


function showpost(){

    $.ajax({
        url: "../user/showposts.php",
        method:"GET",
        dataType:"json",

        success: function(data){
            console.log(data);
let x ="";

            if(data){
                x = data ;
            }else{  x = [];}
            
let output = "";

            for(let i =0 ; i < x.length ; i++){
                
                
            output +=  `<div class="col-lg-6">


                    <div class="post-card">


                        <img
                            src="../uploads/${ x[i].thumbnail}"
                            class="post-img"
                        >


                        <div class="post-body">


                            <span class="badge bg-primary mb-2">

                                ${ x[i].category}

                            </span>


                            <h4>

                                ${ x[i].title}

                            </h4>


                            <p class="text-muted">

                                ${x[i].description}
                            </p>


                            <div class="d-flex gap-3 text-muted">


                                <span>

                                    <i class="bi bi-heart"></i>

                                    ${ x[i].time }

                                </span>


                                <span>

                                    <i class="bi bi-chat"></i>

                                    ${ x[i].id}

                                </span>


                            </div>


                        </div>

                    </div>


                </div>

                   `; 

            }

            $("#recentposts").html(output);
        }
    })
}

showpost();


$("#posts").on("click", function(){


    $.ajax({
        url: "../user/showallposts.php",
        method:"GET",
        dataType:"json",

        success: function(data){
            console.log(data);
let x ="";

            if(data){
                x = data ;
            }else{  x = [];}
            
let output = "";

            for(let i =0 ; i < x.length ; i++){
                
           output += `   <div class="col-lg-6">


                    <div class="post-card">


                        <img
                            src="../uploads/${x[i].thumbnail}"
                            class="post-img"
                        >


                        <div class="post-body">


                            <span class="badge bg-primary mb-2">

                                ${x[i].category}

                            </span>


                            <h4>

                                ${x[i].title}

                            </h4>


                            <p class="text-muted">

                            ${x[i].description}

                            </p>


                            <div class="d-flex justify-content-between">


                                <small class="text-muted">

                                ${x[i].time}

                                </small>


                                <div>


                                    <button
                                        class=" viewbtn btn btn-sm btn-outline-primary"
                                        data-id="${x[i].id}"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </button>


                                    <button
                                        class="editbtn btn btn-sm btn-outline-warning"
                                         data-id="${x[i].id}"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </button>


                                    <button
                                        class="delbtn btn btn-sm btn-outline-danger"
                                         data-id="${x[i].id}"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </button>


                                </div>


                            </div>


                        </div>


                    </div>


                </div>


`;

            }
            $("#showposts").html(output);
        }

})
})

$(document).on("click", ".delbtn", function(){

   let id = $(this).attr("data-id");

let mythis = this;

   $.ajax({
    url: "../admin/delete.php",
    method:"POST",
    data: JSON.stringify({sid: id}),

    success: function(data){
// console.log(data);

        if(data == 1){
            $(mythis).closest(".col-lg-6").fadeOut(400, function(){
                $(this).remove();
            });
        }else{
            alert("Error, couldn't del post");
        }

    }

   })
})



$(document).on("click", ".viewbtn", function(){

  let id = $(this).attr('data-id');

  window.location.href = "../admin/viewpost.php?id=" + id;


})

$(document).on("click", ".editbtn", function(){

  $(".page-section")
        .removeClass("active-page");


    $("#createPage")
        .addClass("active-page");


    $(".nav-link")
        .removeClass("active");


    $('.nav-link[data-page="create"]')
        .addClass("active");

        let id = $(this).attr("data-id");

$.ajax({
    url: "../user/postinfo.php",
    method: "POST",
    dataType:"json",
    data:JSON.stringify({id: id}),

    success: function(data){
        // console.log(data[0].title);
        $("#postTitle").val(data[0].title);
        $("#postId").val(data[0].id);
        $("#postCategory").val(data[0].category);
        $("#postDescription").val(data[0].description);
        
    }
})

})

$(".createPost").on("click", function(){
    
    $("#createPostForm")[0].reset();
   $("#postId").val("");
//    href.reload();
})

$("#createPostForm").on("submit", function(e){

e.preventDefault();

     let title =   $("#postTitle").val();
       let id =  $("#postId").val();
       let category = $("#postCategory").val();
       let description =  $("#postDescription").val();

       let img = $("#postThumbnail")[0].files[0];

       let data = new FormData();

       

    if(img && id && category){

        data.append("id",id);
        data.append("img",img);
        data.append("title",title);
        data.append("category",category);
        data.append("content",description);

    }else if(img && id){

        data.append("img",img);
        data.append("id",id);
        data.append("title",title);
        data.append("content",description);

    }else if(id && category){
        data.append("id",id);
        data.append("title",title);
        data.append("content",description);
        data.append("category",category);

    }else if(img && category){

        data.append("img",img);
        data.append("title",title);
        data.append("content",description);
        data.append("category",category);

    }else if (id){
        data.append("id",id);
        data.append("title",title);
        data.append("content",description);

    }else if (img){
        data.append("img",img);
        data.append("title",title);
        data.append("content",description);
        
    }else if (category){
        data.append("category",category);
        data.append("title",title);
        data.append("content",description);
        
    }else{
        data.append("title",title);
        data.append("content",description);

        }

    
    $.ajax({
        url: "../actions/inserpost.php",
        method: "POST",
        data: data,
        contentType: false,
        processData: false,

        success: function(result){
    console.log(result);
    $("#msg").html(result);

    $("#createPostForm")[0].reset();
    
        }
    })

})

$("#profileImage").on("change", function(){

    let file = this.files[0];

    if(file){
        let reader = new FileReader();

        reader.onload = function(e){
            $("#profilePic").attr("src", e.target.result);
        };

        reader.readAsDataURL(file);
    }

});


$("#profilePage").on("submit", function(e){

    e.preventDefault();

    let id = $("#userid").val();
    let user = $("#username").val();
    let email = $("#email").val();
    let pass = $("#password").val();
    let img = $("#profileImage")[0].files[0];

    let data = new FormData();

    if(id){
        data.append('id',id);
    }

    if(user){
    data.append("user",user);}

    if(email){
    data.append("email", email);}

    if(pass){
    data.append("pass",pass);}

    if(img){
        data.append("img",img);
    }


    $.ajax({
        url:"../actions/insert.php",
        method:"POST",
        data: data,
        contentType: false,
        processData: false,

        success: function(data){
            // console.log(data);

    location.reload();

    //           $(".page-section").removeClass("active-page");

    // $("#dashboardPage").addClass("active-page");

    // $(".nav-link").removeClass("active");

    // $('.nav-link[data-page="dashboard"]').addClass("active");

        }
    })

})











// SETTING PAGE 
$("#darkMode").on("change", function(){

    if(this.checked){

        $("body").addClass("dark-mode");
        localStorage.setItem("darkMode", "on");

    }else{

        $("body").removeClass("dark-mode");
        localStorage.setItem("darkMode", "off");

    }

});

if(localStorage.getItem("darkMode") === "on"){

    $("body").addClass("dark-mode");
    $("#darkMode").prop("checked", true);

}


    //   ------------------ CONTRACT PAGE ---------------------

    let allContracts = [];
    function showcontracts() {


    $.ajax({
        url: "../contract/getcontracts.php",
        type: "GET",
        dataType: "json",

        success: function(response) {


    //           console.log("FULL RESPONSE:", response);
    // console.log("TYPE:", typeof response);
    // console.log("DATA:", response.data);


            // console.log(response);


            $("#contractsLoading").addClass("d-none");
                        
            $("#refreshContractsBtn")
                .prop("disabled", false)
                .html(originalRefreshButtonHtml);

            $("#contractsContainer").find(".contract-card").remove();
                        
             if (
                !response ||
                response.status !== true ||
                !Array.isArray(response.contract)
            ) {
                console.error("Unexpected contracts response:", response);
                $("#contractsEmpty").addClass("d-none");
                return;
            }

                allContracts = response.contract;

            if (response.contract.length === 0) {
                $("#contractsEmpty").removeClass("d-none");
                updateContractStats([]);
                return;
            }

            $("#contractsEmpty").addClass("d-none");

            let html = "";

            response.contract.forEach(function (contract){

                html +=   `  <div class="col-md-6 contract-card" data-contract-id="${contract.id}">

                    <div class="card-box p-4 h-100">

                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>

                                <span class="badge bg-primary-subtle text-primary">
                                    Contract #${contract.id}
                                </span>

                                <h5 class="fw-bold mt-2 mb-1">
                                    ${contract.title}
                                </h5>

                            </div>

                            <span class="badge bg-warning text-dark">
                                ${contract.status}
                            </span>

                        </div>


                        <p class="text-muted mb-3">
                            ${contract.description ?? "No description available."}
                        </p>


                        <div class="small text-muted mb-3">

                            <div class="mb-1">
                                <i class="bi bi-person me-1"></i>
                                Manager:
                                <strong>
                                    ${contract.manager_name ?? "-"}
                                </strong>
                            </div>

                            <div>
                                <i class="bi bi-calendar-event me-1"></i>
                                Deadline:
                                <strong>
                                    ${contract.deadline ?? "-"}
                                </strong>
                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-center">

                            <small class="text-muted">

                                <i class="bi bi-clock me-1"></i>
                                ${contract.time ?? ""}

                            </small>


                            <button
                                type="button"
                                class="btn btn-sm btn-primary view-contract-btn"
                                data-id="${contract.id}"
                            >

                                <i class="bi bi-eye me-1"></i>
                                View Contract

                            </button>

                        </div>

                    </div>

                </div>

                `;
                
            });
$("#contractsContainer").prepend(html);

updateContractStats(allContracts);
applyContractFilters();
 },

        error: function(xhr) {

            console.log("GET CONTRACT ERROR:");
            console.log(xhr.responseText);

            $("#contractsLoading").addClass("d-none");

$("#refreshContractsBtn")
    .prop("disabled", false)
    .html(originalRefreshButtonHtml);

            $("#contractsContainer").html(`

                <div class="col-12">

                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Unable to load your contracts.

                    </div>

                </div>

            `);

        }
    });

}

showcontracts();

// ------------------------refresh-button-------------------------

const originalRefreshButtonHtml = $("#refreshContractsBtn").html();

$("#refreshContractsBtn").on("click", function () {

    const button = $(this);

    if (button.prop("disabled")) {
        return;
    }

    // Reset all filters and search.
    $("#contractSearch").val("");
    $("#contractStatusFilter").val("all");
    $("#contractRoleFilter").val("all");

    // Show the loading state.
    button.prop("disabled", true).html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Refreshing...
    `);

    $("#contractsLoading").removeClass("d-none");

    // Fetch the latest contracts from PHP.
    showcontracts();
});



// updateContractStats(response.contract);
/* ==========================================
   UPDATE CONTRACT STATS
========================================== */

function updateContractStats(contracts){

    let total = contracts.length;

    let pending = 0;
    let active = 0;
    let completed = 0;


    contracts.forEach(function(contract){

        let status = contract.status.toLowerCase();


        if(status === "pending"){

            pending++;

        }


        if(status === "ongoing" || status === "accepted"){

            active++;

        }


        if(status === "completed"){

            completed++;

        }

    });


    $("#totalContracts").text(total);

    $("#pendingContracts").text(pending);

    $("#activeContracts").text(active);

    $("#completedContracts").text(completed);

}


function applyContractFilters() {

    const searchText = $("#contractSearch").val()
        .trim()
        .toLowerCase();

    const selectedStatus = $("#contractStatusFilter").val();
    const selectedRole = $("#contractRoleFilter").val();

    const filteredContracts = allContracts.filter(function(contract) {

        // Search across contract details and assigned people.
        const searchableText = [
            contract.id,
            contract.title,
            contract.description,
            contract.worker_name,
            contract.manager_name
        ].join(" ").toLowerCase();

        const matchesSearch = searchableText.includes(searchText);

        // Match the selected status to the database status.
        let status = String(contract.status).toLowerCase();

        if (status === "ongoing") {
            status = "in_progress";
        }

        const matchesStatus =
            selectedStatus === "all" ||
            status === selectedStatus;

        // The PHP endpoint already returns user_role.
        const matchesRole =
            selectedRole === "all" ||
            contract.user_role === selectedRole;

        return matchesSearch && matchesStatus && matchesRole;
    });

    const matchingIds = filteredContracts.map(function(contract) {
        return String(contract.id);
    });

    $("#contractsContainer .contract-card").each(function() {

        const contractId = String($(this).attr("data-contract-id"));

        $(this).toggle(matchingIds.includes(contractId));
    });

    // Show the empty state when no contracts match.
    if (filteredContracts.length === 0) {
        $("#contractsEmpty").removeClass("d-none");
    } else {
        $("#contractsEmpty").addClass("d-none");
    }
}


$("#contractSearch, #contractStatusFilter, #contractRoleFilter")
    .on("input change", applyContractFilters);

$("#clearContractFilters").on("click", function() {
    $("#contractSearch").val("");
    $("#contractStatusFilter").val("all");
    $("#contractRoleFilter").val("all");

    applyContractFilters();
});

                //    VIEW CONTRACT -----------------

$(document).on("click",".view-contract-btn", function(){

    let id = $(this).attr('data-id');

    $.ajax({
        url:"../contract/viewContract.php",
        data:JSON.stringify({id: id}),
        method:"POST",
        dataType:"json",

        success: function(data){

// Load the activity for the contract being viewed.
loadRecentActivity(data.id);
loadContractSubmissions(data.id, data);

            $("#userContractModal").modal("show");
            console.log(data);
    
            if(data['managerID'] == data['current-user-id']){
                
                $("#viewContractRole").html("ROLE: Manager");
                $("#viewContractWorker").html( data.worker_name);
                $("#viewContractManager").html( "YOU");
                

            }else if (data['userID'] == data['current-user-id']){
           
                $("#viewContractRole").html("ROLE: Worker");
                $("#viewContractWorker").html("YOU");
                $("#viewContractManager").html( data.manager_name);
                
            }
            
            
                $("#viewContractStatus").html("STATUS: " + data.status);
                $("#viewContractTitle").html("Title: " + data.title);
                $("#viewContractId").html(data.id);
                $("#viewContractDescription").html( data.description);
                $("#viewContractDeadline").html( data.deadline);


                
          // Determine what the progress bar should display
let status = String(data.status).toLowerCase();

let progress = Number(data.progress) || 0;

if (status === "pending") {
    progress = 0;
} else if (status === "under_review" ) {
    progress = 90;
} else if (status === "ongoing") {
    progress =  40 + (progress * 0.6); // Scale ongoing progress to 30-90%  
} else if (status === "completed") {
    progress = 100;
}

// Update the existing progress bar
$("#viewContractProgressText").text(progress + "%");

$("#viewContractProgressBar")
    .css("width", progress + "%")
    .attr("aria-valuenow", progress);




$("#contractActions").empty();


let isWorker =
    String(data["userID"]) === String(data["current-user-id"]);



// Show Start Work only to the assigned worker on pending contracts.
if (isWorker && status === "pending") {
    $("#contractActions").append(`
        <button
            type="button"
            class="btn btn-success"
            id="startWorkBtn"
            data-id="${data.id}"
        >
            <i class="bi bi-play-circle me-1"></i>
            Start Work
        </button>
    `);
}

// Show Submit Work only after work has started.
if (isWorker && status === "ongoing") {
    $("#contractActions").append(`
        <button
            type="button"
            class="btn btn-primary"
            id="openSubmitWorkBtn"
            data-id="${data.id}"
        >
            <i class="bi bi-cloud-arrow-up me-1"></i>
            Submit Work
        </button>
    `);
}

}
        }
    )  
  })




$(document).on("click", "#startWorkBtn", function () {

    let contractId = $(this).attr("data-id");
    let button = $(this);

    // Prevent repeated clicks while the request is running.
    button.prop("disabled", true).text("Starting...");

    $.ajax({
        url: "../contract/startwork.php",
        method: "POST",
        data: JSON.stringify({
            id: contractId
        }),
        contentType: "application/json",
        dataType: "json",

        success: function (response) {

            if (response.success) {
                // alert(response.message);

                $("#userContractModal").modal("hide");

                showcontracts();
            } else {
                alert(response.message);
                button.prop("disabled", false).text("Start Work");
            }
        },

        error: function (xhr) {

            console.log("START WORK ERROR:", xhr.responseText);

            alert("Unable to start work. Please try again.");

            button.prop("disabled", false).text("Start Work");
        }
    });

});


$(document).on("click", "#openSubmitWorkBtn", function () {

    let contractId = $(this).attr("data-id");

    $("#submitWorkForm")[0].reset();

    $("#submitContractId").val(contractId);

    $("#submitWorkMsg").empty();

    $("#userContractModal").one("hidden.bs.modal", function () {

        $("#submitWorkModal").modal("show");

    });

    $("#userContractModal").modal("hide");

});


$(document).on("submit", "#submitWorkForm", function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const button = $("#submitWorkBtn");
    const message = $("#submitWorkMsg");

    button.prop("disabled", true).text("Submitting...");
    message.empty();

    $.ajax({
        url: "../contract/submitwork.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",

        success: function (response) {
            if (response.success) {
                message.html(
                    `<div class="alert alert-success">${response.message}</div>`
                );

                form.reset();

                setTimeout(function () {
                    $("#submitWorkModal").modal("hide");
                    showcontracts();
                    button.prop("disabled", false)
                          .html('<i class="bi bi-send me-1"></i>Submit Work');
                    message.empty();
                }, 1000);

            } else {
                message.html(
                    `<div class="alert alert-danger">${response.message}</div>`
                );

                button.prop("disabled", false)
                      .html('<i class="bi bi-send me-1"></i>Submit Work');
            }
        },

        error: function (xhr) {
            console.log("SUBMIT WORK ERROR:", xhr.responseText);

            message.html(
                '<div class="alert alert-danger">A server error occurred. Check the console and PHP error log.</div>'
            );

            button.prop("disabled", false)
                  .html('<i class="bi bi-send me-1"></i>Submit Work');
        }
    });
});



function renderActivity(activity) {
    const labels = {
        work_started: "Work Started",
        work_submitted: "Work Submitted",
        contract_assigned: "Contract Assigned",
        revision_requested: "Revision Requested",
        work_approved: "Work Approved",
        contract_completed: "Contract Completed"
    };

    const label = labels[activity.action] || activity.action;

    const date = new Date(
        activity.created_at.replace(" ", "T")
    );

    const formattedDate = Number.isNaN(date.getTime())
        ? activity.created_at
        : date.toLocaleString();

    return `
        <div class="d-flex gap-3 mb-3">
            <div class="rounded-circle bg-primary-subtle text-primary
                        d-flex align-items-center justify-content-center
                        flex-shrink-0"
                 style="width: 40px; height: 40px;">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="flex-grow-1 border-bottom pb-3">
                <div class="fw-semibold">${escapeActivityText(label)}</div>
                <div class="text-muted small mt-1">
                    ${escapeActivityText(activity.details || "")}
                </div>
                <div class="text-muted small mt-2">
                    ${escapeActivityText(formattedDate)}
                </div>
            </div>
        </div>
    `;
}

function escapeActivityText(value) {
    return String(value).replace(/[&<>"']/g, function (char) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;"
        }[char];
    });
}

function loadContractSubmissions(contractId, contractData) {

    $("#contractSubmissionsList").html(`
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm"></div>
            <p class="text-muted mb-0 mt-2">Loading submissions...</p>
        </div>
    `);

    $.ajax({
        url: "../contract/getsubmission.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            id: contractId
        }),

        dataType: "json",

        success: function(response) {

            if (!response.status) {
                $("#contractSubmissionsList").html(`
                    <div class="alert alert-warning mb-0">
                        Could not load submissions.
                    </div>
                `);
                return;
            }

            const submissions = response.submissions;

            $("#submissionCount").text(submissions.length);

            if (submissions.length === 0) {
                $("#contractSubmissionsList").html(`
                    <div class="text-muted text-center py-3">
                        No work has been submitted yet.
                    </div>
                `);
                return;
            }

                     let html = "";

            const isManager =
                Number(contractData["current-user-id"]) ===
                Number(contractData.managerID);

            const canReview =
                isManager && contractData.status === "under_review";

            submissions.forEach(function(submission) {

                let reviewButtons = "";

                if (canReview) {
                    reviewButtons = `
                        <div class="d-flex flex-wrap gap-2 mt-3">

                            <button
                                type="button"
                                class="btn btn-success btn-sm review-submission-btn"
                                data-contract-id="${contractId}"
                                data-submission-id="${submission.id}"
                                data-decision="approve">
                                <i class="bi bi-check-circle me-1"></i>
                                Approve
                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-warning btn-sm review-submission-btn"
                                data-contract-id="${contractId}"
                                data-submission-id="${submission.id}"
                                data-decision="revision">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Request Revision
                            </button>

                        </div>
                    `;
                }

                html += `
                    <div class="contract-detail-box mb-3">

                        <h6 class="fw-bold">
                            ${escapeActivityText(submission.submission_title)}
                        </h6>

                        <p class="mb-2">
                            ${escapeActivityText(submission.submission_description)}
                        </p>

                       
${
    submission.work_file
        ? `<div class="mt-3">
               <i class="bi bi-paperclip me-1"></i>
               <a
                   href="../${escapeActivityText(submission.work_file)}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-outline-primary btn-sm">
                   <i class="bi bi-eye me-1"></i>
                   Open Submitted File
               </a>
           </div>`
        : `<small class="text-muted">No file attached.</small>`
}

                        ${reviewButtons}

                    </div>
                `;
            });


            

            $("#contractSubmissionsList").html(html);
        },

        error: function(xhr) {

            console.error("Submission loading failed:", xhr.responseText);

            $("#contractSubmissionsList").html(`
                <div class="alert alert-danger mb-0">
                    An error occurred while loading submissions.
                </div>
            `);
        }
    });
}

$(document).on("click", ".review-submission-btn", function() {

    const button = $(this);
    const contractId = Number(button.data("contract-id"));
    const submissionId = Number(button.data("submission-id"));
    const decision = button.data("decision");

    let feedback = "";

    if (decision === "revision") {
        feedback = prompt(
            "Explain what the worker needs to revise:"
        );

        if (feedback === null) {
            return;
        }

        feedback = feedback.trim();

        if (feedback === "") {
            alert("Please provide revision feedback.");
            return;
        }
    }

    const confirmation = decision === "approve"
        ? "Approve this submission?"
        : "Request revisions from the worker?";

    if (!confirm(confirmation)) {
        return;
    }

    button.prop("disabled", true);

    $.ajax({
        url: "../contract/reviewsubmission.php",
        type: "POST",
        contentType: "application/json",
        dataType: "json",

        data: JSON.stringify({
            contract_id: contractId,
            submission_id: submissionId,
            decision: decision,
            feedback: feedback
        }),

        success: function(response) {

            if (!response.status) {
                alert(response.message || "Review failed.");
                button.prop("disabled", false);
                return;
            }

            alert(response.message);

            // Refresh contract status and role-based actions.
            location.reload();
        },

        error: function(xhr) {
            console.error(
                "Review failed:",
                xhr.status,
                xhr.responseText
            );

            let message = "Could not process the review.";

            try {
                const response = JSON.parse(xhr.responseText);

                if (response.message) {
                    message = response.message;
                }
            } catch (error) {
                // Keep the default message if the response isn't JSON.
            }

            alert(message);
            button.prop("disabled", false);
        }
    });
});

function loadRecentActivity(contractId) {
    const preview = $("#contractActivityPreview");

    preview.html(
        '<div class="text-muted small">Loading recent activity...</div>'
    );

    $.ajax({
        url: "../contract/getactivity.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ id: contractId }),
        dataType: "json",

        success: function (response) {
            if (!response.success) {
                preview.html(
                    '<div class="text-danger small">Could not load activity.</div>'
                );
                return;
            }

            if (response.activities.length === 0) {
                preview.html(
                    '<div class="text-muted small">No activity recorded yet.</div>'
                );
                return;
            }

            let html = "";

            response.activities.forEach(function (activity) {
                html += renderActivity(activity);
            });

            preview.html(html);
        },

        error: function (xhr) {
            console.log("RECENT ACTIVITY ERROR:", xhr.responseText);

            preview.html(
                '<div class="text-danger small">Failed to load activity.</div>'
            );
        }
    });
}

$(document).on("click", "#viewAllActivityBtn", function () {
    const contractId = $("#viewContractId").text().trim();

    if (!contractId) {
        return;
    }

    $("#allActivityContractId").val(contractId);
    $("#allActivityList").empty();
    $("#allActivityEmpty").addClass("d-none");
    $("#allActivityError").addClass("d-none");
    $("#allActivityLoading").removeClass("d-none");

    // Hide the details modal before opening the history modal.
    $("#userContractModal").one("hidden.bs.modal", function () {
        $("#allActivityModal").modal("show");
    });

    $("#userContractModal").modal("hide");

    $.ajax({
        url: "../contract/getallactivity.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ id: contractId }),
        dataType: "json",

        success: function (response) {
            $("#allActivityLoading").addClass("d-none");

            if (!response.success) {
                $("#allActivityError")
                    .text(response.message || "Could not load activity.")
                    .removeClass("d-none");
                return;
            }

            if (response.activities.length === 0) {
                $("#allActivityEmpty").removeClass("d-none");
                return;
            }

            let html = "";

            response.activities.forEach(function (activity) {
                html += renderActivity(activity);
            });

            $("#allActivityList").html(html);
        },

        error: function (xhr) {
            console.log("ALL ACTIVITY ERROR:", xhr.responseText);

            $("#allActivityLoading").addClass("d-none");
            $("#allActivityError")
                .text("Failed to load activity. Please try again.")
                .removeClass("d-none");
        }
    });
});

})