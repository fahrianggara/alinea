<!DOCTYPE html>
<html>

<head>
    <title>Admin List</title>
</head>

<body>
    <h1>Daftar Admin</h1>
    <ul>
        @foreach($admins as $admin)
        <li>{{ $admin->user->email }} - {{ $admin->role }}</li>
        @endforeach
    </ul>
</body>

</html>