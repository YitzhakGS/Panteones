<div class="navbar-header">
    <div class="container-fluid">
        <div class="float-right">

            <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-search-dropdown">

                    <form class="p-3" onsubmit="return false;">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar algo..." aria-label="">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @include('adminmenu.layouts.partials.header.partials.dropdown-fullscreen')

            @include('adminmenu.layouts.partials.header.partials.dropdown-notifications')

            @include('adminmenu.layouts.partials.header.partials.dropdown-profile')

        </div>
        <div>
            <!-- LOGO -->
            <div class="navbar-brand-box">
                @include('adminmenu.layouts.partials.header.partials.logo-light')
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item toggle-btn waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-inline-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Buscar algo...">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form>
        </div>

    </div>
</div>