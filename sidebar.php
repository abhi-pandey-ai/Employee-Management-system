 

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
     
    <div class="sidebar-brand">
        <a href="./index.html" class="brand-link">
            <img src="/adminlte/dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">AdminLTE 4</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <?php 
                $currentpage = basename($_SERVER["PHP_SELF"]);
            ?>
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?= ($currentpage == "dashboard.php") ? "active" : ""; ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>               
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?= ($currentpage == "users.php") ? "active" : ""; ?>" >
                        <i class=" nav-icon bi bi-person-gear"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="employee-data.php" class="nav-link <?= ($currentpage == "employee-data.php") ? "active" : ""; ?>">
                        <i class="bi bi-people-fill nav-icon "></i>
                        <p>Employees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-employee-attendence.php" class="nav-link <?= ($currentpage == "add-employee-attendence.php") ? "active" : ""; ?>">
                        <i class="bi bi-clipboard-check nav-icon"></i>
                        <p>Employee Attendance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>



            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>