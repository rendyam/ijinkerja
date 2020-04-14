@if(Auth::guard('web')->check())
    <p class="text-success">
        You're logged in as a <strong>User</strong>
    </p>
    @else
    <p class="text-danger">
        You're logged out as a <strong>User</strong>
    </p>
@endif

@if(Auth::guard('admin')->check())
    <p class="text-success">
        You're logged in as a <strong>Admin</strong>
    </p>
    @else
    <p class="text-danger">
        You're logged out as a <strong>Admin</strong>
    </p>
@endif