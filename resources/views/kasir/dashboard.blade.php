<!DOCTYPE html>
<html><head><title>Kasir Dashboard</title></head>
<body>
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, {{ Auth::user()->username }}!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>