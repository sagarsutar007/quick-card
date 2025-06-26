<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('management.dashboard') }}" class="text-nowrap logo-img">
            <img src="{{ asset('images/logos/black-logo.png') }}" class="dark-logo" width="120" alt="" />
            <img src="{{ asset('images/logos/black-logo.png') }}" class="light-logo"  width="120" alt="" />
        </a>
        <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8 text-muted"></i>
        </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MAIN NAVIGATION</span>
                </li>
                
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-speedometer"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.schools') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-backpack"></i>
                        </span>
                        <span class="hide-menu">Schools</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.clusters') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-collection"></i>
                        </span>
                        <span class="hide-menu">Cluster</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.blocks') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-geo"></i>
                        </span>
                        <span class="hide-menu">Block</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.students') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-backpack"></i>
                        </span>
                        <span class="hide-menu">Students</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.users') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-people"></i>
                        </span>
                        <span class="hide-menu">Users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('management.settings') }}" aria-expanded="false">
                        <span>
                            <i class="bi bi-sliders"></i>
                        </span>
                        <span class="hide-menu">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>