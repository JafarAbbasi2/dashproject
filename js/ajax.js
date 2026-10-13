$(document).ready(function () {
    
$("#logoutBtn").on("click", function(){

    location.href = "logout.php";
})
    
$("#logoutDropdown").on("click", function(){

    location.href = "logout.php";
})




// PROFILE update section

$("#profileUpload").on("change", function(){

let img = $("#profileUpload")[0].files[0];


let formdata = new FormData();

if(img){
console.log("img uploaded");

formdata.append("pfp",img);

}else{ console.log("img isn't uploaded");
}

$.ajax({
    url: "../admin/profile.php",
    method: "POST",
    data: formdata,
    contentType: false,
    processData: false,

    success: function(data){
        console.log(data);

        setTimeout(() => {
            location.reload();
        }, 500);

        // $("#profileImg").attr("src", URL.createObjectURL(img));
        
    }
})
})

$("#editProfile").on("click", function(){

    $("#name").prop("disabled", false);
    $("#email").prop("disabled", false);
    $("#password").prop("disabled", false);
    $("#confirmPassword").prop("disabled", false);

    $("#formActions").css("display", "flex");

})

$("#cancelBtn").on("click", function(){

    $("#name").prop("disabled", true);
    $("#email").prop("disabled", true);
    $("#password").prop("disabled", true);
    $("#chnagePassword").prop("disabled", true);

    $("#formActions").css("display", "none");
})

$("#profileForm").on("submit", function(e){

    e.preventDefault();

    let name = $("#name").val();
    let email = $("#email").val();
    let password = $("#password").val();
    let confrimPassword = $("#confirmPassowrd").val();

    let data = {
        name: name,
        email: email,
        password: password
    };



        $.ajax({
            url: "../admin/profile.php",
            method: "POST",
            data: JSON.stringify(data),

            success: function(data){
                console.log(data);
                
                $("#msg").html(data);

                $("#name").prop("disabled", true);
                $("#email").prop("disabled", true);
                $("#password").prop("disabled", true);
                $("#confirmPassword").prop("disabled", true);

                $("#formAction").css("display", "none");

                setTimeout(() => {
                        location.reload();
                            
                }, 1000);            }
        })

    
})




// DASHBOARD SETTINGSS recentPostsTable


function showPosts(data){

        let x;
        let html ="";

        if(data){

            x = data;

        }else{
            x = [];
        }

        for(let i= 0 ; i < x.length; i++){

               html += `
                <tr>

                    <td>${x[i].id}</td>

                    <td>
                        User #${x[i].userid}
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-2">

                            <img
                                src="uploads/${x[i].thumbnail}"
                                width="40"
                                height="40"
                                class="rounded object-fit-cover"
                            >

                            <div>
                                <strong>${x[i].title}</strong>

                                <small class="d-block text-muted">
                                    ${x[i].description.substring(0, 40)}...
                                </small>
                            </div>

                        </div>
                    </td>

                    <td>${x[i].time}</td>

                    <td>
                        <span class="badge bg-success">
                            Published
                        </span>
                    </td>

                    <td>
                        <button
                            class="btn btn-sm btn-outline-primary viewbtn"
                            data-id="${x[i].id}"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                        <button
                            class="btn btn-sm btn-outline-danger delbtn"
                            data-id="${x[i].id}"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>

                </tr>
            `;
        }
        $("#recentPostsTable").html(html);
    };




$.ajax({
    url: "actions/retrieve.php",
    method:"GET",
    dataType:"json",

    success: function(data){ 
    showPosts(data);
}
    
})

$("#viewall").on("click", function(){

let btn = $(this);

    if(btn.text() === "View All"){

$.ajax({
    url: "admin/viewall.php",
    method:"GET",
    dataType:"json",

    success: function(data){
showPosts(data);

$("#viewall").text("View Less");

}
})
    }else{
         
$.ajax({
    url: "actions/retrieve.php",
    method:"GET",
    dataType:"json",

    success: function(data){
showPosts(data);

$("#viewall").text("View All");

}
})
    }

})


//del btnnn


$(document).on("click", ".delbtn", function(){

    let id = $(this).attr('data-id');

    data = {sid: id};
    
    let mythis = this;

    // console.log(id);
    
    $.ajax({
        url: "admin/delete.php",
        method: "POST",
        data: JSON.stringify(data),

        success: function(data){
            console.log(data);
            
            if(data == 1){
                $(mythis).closest('tr').fadeOut();
            }
        }
    })

})


$(document).on("click", ".viewbtn", function(){

    let id = $(this).attr('data-id');

    window.location.href = "admin/viewpost.php?id=" + id;
})


$(".nav-link").on("click", function(){

    let page = $(this).attr("data-page");

    $("main section").addClass("d-none");

    $("#" + page + "Page").removeClass("d-none");

    $(".nav-link").removeClass("active");
    $(this).addClass("active");

})
$("#settingbtn").on("click", function(){

 $("main section").addClass("d-none");
   $("#settingsPage").removeClass("d-none");

   $(".nav-link").removeClass("active");
   $(".nav-setting").addClass("active");


})

function loadusers(){
    // console.log('wrkin');
    
    $.ajax({
        url: "admin/viewusers.php",
        method: "GET",
        dataType: "json",

        success: function(data){
            
            if(data){
                x = data;
            }else{ x = [];}

            let output ="";

            for(let i=0; i< x.length; i++){
output +=  `
    <tr>

        <td>${x[i].id}</td>

        <td>
            <div class="d-flex align-items-center gap-2">
                <img 
                    src="uploads/${x[i].pfp}"
                    width="40"
                    height="40"
                    class="rounded-circle object-fit-cover"
                >
                <strong>${x[i].name}</strong>
            </div>
        </td>

        <td>${x[i].email}</td>

        <td>
            <span class="badge bg-secondary">
                ${x[i].role}
            </span>
        </td>

        <td>
        <span class="badge bg-success">
        Active
        </span>
        </td>

        <td>
            <button 
                class="editUser btn btn-sm btn-outline-warning"
                data-id="${x[i].id}"
            >
                <i class="bi bi-pencil-square"></i>
                </button>
                <button 
        class="delUser btn btn-sm btn-outline-danger"
        data-id="${x[i].id}"
    >
        <i class="bi bi-trash"></i>
    </button>
                </td>
                
                </tr>
                `;
            }
            $("#usersTable").html(output);
        }
        
    })
}

$("#userbtn").on("click", loadusers());

$(document).on("click",".editUser", function(){

    let id = $(this).attr('data-id');

    // console.log(id);
    
    let data = {id: id};

    $.ajax({
        url: "admin/viewusers.php",
        method: "POST",
        dataType: "json",
        data: JSON.stringify(data), 

        success: function(data){
            let id = data[0].id;
            let user = data[0].name;
            // console.log(user);
            

            $("#addUserModal").modal("show");              

            $("#username").val(user);
            $("#email").val(data[0].email);
            $("#password").val(data[0].password);
            $("#userId").val(id);


        }
    })
               

})

$("#addUser").on("click", function(){
     $("#addUserForm")[0].reset();
     $("#userId").val("");
})


$("#addUserForm").on("submit", function(e){

e.preventDefault();

    let id = $("#userId").val();
    let user = $("#username").val();
    let email = $("#email").val();
    let pass = $("#password").val();

    let img = $("#profilePic")[0].files[0];
    
    let formdata = new FormData();

    if(img && id){

        formdata.append("id",id);
        formdata.append("img",img);
        formdata.append("user",user);
        formdata.append("email",email);
        formdata.append("pass",pass);

    }else if(img){

        formdata.append("img",img);
        formdata.append("user",user);
        formdata.append("email",email);
        formdata.append("pass",pass);

    }else if(id){
        formdata.append("id",id);
        formdata.append("user",user);
        formdata.append("email",email);
        formdata.append("pass",pass);

    }else{
        formdata.append("user",user);
        formdata.append("email",email);
        formdata.append("pass",pass);

        }

        $.ajax({
            url: "actions/insert.php",
            method: "POST",
            data: formdata,
            contentType: false,
            processData: false,

            success: function(data){
                console.log(data);

                $("#addUserModal").modal("hide");              
                
                // $("#addUserModal").fadeOut();              
                // $("#addUserModal").removeClass("show");              
                // $("#addUserModal").prop("aria-modal", false);              
                // $("#addUserModal").prop("aria-hidden", true);              
                // $("#addUserModal").prop("style", "display: none");              

                loadusers();
                $("#addUserForm")[0].reset();
            }
        })
    
})


$("#userSearch").on("input", searchdata);
$("#searchUsers").on("click", function(e){
    searchdata(e);
    $("#userSearch").val("");
    searchdata()
});




function searchdata(e){
    e.preventDefault();

  let data =  $("#userSearch").val();
let searchdata = {sd: data}

let output = '';

  $.ajax({
    url:"actions/search.php",
    method:"POST",
    dataType:"json",
    data:JSON.stringify(searchdata),
    success:function(data){
        // console.log(data);

        if(data.length > 0){
    
                x = data;        

    for (let  i= 0; i< x.length; i++) {
        
        
         output +=   `
    <tr>

        <td>${x[i].id}</td>

        <td>
            <div class="d-flex align-items-center gap-2">
                <img 
                    src="uploads/${x[i].pfp}"
                    width="40"
                    height="40"
                    class="rounded-circle object-fit-cover"
                >
                <strong>${x[i].name}</strong>
            </div>
        </td>

        <td>${x[i].email}</td>

        <td>
            <span class="badge bg-secondary">
                ${x[i].role}
            </span>
        </td>

        <td>
        <span class="badge bg-success">
        Active
        </span>
        </td>

        <td>
            <button 
                class="editUser btn btn-sm btn-outline-warning"
                data-id="${x[i].id}"
            >
                <i class="bi bi-pencil-square"></i>
                </button>
                <button 
        class="delUser btn btn-sm btn-outline-danger"
        data-id="${x[i].id}"
    >
        <i class="bi bi-trash"></i>
    </button>
                </td>
                
                </tr>
                `;
          }
    $("#usersTable").html(output);
}else{
        // no match
        $("#usersTable").html(
            "<tr><td colspan='5'>No student found</td></tr>");
}
        
    }
  })

}


$(document).on("click", ".delUser", function(){

    let id = $(this).attr("data-id");
let mythis = this;

   $.ajax({
    url: "admin/deleteuser.php",
    method: "POST",
    data: JSON.stringify({sid: id}),

    success: function(data){
        console.log(data);
        if(data == 1){
    $(mythis).closest('tr').fadeOut();
        }else{
            console.log("cant delete error");
            
        }


    }
   })
});



$("#postsbtn").on("click", loadposts());


function loadposts(){
    // console.log('wrkin');
    
    $.ajax({
        url: "admin/viewall.php",
        method: "GET",
        dataType: "json",

       success: function(data){ 

    if(data){ 
        x = data; 
    }else{ 
        x = [];
    } 

    let output = ""; 

    for(let i = 0; i < x.length; i++){ 

        output += `
            <tr>

                <!-- ID -->
                <td>${x[i].id}</td>

                <!-- Author -->
                <td>
                    User #${x[i].userid}
                </td>

                <!-- Title -->
                <td>
                    <div class="d-flex align-items-center gap-2">

                        <img 
                            src="uploads/${x[i].thumbnail}"
                            width="40"
                            height="40"
                            class="rounded object-fit-cover"
                        >

                        <div>
                            <strong>${x[i].title}</strong>

                            <small class="d-block text-muted">
                                ${x[i].description.substring(0, 40)}...
                            </small>
                        </div>

                    </div>
                </td>

                <!-- Category -->
                <td>
                    ${x[i].category}
                </td>

                <!-- Date -->
                <td>
                    ${x[i].time}
                </td>

                <!-- Status -->
                <td>
                    <span class="badge bg-success">
                        Published
                    </span>
                </td>

                <!-- Actions -->
                <td>

                    <button
                        class="viewbtn btn btn-sm btn-outline-primary"
                        data-id="${x[i].id}"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                    <button
                        class="editPost btn btn-sm btn-outline-warning"
                        data-id="${x[i].id}"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        class="delbtn btn btn-sm btn-outline-danger"
                        data-id="${x[i].id}"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>
        `; 
    }

    $("#postsTable").html(output); 
}

    })
}
    



$(document).on("click",".editPost", function(){

    let id = $(this).attr('data-id');

    // console.log(id);
    
    let data = {id: id};

    $.ajax({
        url: "admin/viewall.php",
        method: "POST",
        dataType: "json",
        data: JSON.stringify(data), 

success: function(data){

    let id = data[0].id;
    let title = data[0].title;
    let category = data[0].category;
    let content = data[0].description;
    let thumbnail = data[0].thumbnail;

    $("#addPostModal").modal("show");

    $("#postTitle").val(title);
    $("#postCategory").val(category);
    $("#postContent").val(content);

    $("#postId").val(id);

    $("#oldThumbnail").attr(
        "src",
        "uploads/" + thumbnail
    );

}    })
               

})

$("#addPost").on("click", function(){
     $("#addPostForm")[0].reset();
     $("#postId").val("");
})


$("#addPostForm").on("submit", function(e){

    e.preventDefault();

    let id = $("#postId").val();
    let title = $("#postTitle").val();
    let category = $("#postCategory").val();
    let content = $("#postContent").val();

    let img = $("#postImage")[0].files[0];

    let formdata = new FormData();

    if(img && id){

        formdata.append("id", id);
        formdata.append("img", img);
        formdata.append("title", title);
        formdata.append("category", category);
        formdata.append("content", content);

    }else if(img){

        formdata.append("img", img);
        formdata.append("title", title);
        formdata.append("category", category);
        formdata.append("content", content);

    }else if(id){

        formdata.append("id", id);
        formdata.append("title", title);
        formdata.append("category", category);
        formdata.append("content", content);

    }else{

        formdata.append("title", title);
        formdata.append("category", category);
        formdata.append("content", content);
    }


    $.ajax({

        url: "actions/inserpost.php",

        method: "POST",

        data: formdata,

        contentType: false,

        processData: false,

        success: function(data){

            console.log(data);

            $("#addPostModal").modal("hide");

            loadposts();

            $("#addPostForm")[0].reset();

            $("#postId").val("");

        }

    });

});


$("#createContractBtn").on("click", function(){

    $("#contractModal").modal("show");
    $("#contractForm")[0].reset();
    $("#contractID").val("");

    
});


$.ajax({
    url: "contract/fetchuser.php",
    method: "GET",
    dataType: "json",

    success: function (data) {
        // console.log(data);

        let output = "";

        if(data){ x = data; 

        }else { x = ""; }

        for (let i = 0; i< x.length; i++) {
            
            output += ` <option value="${x[i].id}">
                                ${x[i].name}
                            </option>
`
        }



        $(".users").html(output);
    }
})

let contractsubmitting = false;

$("#contractForm").on("submit", function(e){

    e.preventDefault();

if(contractsubmitting){
return;
}
    
    contractsubmitting = true;
    $("#saveContract").prop("disabled", true);
    
    // let id = $("#contractID").val();
    // let title = $("#title").val();
    // let description = $("#description").val();
    // let description = $("#description").val();
    // let description = $("#description").val();

    $.ajax({
        url: "contract/insertcontract.php",
        method:"POST",
        data: $(this).serialize(),
        success: function(data){
            // console.log(data);

            
    $("#contractModal").modal("hide");
    $("#contractForm")[0].reset();
    $("#contractID").val("");
showContracts();
            
        },
    error: function(xhr){

            console.log(xhr.responseText);

            alert("Something went wrong while saving the contract.");
        },

        complete: function(){

            // Request is completely finished
            contractsubmitting = false;

            $("#saveCaontract").prop("disabled", false);
        }
       })
})

function showContracts(){

    $.ajax({

        url: "contract/showcontracts.php",
        method: "GET",
        dataType: "json",

        success: function(data){

        let output = "";
                
        // console.log("Contracts received:", data);
        //  console.log("Total received:", data.length);
        for(let i = 0; i < data.length; i++){

    output += `

                <div class="contract-item contract-card"
                     data-status="${data[i].status}">

                    <div class="row align-items-center g-3">

                        <!-- Title -->
                        <div class="col-lg-4">

                            <div class="d-flex align-items-center gap-3">

                                <div class="contract-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        ${data[i].title}
                                    </h6>

                                    <small class="text-muted">
                                        Contract #${data[i].id}
                                    </small>

                                </div>

                            </div>

                        </div>


                        <!-- Worker -->
                        <div class="col-lg-2">

                            <small class="text-muted d-block">
                                Worker
                            </small>

                            <strong>
                                ${data[i].worker_name}
                            </strong>

                        </div>


                        <!-- Manager -->
                        <div class="col-lg-2">

                            <small class="text-muted d-block">
                                Manager
                            </small>

                            <strong>
                                ${data[i].manager_name}
                            </strong>

                        </div>


                        <!-- Deadline -->
                        <div class="col-lg-2">

                            <small class="text-muted d-block">
                                Deadline
                            </small>

                            <strong>
                                ${data[i].deadline}
                            </strong>

                        </div>


                        <!-- Status + View -->
                        <div class="col-lg-2 text-lg-end">

                            <span class="badge bg-info-subtle text-info mb-2">
                                ${data[i].status}
                            </span>

                            <br>

                            <button
                                class="viewContractBtn btn btn-sm btn-outline-primary"
                                data-id="${data[i].id}">

                                View

                            </button>

                        </div>

                    </div>

                </div>

                `;

        }
            $("#contractList").html(output);
            updateContractStats();
        }
        
    });

}
showContracts();


function updateContractStats(){

    let total = $(".contract-card").length;

    let pending = $('.contract-card[data-status="pending"]').length;

    let ongoing = $('.contract-card[data-status="ongoing"]').length;

    let completed = $('.contract-card[data-status="completed"]').length;

    // Total
    $(".contract-filter[data-status='all'] h3").text(total);

    // Pending
    $(".contract-filter[data-status='pending'] h3").text(pending);

    // Ongoing
    $(".contract-filter[data-status='ongoing'] h3").text(ongoing);

    // Completed
    $(".contract-filter[data-status='completed'] h3").text(completed);

}

$(document).on("click", ".contract-filter", function(){

    let status = $(this).data("status");

    $(".contract-filter").removeClass("active");

    $(this).addClass("active");

    if(status == "all"){

        $(".contract-card").show();

        $("#contractListTitle").text("All Contracts");

    }else{

        $(".contract-card").each(function(){

            if($(this).data("status") == status){

                $(this).show();

            }else{

                $(this).hide();

            }

        });

        $("#contractListTitle").text(
            status.charAt(0).toUpperCase() + status.slice(1) + " Contracts"
        );

    }

});


$("#contractSearch").on("input", function(){

    let search = $(this).val().toLowerCase();

    $(".contract-card").each(function(){

        let text = $(this).text().toLowerCase();

        if(text.includes(search)){

            $(this).show();

        }else{

            $(this).hide();

        }

    });

})        

        // vIEW BTN CONTRACT PAGE 


        $(document).on("click", ".viewContractBtn", function(){

            let id = $(this).attr("data-id");

            $.ajax({
                url:"contract/viewContract.php",
                method:"POST",
                dataType: "json",
                data: JSON.stringify({id: id}),

                success: function(data){
                    console.log(data);

                    $("#viewContractTitle").text(data.title);
                    $("#viewContractNumber").text("Contract # " + data.id);
                    $("#viewContractStatus").text(data.status);
                    $("#viewContractDeadline").text(data.deadline);
                    $("#viewContractDescription").text(data.description);
                    $("#viewContractWorker").text(data.worker_name);
                    $("#viewContractManager").text(data.manager_name);
                    $("#viewContractId").text(data.id);
                    $("#viewContractCreated").text(data.time);

                    $("#viewContractModal").data(
                "contract-id",
                data.id
            );

                       $("#viewContractModal").modal("show");
                    
                }
            })
        })


        $(document).on("click", "#editContractBtn", function () {

    let id = $("#viewContractModal").data("contract-id");

    // console.log("Editing contract:", id);

    $.ajax({
        url: "contract/viewContract.php",
        method: "POST",
        dataType: "json",
        data: JSON.stringify({
            id: id
        }),

        success: function (response) {

            // console.log(response);
            
            let contract = response;

            // Fill existing contract form
            $("#contractID").val(contract.id);
            $("#title").val(contract.title);
            $("#description").val(contract.description);
            $("select[name='worker_id']").val(contract.userID);
            $("select[name='manager_id']").val(contract.managerID);
            $("input[name='deadline']").val(contract.deadline);
            $("select[name='status']").val(contract.status);

            // Change modal title
            $("#contractModal .modal-title").text("Edit Contract");

            // Change submit button text
            // $("#contractForm button[type='submit']").text("Update Contract");

            // Close view modal
            $("#viewContractModal").modal("hide");

            // Open existing create/edit modal
            $("#contractModal").modal("show");
        },

    });
});


$(document).on("click", "#deleteContractBtn", function () {

    let id = $("#viewContractModal").data("contract-id");

    if (!confirm("Are you sure you want to delete this contract?")) {
        return;
    }

    $.ajax({
        url: "contract/deletecontract.php",
        method: "POST",
        dataType: "json",

        data: JSON.stringify({
            id: id
        }),

        success: function (response) {

            if (response === 1) {

                $("#viewContractModal").modal("hide");

                showContracts();

            } else {

                alert("failed to delete");
            }
        },

        error: function (xhr) {

            console.log(xhr.responseText);

            alert("Unable to delete contract.");
        }
    });
});


})