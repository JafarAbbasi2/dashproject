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

            $("#contractsContainer").find(".contract-card").remove();
            
            if(response.length === 0){
                $("#contractsEmpty").removeClass("d-none");
                updateContractStats([]);
                return;
            }

            $("#contractsEmpty").addClass("d-none");

            let html = "";

            response.contract.forEach(function (contract){

                html +=   `  <div class="col-md-6 contract-card">

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

            updateContractStats(response.contract);
        },

        error: function(xhr) {

            console.log("GET CONTRACT ERROR:");
            console.log(xhr.responseText);

            $("#contractsLoading").addClass("d-none");

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

                //    VIEW CONTRACT -----------------

$(document).on("click",".view-contract-btn", function(){

    let id = $(this).attr('data-id');

    $.ajax({
        url:"../contract/viewContract.php",
        data:JSON.stringify({id: id}),
        method:"POST",
        dataType:"json",

        success: function(data){

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
                
            $("#viewContractProgressText").text(data.progress + "%");

            $("#viewContractProgressBar")
                .css("width", data.progress + "%")
                .attr("aria-valuenow", data.progress);


                $("#contractActions").empty();

if (
    String(data["userID"]) === String(data["current-user-id"]) &&
    ["accepted", "ongoing", "in_progress"].includes(
        String(data.status).toLowerCase()
    )
) {
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
    })

})



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
})