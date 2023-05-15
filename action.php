<?php
include 'config.php';



function getMainDevices($conn) {

    echo "<button class='btn btn-primary' id='addMainDevice'>新增主设备</button>";

    $sql = "SELECT * FROM Main";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>编号</th><th>位置</th><th>管理人</th><th>操作</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['编号'] . "</td><td>" . $row['位置'] . "</td><td>" . $row['管理人'] . "</td>";
            echo "<td><button class='btn btn-primary view-sub-devices' data-mainid='" . $row['ID'] . "'>查看附件</button>";
             echo " <button class='btn btn-warning update-main-device' data-mainid='" . $row['ID'] . "'>变更</button></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "暂无主设备数据。";
    }
}


function getMainDeviceForm($conn) {
    $mainId = isset($_GET['main_id']) ? $_GET['main_id'] : '';

    if ($mainId !== '') {
        $sql = "SELECT * FROM Main WHERE ID='$mainId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h3>编辑主设备</h3>";
            echo "<form id='update-main-device-form'>";
            echo "<input type='hidden' name='action' value='update_main_device'>";
            echo "<input type='hidden' name='ID' value='" . $row['ID'] . "'>";
            echo "<div class='form-group'><label>编号：</label><input type='text' class='form-control' name='编号' value='" . $row['编号'] . "'></div>";
            echo "<div class='form-group'><label>位置：</label><input type='text' class='form-control' name='位置' value='" . $row['位置'] . "'></div>";
            echo "<div class='form-group'><label>管理人：</label><input type='text' class='form-control' name='管理人' value='" . $row['管理人'] . "'></div>";
            echo "</form>";
        } else {
            echo "找不到指定的主设备。";
        }
    } else {
        echo "缺少主设备ID。";
    }
}

function updateMainDevice($conn) {
    $ID = isset($_POST['ID']) ? $_POST['ID'] : '';
    $编号 = isset($_POST['编号']) ? $_POST['编号'] : '';
    $位置 = isset($_POST['位置']) ? $_POST['位置'] : '';
    $管理人 = isset($_POST['管理人']) ? $_POST['管理人'] : '';

    if ($ID !== '' && $编号 !== '' && $位置 !== '' && $管理人 !== '') 
    {
        $sql = "UPDATE Main SET 编号='$编号', 位置='$位置', 管理人='$管理人' WHERE ID='$ID'";
        if ($conn->query($sql) === TRUE) {
            echo 'success';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo 'Please fill out all fields.';
    }
}

function getAddMainDeviceForm() {
?>
  <form id="add-main-device-form">
    <input type="hidden" name="action" value="add_main_device">
    <div class="form-group">
      <label>编号：</label>
      <input type="text" class="form-control" name="编号" required>
    </div>
    <div class="form-group">
      <label>位置：</label>
      <input type="text" class="form-control" name="位置" required>
    </div>
    <div class="form-group">
      <label>管理人：</label>
      <input type="text" class="form-control" name="管理人" required>
    </div>
  </form>
<?php
}

function addMainDevice($conn) {
    $编号 = isset($_POST['编号']) ? $_POST['编号'] : '';
    $位置 = isset($_POST['位置']) ? $_POST['位置'] : '';
    $管理人 = isset($_POST['管理人']) ? $_POST['管理人'] : '';

    if ($编号 !== '' && $位置 !== '' && $管理人 !== '') {
        $sql = "INSERT INTO Main (编号, 位置, 管理人) VALUES ('$编号', '$位置', '$管理人')";
        if ($conn->query($sql) === TRUE) {
            echo 'success';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo 'Please fill out all fields.';
    }
}

// ==============================================

function getSubDevices($conn) {

    $mainId = isset($_GET['main_id']) ? $_GET['main_id'] : '';

    if ($mainId !== '') {
        $sql = "SELECT * FROM Sub WHERE mainId = '$mainId' order by 类别";
    } else {
        $sql = "SELECT * FROM Sub";
    }


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>mainID</th><th>ID</th><th>名称</th><th>编号</th><th>类别</th><th>规格型号</th><th>有效期</th><th>数量</th><th>计量单位</th><th>操作</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" .$row['mainId'] . "</td><td>" .$row['ID'] . "</td><td>" . $row['名称'] . "</td><td>" . $row['编号'] . "</td><td>" . $row['类别'] . "</td><td>" . $row['规格型号'] . "</td><td>" . $row['有效期'] . "</td><td>" . $row['数量'] . "</td><td>" . $row['计量单位'] . "</td>";

            echo "<td><button type='button' class='btn btn-sm btn-warning change-subdevice' data-id='" . $row['ID'] . "'>变更</button> ";

            echo "<button type='button' class='btn btn-sm btn-info transfer-subdevice' data-id='" . $row['ID'] . "'>调拨</button> ";

            echo "<button type='button' class='btn btn-sm btn-danger del-subdevice' data-id='" . $row['ID'] . "'>删除</button>";

            echo " </td></tr>";


        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "暂无附件数据。";
    }
}


function getLocationStatistics() {
    // 查询数据库，生成地点统计的HTML，并输出
    echo "1111";
}

function getStatusStatistics() {
    // 查询数据库，生成状态统计的HTML，并输出
    echo "2222";
}


function getChangeSubDeviceForm($conn, $subId) {
    // 查询数据库，生成变更附件表单的HTML，并输出
    $sql = "SELECT * FROM Sub WHERE ID='$subId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<form id='update-sub-device-form'>";
        echo "<input type='hidden' name='action' value='update_sub_device'>";
        echo "<input type='hidden' name='ID' value='" . $row['ID'] . "'>";
        echo "<div class='form-group'><label>名称：</label><input type='text' class='form-control' name='名称' value='" . $row['名称'] . "'></div>";
        echo "<div class='form-group'><label>编号：</label><input type='text' class='form-control' name='编号' value='" . $row['编号'] . "'></div>";
        echo "<div class='form-group'><label>类别：</label><input type='text' class='form-control' name='类别' value='" . $row['类别'] . "'></div>";
        echo "<div class='form-group'><label>规格型号：</label><input type='text' class='form-control' name='规格型号' value='" . $row['规格型号'] . "'></div>";
        echo "<div class='form-group'><label>数量：</label><input type='text' class='form-control' name='数量' value='" . $row['数量'] . "'></div>";
        echo "</form>";
    } else {
        echo "找不到指定的附件。";
    }
}


function updateSubDevice($conn) {

    $ID = isset($_POST['ID']) ? $_POST['ID'] : '';
    $名称 = isset($_POST['名称']) ? $_POST['名称'] : '';
    $编号 = isset($_POST['编号']) ? $_POST['编号'] : '';
    $类别 = isset($_POST['类别']) ? $_POST['类别'] : '';
    $规格型号=isset($_POST['规格型号']) ? $_POST['规格型号'] : '';
    $数量 = isset($_POST['数量']) ? $_POST['数量'] : '';

    if ($ID == '' || $名称 == '' ) {
        echo 'Please fill out all fields.';
        return;
    }      

    $sql = "UPDATE Sub SET 名称='$名称', 编号='$编号',类别='$类别',规格型号='$规格型号',数量='$数量' WHERE ID='$ID'";
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


}


function getTransferSubDeviceForm($conn, $subId) {
    // 查询数据库，生成附件调拨表单的HTML，并输出
    $sql = "SELECT * FROM Sub WHERE ID='$subId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h3>调拨附件</h3>";
        echo "<form id='update-sub-device-form'>";

        echo "<input type='hidden' name='action' value='transfer_sub_device'>";
        echo "<input type='hidden' name='ID' value='" . $row['ID'] . "'>";
        echo "<div class='form-group'><label>当前主设备ID：</label><input type='text' class='form-control' name='current_main_id' value='" . $row['mainId'] . "' readonly></div>";
        echo "<div class='form-group'><label>目标主设备ID：</label><input type='text' class='form-control' name='target_main_id' required></div>";

        echo "<div class='form-group'><label>调拨数量：</label><input type='text' class='form-control' name='amount' value=0 required></div>";

        echo "</form>";
    } else {
        echo "找不到指定的附件。";
    }
}

function LogAction($conn,$type, $mainID,$subID, $mainID2,$subID2, $content)
{
    if($mainID2=="") $mainID2="NULL";
    if( $subID2=="")  $subID2="NULL";


    $sql = "INSERT INTO Log (类型, mainID,subID,mainID2,subID2,内容) VALUES ('$type', $mainID,$subID, $mainID2,$subID2,'$content')";

    if ($conn->query($sql) === TRUE) {
        $last_inserted_id = $conn->insert_id;

    } else {
        echo "Error: <br>" . $sql . "<br>" . $conn->error;
        return;
    }



}

function transferSubDevice($conn) {

    $ID = isset($_POST['ID']) ? $_POST['ID'] : '';
    $targetMindId = isset($_POST['target_main_id']) ? $_POST['target_main_id'] : '';
    $amount = $_POST['amount'];

    if ($ID == '' || $targetMindId == '' || $amount==0 ) {
        echo 'Please fill out all fields.';
        return;
    }


    $sql = "select * from Sub WHERE ID='$ID'";
    $result = mysqli_query($conn, $sql);
    $line = mysqli_fetch_assoc($result);
    $orgAmount = $line['数量'];
    $name = $line['名称'];
    if( $amount > $orgAmount)
    {
        echo "存量不足调拨";
        return;
    }

    $sql = "select count(*) as cc from Sub WHERE ID='$targetMindId' and 名称='$name' ";
    $result = mysqli_query($conn, $sql);
    $ttt = mysqli_fetch_assoc($result);
    if( $ttt["cc"]==0 )
    {
        if($line["有效期"]=="") $line["有效期"]="NULL";
        $sql = "insert into Sub(mainID,名称,编号,类别,规格型号,有效期,数量,计量单位) values($targetMindId,  '".$line["名称"]."','".$line["编号"]."','".$line["类别"]."','".$line["规格型号"]."',".$line["有效期"].",  0  ,'".$line["计量单位"]."')";


        if ($conn->query($sql) === TRUE) {
            $targetSubId = $conn->insert_id;

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return;
        }
    }


    $sql1 = "UPDATE Sub SET 数量=数量-$amount WHERE ID=$ID ";
    $sql2 = "UPDATE Sub SET 数量=数量+$amount WHERE ID=$targetSubId ";

    $conn->query($sql1);
    $conn->query($sql2);

    if ( $conn->query($sql1) === TRUE &&  $conn->query($sql2) === TRUE) 
    {
        $content = $amount;
        LogAction($conn,"调出", $line['mainId'], $ID,           $targetMindId  , $targetSubId , $content);
        LogAction($conn,"调入", $targetMindId  , $targetSubId , $line['mainId'], $ID          , $content);
        echo 'success';
    } 
    else 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

function searchDevices($conn) {

    // 获取搜索输入
    $searchQuery = $_POST['searchQuery'];

    // 书写 SQL 查询语句
    $sql = "SELECT *,Main.编号 as 主设备编号 FROM Main  INNER JOIN Sub ON Main.ID = Sub.mainID WHERE Sub.名称 LIKE '%$searchQuery%'";
    // var_dump($_POST);

    // 执行查询并获取结果
    $result = mysqli_query($conn, $sql);
    // 遍历结果并生成 HTML 输出

    echo "<table class='table table-striped'>";
    // echo "<thead><tr><th>ID</th><th>名称</th><th>编号</th><th>类别</th><th>规格型号</th><th>有效期</th><th>数量</th><th>计量单位</th><th>操作</th></tr></thead>";
    echo "<tbody>";

    echo "<tr><th>主设备编号</th><th>主设备位置</th><th>设备名称</th><th>设备编号</th><th>类别</th><th>规格型号</th><th>数量</th><th>计量单位</th><th>有效期</th><th>价格</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['主设备编号'] . "</td>";
        echo "<td>" . $row['位置'] . "</td>";
        echo "<td>" . $row['名称'] . "</td>";
        echo "<td>" . $row['编号'] . "</td>";
        echo "<td>" . $row['类别'] . "</td>";
        echo "<td>" . $row['规格型号'] . "</td>";
        echo "<td>" . $row['数量'] . "</td>";
        echo "<td>" . $row['计量单位'] . "</td>";
        echo "<td>" . $row['有效期'] . "</td>";
        echo "<td>" . $row['价格'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";


}


function delDevices($conn)
{
    $subId = $_GET['sub_id'];

    $result = $conn->query("select * from Sub where id=$subId");
    $line = $result->fetch_assoc();

    $sql = "delete from Sub where id = $subId";
    $conn->query($sql);
    LogAction($conn,"删除", $line['mainId'],$subId, "" , "" , $line['名称'] );
}

function echoLog($conn)
{
    $sql = "SELECT * from Log order by 时间 desc limit 10";
    $result = mysqli_query($conn, $sql);

    echo "<table class='table table-striped'>";
    echo "<tbody>";
    echo "<tr><th>mainID</th><th>subID</th><th>mainID2</th><th>subID2</th><th>类型</th><th>内容</th><th>时间</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) 
    {
        echo "<tr>";
        echo "<td>" . $row['mainID'] . "</td>";
        echo "<td>" . $row['subID'] . "</td>";
        echo "<td>" . $row['mainID2'] . "</td>";
        echo "<td>" . $row['subID2'] . "</td>";
        echo "<td>" . $row['类型'] . "</td>";
        echo "<td>" . $row['内容'] . "</td>";
        echo "<td>" . $row['时间'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}



function  expired($conn)
{
    $sql = "SELECT *,Main.编号 as 主设备编号 , IF(有效期 < NOW(), '是', '否') as 是否超期  FROM Main  INNER JOIN Sub ON Main.ID = Sub.mainID 
            where 有效期<DATE_ADD(NOW(), INTERVAL 30 DAY)";
 
    $result = mysqli_query($conn, $sql);

    echo "<table class='table'>";
    echo "<tbody>";


    echo "<tr><th>主设备编号</th><th>主设备位置</th><th>设备名称</th><th>设备编号</th><th>类别</th><th>规格型号</th><th>数量</th><th>计量单位</th><th>有效期</th><th>价格</th></tr>";


    while ($row = mysqli_fetch_assoc($result)) 
    {

        if( $row['是否超期'] == "是" )
            echo "<tr style='color:red'>";
        else 
            echo "<tr>";
        

        echo "<td>" . $row['主设备编号'] . "</td>";
        echo "<td>" . $row['位置'] . "</td>";
        echo "<td>" . $row['名称'] . "</td>";
        echo "<td>" . $row['编号'] . "</td>";
        echo "<td>" . $row['类别'] . "</td>";
        echo "<td>" . $row['规格型号'] . "</td>";
        echo "<td>" . $row['数量'] . "</td>";
        echo "<td>" . $row['计量单位'] . "</td>";
        echo "<td>" . $row['有效期'] . "</td>";
        echo "<td>" . $row['价格'] . "</td>";
        echo "</tr>";

    }
    echo "</tbody>";
    echo "</table>";

}


// ===============================================================================================================

$action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'] ;

switch ($action) {
    case 'get_main_devices':
        getMainDevices($conn);
        break;
    case 'get_sub_devices':
        getSubDevices($conn);
        break;
    case 'get_main_device_form':
        getMainDeviceForm($conn);
        break;
    case 'update_main_device':
        updateMainDevice($conn);
        break;
    case 'get_add_main_device_form':
        getAddMainDeviceForm();
        break;
    case 'add_main_device':
        addMainDevice($conn);
        break;        
    case 'get_location_statistics':
        getLocationStatistics();
        break;
    case 'get_status_statistics':
        getStatusStatistics();
        break;
    case 'get_change_sub_device_form':
        getChangeSubDeviceForm($conn,$_GET['sub_id']);
        break;
    case 'update_sub_device':
        updateSubDevice($conn);
        break;
    case 'get_transfer_sub_device_form':
        getTransferSubDeviceForm($conn, $_GET['sub_id']);
        break;
    case 'transfer_sub_device':
        transferSubDevice($conn);
        break;
    case 'search':
        searchDevices($conn);
        break;
    case 'del_sub_device':
        delDevices($conn);
        break;
    case 'expired':
        expired($conn);
        break;
    case 'log':
        echoLog($conn);
        break;
    default:
        echo "不支持的命令: " . $action;
        break;

}

$conn->close();


?>