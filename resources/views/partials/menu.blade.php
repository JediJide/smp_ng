<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('statment_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/therapy-areas*") ? "c-show" : "" }} {{ request()->is("admin/categories*") ? "c-show" : "" }} {{ request()->is("admin/themes*") ? "c-show" : "" }} {{ request()->is("admin/resources*") ? "c-show" : "" }} {{ request()->is("admin/references*") ? "c-show" : "" }} {{ request()->is("admin/statements*") ? "c-show" : "" }} {{ request()->is("admin/statement-statuses*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.statmentManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('audience_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audiences.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audience") || request()->is("admin/audience/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.audience.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('therapy_area_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.therapy-areas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/therapy-areas") || request()->is("admin/therapy-areas/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.therapyArea.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.category.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('theme_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.themes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/themes") || request()->is("admin/themes/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.theme.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('resource_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.resources.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/resources") || request()->is("admin/resources/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.resource.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('reference_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.references.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/references") || request()->is("admin/references/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.reference.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('statement_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.statements.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/statements") || request()->is("admin/statements/*") ? "c-active" : "" }}">
                                <i class="fa-fw table_view c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.statement.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('statement_status_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.statement-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/statement-statuses") || request()->is("admin/statement-statuses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.statementStatus.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('glossary_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.glossaries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/glossaries") || request()->is("admin/glossaries/*") ? "c-active" : "" }}">
                    <i class="fa-fw table_view c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.glossary.title') }}
                </a>
            </li>
        @endcan
        @can('lexicon_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.lexicons.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lexicons") || request()->is("admin/lexicons/*") ? "c-active" : "" }}">
                    <i class="fa-fw table_view c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.lexicon.title') }}
                </a>
            </li>
        @endcan
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
