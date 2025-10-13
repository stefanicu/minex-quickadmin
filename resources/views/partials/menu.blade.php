<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="{{ url('').'/'.app()->getLocale() }}" class="brand-link align-self-center p-3 m-2" target="_blank">
        <svg width="100" viewBox="0 0 139 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M117 92L124 101H117L113 96L107 101H98L110 91L103 82H111L114 87L120 82H128L117 92ZM33 101H41L46 82H39L33 101ZM65 93L59 82H52L47 101H53L57 90L63 101H70L75 82H68L65 93ZM96 93L98 89H86L87 86H99L100 82H80L75 101H96L97 97H83L85 93H96ZM24 82L16 94L15 82H6L1 101H6L10 87L11 101H17L26 87L22 101H28L33 82H24Z" fill="white"/>
            <path d="M89 56.0001L87 55.0001L85 53.0001L81 45.0001C81 45.0001 84 41.0001 88 41.0001L96 42.0001L98 62.0001L96 64.0001C96 64.0001 89 63.0001 88 58.0001C87 57.0001 89 56.0001 89 56.0001ZM85 26.0001C92 26.0001 97 30.0001 97 30.0001L80 17.0001L78 29.0001C78 29.0001 81 26.0001 85 26.0001ZM98 26.0001L81 14.0001L80 16.0001L97 28.0001L98 26.0001ZM98 22.0001L97 21.0001L99 13.0001H100L101 12.0001C97 9.00012 88 7.00012 83 7.00012L82 13.0001L98 24.0001V22.0001ZM103 23.0001L105 14.0001H101L100 22.0001L103 23.0001ZM94 1.00012C87 0.000121348 84 6.00012 84 6.00012C84 6.00012 96 7.00012 101 11.0001C101 8.00012 100 3.00012 94 1.00012ZM80 62.0001C78 61.0001 78 62.0001 77 63.0001L83 64.0001H88V62.0001H80ZM98 66.0001C98 66.0001 97 68.0001 94 66.0001C90 63.0001 90 64.0001 89 65.0001C87 66.0001 81 66.0001 77 64.0001C76 63.0001 73 70.0001 73 70.0001H106L103 62.0001L98 66.0001ZM139 45.0001V48.0001H131C131 48.0001 131 50.0001 129 50.0001H127C127 50.0001 128 53.0001 125 52.0001L120 53.0001C120 53.0001 118 55.0001 116 53.0001L115 52.0001H113L100 63.0001L97 41.0001C97 41.0001 93 38.0001 87 39.0001C82 41.0001 80 45.0001 80 45.0001L83 53.0001L87 57.0001V61.0001C83 61.0001 79 62.0001 77 57.0001L73 45.0001C73 41.0001 77 28.0001 86 28.0001C95 28.0001 103 38.0001 103 38.0001L106 39.0001L104 49.0001L101 47.0001V51.0001L106 50.0001V47.0001L109 49.0001L110 46.0001H106L107 39.0001L122 43.0001C122 43.0001 125 42.0001 127 44.0001L130 45.0001H139ZM119 43.0001V44.0001H124V43.0001H119ZM120 48.0001L113 50.0001L114 51.0001H113H116L119 49.0001L120 48.0001ZM125 48.0001V45.0001H114L111 46.0001V48.0001L112 49.0001L125 47.0001V48.0001Z" fill="#B6B6B6"/>
            <path d="M58 56.0001L57 55.0001C57 55.0001 55 55.0001 55 53.0001L51 45.0001C51 45.0001 54 41.0001 58 41.0001C61 40.0001 65 42.0001 65 42.0001L68 62.0001L66 64.0001C66 64.0001 59 63.0001 57 58.0001L58 56.0001ZM55 26.0001C62 26.0001 66 30.0001 66 30.0001H67L49 17.0001L48 29.0001C48 29.0001 50 26.0001 55 26.0001ZM68 26.0001L51 14.0001L50 16.0001L67 28.0001L68 26.0001ZM68 22.0001L67 21.0001L68 13.0001H70V12.0001C67 9.00012 58 7.00012 53 7.00012L51 13.0001L68 24.0001V22.0001ZM73 23.0001L74 14.0001H71L69 22.0001L73 23.0001ZM63 1.00012C57 0.000121348 53 6.00012 53 6.00012C53 6.00012 65 7.00012 70 11.0001C70 8.00012 70 3.00012 63 1.00012ZM50 62.0001C48 61.0001 47 62.0001 47 63.0001L52 64.0001H58V62.0001H50ZM67 66.0001C67 66.0001 66 68.0001 63 66.0001C59 63.0001 59 64.0001 58 65.0001C57 66.0001 51 66.0001 47 64.0001C45 63.0001 43 70.0001 43 70.0001H76L72 62.0001L67 66.0001ZM109 45.0001V48.0001H101C101 48.0001 101 50.0001 99 50.0001H97C97 50.0001 98 53.0001 94 52.0001L89 53.0001C89 53.0001 87 55.0001 86 53.0001L85 52.0001H82L70 63.0001L67 41.0001C67 41.0001 62 38.0001 57 39.0001C51 41.0001 49 44.0001 49 44.0001L53 53.0001C53 53.0001 54 56.0001 56 56.0001C56 59.0001 55 60.0001 57 61.0001C53 61.0001 49 62.0001 47 57.0001L43 45.0001C43 41.0001 47 28.0001 56 28.0001C65 28.0001 73 38.0001 73 38.0001L76 39.0001L74 49.0001L71 47.0001V51.0001L76 50.0001V47.0001L78 49.0001L79 46.0001H76L77 39.0001L91 43.0001C91 43.0001 95 42.0001 97 44.0001L99 45.0001H109ZM89 43.0001V44.0001H94V43.0001H89ZM89 48.0001L83 50.0001V51.0001H85L89 49.0001V48.0001ZM95 48.0001V45.0001H84L80 46.0001L81 48.0001L82 49.0001L95 47.0001V48.0001Z" fill="#DDDDDD"/>
            <path d="M27 58.0001C28 63.0001 35 64.0001 35 64.0001L37 62.0001L35 42.0001C35 42.0001 31 40.0001 27 41.0001C23 42.0001 20 45.0001 20 45.0001L24 53.0001L26 55.0001L28 56.0001C28 56.0001 26 57.0001 27 58.0001ZM17 29.0001L19 17.0001L36 30.0001C36 30.0001 31 26.0001 24 26.0001C19 26.0001 17 29.0001 17 29.0001ZM36 28.0001L19 16.0001L20 14.0001L37 26.0001L36 28.0001ZM37 24.0001L21 13.0001L22 7.00012C27 7.00012 36 9.00012 40 12.0001L39 13.0001H38L36 21.0001L37 22.0001V24.0001ZM39 22.0001L40 14.0001H44L42 23.0001L39 22.0001ZM40 11.0001C35 7.00012 23 6.00012 23 6.00012C23 6.00012 26 0.000121355 33 1.00012C39 3.00012 40 8.00012 40 11.0001ZM27 62.0001V64.0001H22L16 63.0001C17 62.0001 17 61.0001 19 62.0001H27ZM42 62.0001L45 70.0001H12C12 70.0001 14 63.0001 16 64.0001C20 66.0001 26 66.0001 28 65.0001C29 64.0001 29 63.0001 33 66.0001C36 68.0001 37 66.0001 37 66.0001L42 62.0001ZM69 45.0001L66 43.0001H61L46 39.0001L45 46.0001H49L48 49.0001L45 47.0001V50.0001L40 51.0001V47.0001L43 49.0001L45 39.0001L42 38.0001C42 38.0001 34 28.0001 25 28.0001C16 28.0001 12 41.0001 12 45.0001L16 57.0001C18 62.0001 22 61.0001 26 61.0001V56.0001C24 56.0001 22 53.0001 22 53.0001L19 44.0001C19 44.0001 20 41.0001 26 39.0001C31 38.0001 36 41.0001 36 41.0001L39 63.0001L52 52.0001H54L55 53.0001C56 55.0001 59 53.0001 59 53.0001C59 53.0001 60 52.0001 64 52.0001C67 53.0001 66 50.0001 66 50.0001H68C70 50.0001 70 48.0001 70 48.0001H78V45.0001H69ZM63 43.0001V44.0001H58V43.0001H63ZM58 49.0001L54 51.0001H52V50.0001C53.9873 49.5945 55.9886 49.2609 58 49.0001ZM64 48.0001V47.0001L51 49.0001L50 48.0001V46.0001L53 45.0001H64V48.0001Z" fill="white"/>
            <path d="M129 79.1123H0V73.1123H129V79.1123Z" fill="white"/>
        </svg>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon"></i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('front_page_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.front_pages.index") }}" class="nav-link {{ request()->is("admin/front_pages") || request()->is("admin/front_pages/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-home"></i>
                            <p>
                                {{ trans('cruds.frontPage.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('page_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.pages.index") }}" class="nav-link {{ request()->is("admin/pages") || request()->is("admin/pages/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-file"></i>
                            <p>
                                {{ trans('cruds.page.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('blog_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.blogs.index") }}" class="nav-link {{ request()->is("admin/blogs") || request()->is("admin/blogs/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-rss"></i>
                            <p>
                                {{ trans('cruds.blog.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('industry_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.industries.index") }}" class="nav-link {{ request()->is("admin/industries") || request()->is("admin/industries/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-industry"></i>
                            <p>
                                {{ trans('cruds.industry.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('reference_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.references.index") }}" class="nav-link {{ request()->is("admin/references") || request()->is("admin/references/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bullhorn"></i>
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
                            <i class="fa-fw nav-icon fas fa-comments"></i>
                            <p>
                                {{ trans('cruds.testimonial.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-shopping-cart"></i>
                            <p>
                                {{ trans('cruds.product.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('brand_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.brands.index") }}" class="nav-link {{ request()->is("admin/brands") || request()->is("admin/brands/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bookmark"></i>
                            <p>
                                {{ trans('cruds.brand.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('application_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.applications.index") }}" class="nav-link {{ request()->is("admin/applications") || request()->is("admin/applications/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fab fa-autoprefixer"></i>
                            <p>
                                {{ trans('cruds.application.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('category_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-th-list"></i>
                            <p>
                                {{ trans('cruds.category.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('filter_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.filters.index") }}" class="nav-link {{ request()->is("admin/filters") || request()->is("admin/filters/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-th-list"></i>
                            <p>
                                {{ trans('cruds.filter.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('contact_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.contacts.index") }}" class="nav-link {{ request()->is("admin/contacts") || request()->is("admin/contacts/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-address-card"></i>
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
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt"></i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase"></i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user"></i>
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
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon"></i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon"></i>
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
