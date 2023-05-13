<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>能源开采设备管理系统</title>

    <script src="res/jquery.min.js"></script>
    <link rel="stylesheet" href="res/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="res/fontawesome/css/all.min.css">
    <style>
        body {
            font-family: "微软雅黑", sans-serif;
        }
        .main-header {
            background-color: #343a40;
            padding: 10px 0;

            border-bottom: 1px solid gray;
        }
        .main-header .logo {
            color: #fff;
            font-size: 24px;
        }
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding-top: 30px;
        }
        .sidebar .nav-item {
            margin-bottom: 10px;
        }
        .sidebar .nav-link {
            color: #ccc;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            text-decoration: none;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .content-wrapper {
            padding: 20px;
        }
    </style>



<script>
$(document).ready(function() 
{
    // 点击主设备链接时加载主设备列表
    $('a[href="#main_devices"]').on('click', function(e) {
      e.preventDefault();
      loadMainDevices();
    });

    // 点击附件链接时加载附件列表
    $('a[href="#sub_devices"]').on('click', function(e) {
      e.preventDefault();
      loadSubDevices();
    });



    $('.content-wrapper').on('click', '.view-sub-devices', function() {
    const mainId = $(this).data('mainid');
    loadSubDevices(mainId);
    });

    // 点击变更按钮时打开对应主设备的编辑表单（模态框形式）
    $('.content-wrapper').on('click', '.update-main-device', function() {
      const mainId = $(this).data('mainid');
      loadUpdateMainDeviceForm(mainId);
    });


    // 点击新增主设备按钮时，打开新增表单（模态框形式）
    $('.content-wrapper').on('click', '#addMainDevice', function() {
      loadAddMainDeviceForm();
    });


    // 保存主设备变更
    $('#saveMainDeviceChanges').on('click', function() {
      const formData = $('#update-main-device-form').serialize();
      $.ajax({
        url: 'action.php',
        type: 'POST',
        data: formData,
        success: function(response) {

          if (response === 'success') {
            alert('主设备信息已更新。');
            $('#editMainDeviceModal').modal('hide');
            loadMainDevices();
          } else {
            // alert('更新主设备信息失败A，请稍后重试。');
          }
        },
        error: function() {
          alert('更新主设备信息失败B，请稍后重试。');
        }
      });
    });


    // 保存新增主设备
    $('#saveMainDeviceChanges').on('click', function() {
      const formData = $('#add-main-device-form').serialize();

      $.ajax({
        url: 'action.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response === 'success') {
            alert('主设备已添加。');
            $('#editMainDeviceModal').modal('hide');
            loadMainDevices();
          } else {
            alert('添加主设备失败1，请稍后重试。');
          }
        },
        error: function() {
          alert('添加主设备失败2，请稍后重试。');
        }
      });
    });





    // 保存附件的: 变更 / 调拨
    $('#saveSubDeviceChanges').on('click', function() {
      const formData = $('#update-sub-device-form').serialize();
      $.ajax({
        url: 'action.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response === 'success') {

            $('#changeSubDeviceModal').modal('hide');
            loadSubDevices();

          } else {
            alert('附件变更失败1，请稍后重试。\n' + response);
          }
        },
        error: function() {
          alert('附件变更失败2，请稍后重试。');
        }
      });

    });




    // 点击地点统计链接时加载地点统计
    $('a[href="#location_statistics"]').on('click', function(e) {
      e.preventDefault();
      loadLocationStatistics();
    });

    // 点击状态统计链接时加载状态统计
    $('a[href="#status_statistics"]').on('click', function(e) {
      e.preventDefault();
      loadStatusStatistics();
    });


    $('a[href="#log"]').on('click', function(e) {
      e.preventDefault();

      $.ajax({
        url: 'action.php',
        type: 'GET',
        data: { action: 'log' },
        success: function(response) {
          $('.content-wrapper').html(response);          
        },
        error: function() {
          alert('失败');
        }
      });
      
    });


    // 绑定附件列表中的变更按钮点击事件
    $(document).on('click', '.change-subdevice', function() {
      var subDeviceId = $(this).data('id');
      openChangeSubDeviceForm(subDeviceId);
    });


    // 绑定附件列表中的调拨按钮点击事件
    $(document).on('click', '.transfer-subdevice', function() {
      var subDeviceId = $(this).data('id');
      // openChangeSubDeviceForm(subDeviceId);

      $.ajax({
        url: 'action.php',
        type: 'GET',
        data: { action: 'get_transfer_sub_device_form', sub_id: subDeviceId },
        success: function(response) {
          $('#changeSubDeviceModal .modal-body').html(response);
          $('#changeSubDeviceModal').modal('show');
        },
        error: function() {
          alert('加载变更附件表单失败，请稍后重试。');
        }
      });
    });


    $(document).on('click', '.del-subdevice', function() {
      var subDeviceId = $(this).data('id');
      $.ajax({
        url: 'action.php',
        type: 'GET',
        data: { action: 'del_sub_device', sub_id: subDeviceId },
        success: function(response) {
console.log(response);
          alert(response);
        },
        error: function() {
          alert('失败');
        }
      });
    });





    $("#search-btn").click(function () {
        var searchQuery = $("#search-query").val();

        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                action: "search",
                searchQuery: searchQuery
            },
            success: function (response) {
                $('.content-wrapper').html(response);
            },
            error: function (xhr, status, error) {
                alert("搜索请求失败: " + error);
            }
        });
    });


});



function openChangeSubDeviceForm(subDeviceId) {
    
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_change_sub_device_form', sub_id: subDeviceId },
    success: function(response) {
      $('#changeSubDeviceModal .modal-body').html(response);
      $('#changeSubDeviceModal').modal('show');
    },
    error: function() {
      alert('加载变更附件表单失败，请稍后重试。');
    }
  });
}




function loadAddMainDeviceForm() {
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_add_main_device_form' },
    success: function(response) {
      $('#editMainDeviceModal .modal-body').html(response);
      $('#editMainDeviceModal').modal('show');
    },
    error: function() {
      alert('加载新增表单失败，请稍后重试。');
    }
  });
}


function loadMainDevices() {
    $.ajax({
      url: 'action.php',
      type: 'GET',
      data: { action: 'get_main_devices' },
      success: function(response) {
        $('.content-wrapper').html(response);
      },
      error: function() {
        alert('加载主设备失败，请稍后重试。');
      }
    });
}


function loadSubDevices(mainId) {
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_sub_devices', main_id: mainId },
    success: function(response) {
      $('.content-wrapper').html(response);
    },
    error: function() {
      alert('加载附件失败，请稍后重试。');
    }
  });
}


function loadUpdateMainDeviceForm(mainId) {
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_main_device_form', main_id: mainId },
    success: function(response) {
      $('#editMainDeviceModal .modal-body').html(response);
      $('#editMainDeviceModal').modal('show');
    },
    error: function() {
      alert('加载编辑表单失败，请稍后重试。');
    }
  });
}



function loadLocationStatistics() {
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_location_statistics' },
    success: function(response) {
      $('.content-wrapper').html(response);
    },
    error: function() {
      alert('加载地点统计失败，请稍后重试。');
    }
  });
}

function loadStatusStatistics() {
  $.ajax({
    url: 'action.php',
    type: 'GET',
    data: { action: 'get_status_statistics' },
    success: function(response) {
      $('.content-wrapper').html(response);
    },
    error: function() {
      alert('加载状态统计失败，请稍后重试。');
    }
  });
}



</script>

</head>
<body>


<!-- 编辑主设备模态框 -->
<div class="modal fade" id="editMainDeviceModal" tabindex="-1" role="dialog" aria-labelledby="editMainDeviceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMainDeviceModalLabel">编辑主设备</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- 这里将插入编辑表单 -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="saveMainDeviceChanges">保存变更</button>
      </div>
    </div>
  </div>
</div>

<!-- Change Sub Device Modal -->
<div class="modal fade" id="changeSubDeviceModal" tabindex="-1" role="dialog" aria-labelledby="changeSubDeviceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeSubDeviceModalLabel">变更附件</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- 这里将加载变更附件表单 -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="saveSubDeviceChanges">保存变更</button>
      </div>
    </div>
  </div>
</div>



<div class="container-fluid row">

    <header class="main-header d-flex justify-content-between align-items-center">
        <div class="logo ml-3">
            <i class="fas fa-home" style='margin-left: 10px;'></i> 能源开采设备管理系统
        </div>

        <div class="search-box d-flex justify-content-end mr-3">
          <input type="text" name="search-query" id="search-query" placeholder="搜索设备" />
          <button type="button" id="search-btn"><i class="fas fa-search"></i></button>
        </div>

    </header>

    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#main_devices"><i class="fas fa-home"></i>主设备</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sub_devices"><i class="fas fa-cogs"></i>附件</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#statisticsSubmenu" aria-expanded="false" aria-controls="statisticsSubmenu">
                        <i class="fas fa-chart-pie"></i>统计
                    </a>
                    <ul class="collapse list-unstyled" id="statisticsSubmenu">
                        <li class="nav-item">
                            <a class="nav-link pl-4" href="#location_statistics">地点统计</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-4" href="#status_statistics">状态统计</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-bell"></i>到期通知</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#log"><i class="fas fa-history"></i>日志记录</a>
                </li>
            </ul>
        </nav>
        <main class="col-md-10 content-wrapper">
            <!-- Tab标签内容区域 -->
        </main>
    </div>
</div>
<script src="res/popper.min.js"></script>
<script src="res/bootstrap.min.js"></script>
</body>
</html>
