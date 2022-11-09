var selectedTaskGroupId = null;

// Side menubar controls
var isSideBarMenuOpen = false;


function sideBarMenuControl() {
    if (isSideBarMenuOpen) {
        $("#sidebar-menu").width(0);
        $("#mainBody").css("margin-left", "0");
        $("#mainBody").css("border-radius", "0px");
        $("#sideVarControlBtn").html("&#9776;");
        isSideBarMenuOpen = false;
    } else {
        $("#sidebar-menu").width(190);
        $("#mainBody").css("margin-left", "190px");
        $("#mainBody").css("border-radius", "30px 0px 0px 30px");
        $("#sideVarControlBtn").html("&#x2715");
        isSideBarMenuOpen = true;
    }
}
sideBarMenuControl();
// Side menubar controls end

// On Window resize event
$(window).resize(function () {
    if (window.innerWidth < 772) {
        isSideBarMenuOpen = true;
        sideBarMenuControl();
    }
});// On Window resize event end


// Click drag scrollable component script
const slider = document.querySelector('.h-scroll-wrap');
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('h-scroll-active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});
slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('h-scroll-active');
});
slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('h-scroll-active');
});
slider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.8; //scroll-fast
    slider.scrollLeft = scrollLeft - walk;
});
// Click drag scrollable component script end


// User removal ajax call
function userDeletion(userId) {
    var formData = new FormData();
    formData.append("userDeletionSubmit", true);
    formData.append("userId", userId);
    $.ajax({
        url: "./controls/userAction.php",
        type: "POST",
        dataType: "html",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            response = JSON.parse(response);
            console.log(response);
            if (response.status) {
                $(location).attr('href', './');
            } else {
                alert(response.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}
// User removal ajax call end


// Task group creation
$('#createTaskGroupForm').on('submit', function (e) {
    // To prevent form from submiting
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("createTaskGroupFormSubmit", true);

    $.ajax({
        url: "./controls/taskAction.php",
        type: "POST",
        dataType: "html",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.status) {
                $(location).attr('href', './');
            } else {
                alert(response.message);
            }
        },
        error: function (error) {
            alert("Error");
            alert(JSON.parse(response).message);
        },
    });

});
// Task group creation end

// Get task list by taskgroup id
function getTaskList(taskGroupId) {
    if (selectedTaskGroupId !== null) {
        $(`#${selectedTaskGroupId}`).removeClass('cu-active');
    }
    selectedTaskGroupId = taskGroupId
    $(`#${selectedTaskGroupId}`).addClass('cu-active');
    $("#taskListViewWrap").removeClass('d-none');
    $.ajax({
        url: "./controls/taskAction.php",
        type: "GET",
        data: {
            taskGroupId: taskGroupId
        },
        success: function (response) {
            response = JSON.parse(response)
            if (response.status) {
                $("#taskListViewBody").html(response.message)
                var completedPercentage = (response.done_count / (response.done_count + response.pending_count)) * 100
                $("#inner-circle").html(`${completedPercentage.toFixed()}%`)
                $("#graph-statistics").html(`<div class="col">${response.done_count} Done</div>
                    <div class="col">${response.pending_count} Pending</div>`)
                $("#outer-circle").css({ "background-image": `conic-gradient(rgb(67, 134, 0) ${completedPercentage}%, gray ${completedPercentage - 100}%)` });
            } else {
                $("#inner-circle").html(`0%`)
                $("#graph-statistics").html(`<div class="col">0 Done</div>
                    <div class="col">0 Pending</div>`)
                $("#outer-circle").css({ "background-image": `conic-gradient(rgb(67, 134, 0) 0%, gray -100%)` });
                $("#taskListViewBody").html(response.message)
            }
        },
        error: function (error) {
            alert("Error");
            alert(JSON.parse(response).message);
        },
    });
}
// Get task list by taskgroup id

// Task creation
$('#createTaskForm').on('submit', function (e) {
    // To prevent form from submiting
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("createTaskFormSubmit", true);
    formData.append("tg_id", selectedTaskGroupId);

    $.ajax({
        url: "./controls/taskAction.php",
        type: "POST",
        dataType: "html",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            response = JSON.parse(response);
            if (response.status) {
                getTaskList(selectedTaskGroupId);
                $('#createTask').modal('toggle');
            } else {
                alert(response.message);
            }
        },
        error: function (error) {
            alert("Error");
            alert(JSON.parse(response).message);
        },
    });

});
// Task creation end

// Get task list by taskgroup id
function taskStatusChange(taskId) {
    $.ajax({
        url: "./controls/taskAction.php",
        type: "POST",
        data: {
            taskStatusUpdate: true,
            taskId: taskId,
        },
        success: function (response) {
            response = JSON.parse(response)
            if (response.status) {
                getTaskList(selectedTaskGroupId);
            } else {
                console.log(response.message)
            }
        },
        error: function (error) {
            alert("Error");
            alert(JSON.parse(response).message);
        },
    });
}
// Get task list by taskgroup id

// Task removal ajax call
function taskStatusToggle(taskId, isFromTrash = false) {
    $.ajax({
        url: "./controls/taskAction.php",
        type: "POST",
        data: {
            taskStatusToggle: true,
            taskId: taskId,
        },
        success: function (response) {
            response = JSON.parse(response)
            if (response.status) {
                if (isFromTrash) {
                    $(location).attr('href', './trash.php');
                } else {
                    getTaskList(selectedTaskGroupId);
                }
            } else {
                console.log(response.message)
            }
        },
        error: function (error) {
            alert("Error");
            alert(JSON.parse(response).message);
        },
    });
}
// Task removal ajax call end

// Task group removal ajax call
function taskGroupStatusToggle(taskGroupId = selectedTaskGroupId) {
    if (taskGroupId != null) {
        $.ajax({
            url: "./controls/taskAction.php",
            type: "POST",
            data: {
                taskGroupStatusToggle: true,
                taskGroupId: taskGroupId,
            },
            success: function (response) {
                response = JSON.parse(response)
                if (response.status) {
                    $(location).attr('href', './');
                } else {
                    console.log(response.message)
                }
            },
            error: function (error) {
                alert("Error");
                alert(JSON.parse(response).message);
            },
        });
    } else {
        alert("Please select a task group");
    }
}
// Task removal ajax call end