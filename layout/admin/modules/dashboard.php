<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

</head>
<body>
    <div class="main-content">
        <!-- Page Title -->
        <div id="pageTitle" class="page-title"></div>

        <!-- Stats Cards -->
        <div class="stats-cards">

            <!--card1-->
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">1,504</div>
                        <div class="card-label">Total Users</div>
                    </div>
                    <div class="card-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% from last month</span>
                </div>
            </div>

            <!-- card2 -->
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">$12,750</div>
                        <div class="card-label">Total Revenue</div>
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>8.2% from last month</span>
                </div>
            </div>

            <!-- card3 -->
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">324</div>
                        <div class="card-label">Total Orders</div>
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="card-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>3.1% from last month</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">85%</div>
                        <div class="card-label">Conversion Rate</div>
                    </div>
                    <div class="card-icon orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>4.6% from last month</span>
                </div>
            </div>
        </div>

        
        <!-- Recent Orders -->
        <div id="tableCard" class="table-card"></div>

    </div>


    
    <script>
        
        // Call the createPageTitle function from the external JS file
        const pageTitleHTML = createPageTitle("Dashboard", true, true); 
        document.querySelector("#pageTitle").innerHTML = pageTitleHTML;

        // Example usage
        const iconClass = "fas fa-shopping-bag";
            const tableName = "Recent Orders";
            const columns = ["Order ID", "Customer", "Date", "Amount", "Status", "Actions"];
            const data = [
                ["#ORD-001", "John Smith", "15 Mar 2025", "$125.00", '<span class="status active"><i class="fas fa-check-circle"></i> Completed</span>', '<button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</button>'],
                ["#ORD-002", "Emma Johnson", "14 Mar 2025", "$245.99", '<span class="status pending"><i class="fas fa-clock"></i> Pending</span>', '<button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</button>'],
                ["#ORD-003", "Michael Brown", "13 Mar 2025", "$79.50", '<span class="status active"><i class="fas fa-check-circle"></i> Completed</span>', '<button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</button>'],
                ["#ORD-004", "Sarah Davis", "12 Mar 2025", "$350.00", '<span class="status cancelled"><i class="fas fa-times-circle"></i> Cancelled</span>', '<button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</button>'],
                ["#ORD-005", "David Wilson", "11 Mar 2025", "$185.25", '<span class="status active"><i class="fas fa-check-circle"></i> Completed</span>', '<button class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</button>']
            ];
            
            // Generate the HTML table
            document.querySelector("#tableCard").innerHTML = generateTable(iconClass, tableName, columns, data);
    </script>
    </body>
</html>


