<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('admin.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}"
                       href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('front_page_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.front_pages.index") }}"
                           class="nav-link {{ request()->is("admin/front_pages") || request()->is("admin/front_pages/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-home">

                            </i>
                            <p>
                                {{ trans('cruds.frontPage.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('blog_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.blogs.index") }}"
                           class="nav-link {{ request()->is("admin/blogs") || request()->is("admin/blogs/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-rss">

                            </i>
                            <p>
                                {{ trans('cruds.blog.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('industry_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.industries.index") }}"
                           class="nav-link {{ request()->is("admin/industries") || request()->is("admin/industries/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-industry">

                            </i>
                            <p>
                                {{ trans('cruds.industry.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('reference_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.references.index") }}"
                           class="nav-link {{ request()->is("admin/references") || request()->is("admin/references/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bullhorn">

                            </i>
                            <p>
                                {{ trans('cruds.reference.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('testimonial_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.testimonials.index") }}"
                           class="nav-link {{ request()->is("admin/testimonials") || request()->is("admin/testimonials/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-comments">

                            </i>
                            <p>
                                {{ trans('cruds.testimonial.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.products.index") }}"
                           class="nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-shopping-cart">

                            </i>
                            <p>
                                {{ trans('cruds.product.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('brand_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.brands.index") }}"
                           class="nav-link {{ request()->is("admin/brands") || request()->is("admin/brands/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bookmark">

                            </i>
                            <p>
                                {{ trans('cruds.brand.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('application_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.applications.index") }}"
                           class="nav-link {{ request()->is("admin/applications") || request()->is("admin/applications/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fab fa-autoprefixer">

                            </i>
                            <p>
                                {{ trans('cruds.application.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('category_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.categories.index") }}"
                           class="nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-th-list">

                            </i>
                            <p>
                                {{ trans('cruds.category.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('contact_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.contacts.index") }}"
                           class="nav-link {{ request()->is("admin/contacts") || request()->is("admin/contacts/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-address-card">

                            </i>
                            <p>
                                {{ trans('cruds.contact.title') }}
                            </p>
                        </a>
                    </li>
                @endcan




                @can('translation_center_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/translation*") ? "menu-open" : "" }} {{ request()->is("admin/translation/dbmodels*") ? "menu-open" : "" }} {{ request()->is("admin/translation/strings*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/translation*") ? "active" : "" }} {{ request()->is("admin/translation/dbmodels*") ? "active" : "" }} {{ request()->is("admin/translation/strings*") ? "active" : "" }}" href="#">

                            <i class="fa-fw nav-icon fas fa-language"></i>
                            <p>
                                {{ trans('cruds.translationCenter.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.translation.dbmodels") }}" class="nav-link {{ request()->is("admin/translation/dbmodels") || request()->is("admin/translation/dbmodels/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-unlock-alt"></i>
                                    <p>{{ trans('admin.translation_db_models') }}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route("admin.translation.strings") }}" class="nav-link {{ request()->is("admin/translation/strings") || request()->is("admin/translation/strings/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase"></i>
                                    <p>{{ trans('admin.translation_static_strings') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan






                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users"></i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}"
                                       class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}"
                                       class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}"
                                       class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                               href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                        <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
