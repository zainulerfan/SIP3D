<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SIP3D - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
        }

        .navbar-custom {
            background-color: #4f46e5;
            padding: 10px 28px;
        }

        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .container-dashboard {
            max-width: 1200px;
            margin: 28px auto;
            padding: 0 18px 60px;
        }

        /* Common Helpers */
        h4.section-title {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .btn-manage {
            border-radius: 10px;
            border: none;
            font-weight: 700;
            padding: 9px 22px;
        }
    </style>
    </style>
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <!-- <img src="/images/logo-politala.png" width="28"> --> SIP3D
            </a>
            <div class="d-flex">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtns = document.querySelectorAll('.btn-logout');

            logoutBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin keluar?',
                        text: "Sesi Admin Anda akan berakhir.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>