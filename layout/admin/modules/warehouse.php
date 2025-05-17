<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/warehouse.css">
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Warehouse</div>
            <div class="action-buttons">
                <button id="createBtn" class="btn btn-outline" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> New
                </button>
            </div>
        </div>
        <div class="stats-cards">
            <div class="table-card">
                <div class="card-title">
                    <h3><i class="fa-solid fa-user"></i>Phiếu nhập</h3>
                    <div class="wrapperFilter">
                        <div class="search-box">
                            <i class="ri-search-line"></i>
                            <input id="searchPhone" type="text" placeholder="Tìm Kiếm Theo Mã Phiếu">
                        </div>
                        <button class="btn btn-outline btn-sm" onclick="showFormFilter()">
                            <i class="fa-solid fa-filter"></i>Bộ Lọc
                        </button>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>ID</th>
                            <th>Người nhập</th>
                            <th>NCC</th>
                            <th>Ngày tạo</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>