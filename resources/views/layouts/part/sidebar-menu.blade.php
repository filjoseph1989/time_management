<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        {{-- <img src="{{ mix('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">Time Mgmt</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ mix('img/user1-128x128.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ ucfirst(Auth::user()->name) }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">                
                <li>
                    <router-link to="/dashboard" class="nav-link">
                        <i class="nav-icon fa fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </router-link>
                </li>
                <li>
                    <router-link to="/timer" class="nav-link">
                        <i class="fas fa-clock nav-icon"></i>
                        <p>Timer</p>
                    </router-link>
                </li>
                <li>
                    <router-link to="/report" class="nav-link">
                        <i class="fas fa-align-justify nav-icon"></i>
                        <p>Report</p>
                    </router-link>
                </li>
                <li>
                    <router-link to="/projects-list" class="nav-link">
                        <i class="fas fa-suitcase nav-icon"></i>
                        <p>Projects</p>
                    </router-link>
                </li>
                <li>
                    <router-link to="/client" class="nav-link">
                        <i class="fas fa-address-book nav-icon"></i>
                        <p>Client</p>
                    </router-link>
                </li>
                <li>                    
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>                
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>
</aside>
