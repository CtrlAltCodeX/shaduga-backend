<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="/dist/pages/index.html" class="brand-link"> <!--begin::Brand Image--> <img src="/dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">AdminLTE 4</span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open"> <a href="{{ route('admin.dashboard') }}" class="nav-link active"> <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard

                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.users.index') }}" class="nav-link"> <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Users

                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.reviews.index') }}" class="nav-link"> <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Review
                        </p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.communities.index') }}" class="nav-link"> <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Communities
                        </p>
                    </a>
                </li>

                <li class="nav-item"> <a href="{{ route('admin.modules.index') }}" class="nav-link"> <i class="nav-icon bi bi-tree-fill"></i>
                        <p>
                            Modules
                        </p>
                    </a>
                </li>
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->