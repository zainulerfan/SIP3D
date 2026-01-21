<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Akses Ditolak - SIP3D</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #f8fafc;
      font-family: 'Inter', sans-serif;
      color: #334155;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .confirm-container {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      max-width: 600px;
      /* Lebar dikurangi sedikit agar lebih fokus */
      width: 100%;
      overflow: hidden;
    }

    .header-bar {
      background: #fff0f0;
      /* Merah muda lembut untuk warning */
      padding: 24px 32px;
      border-bottom: 1px solid #fee2e2;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .header-icon {
      width: 44px;
      height: 44px;
      background: #fee2e2;
      color: #dc2626;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .header-title h4 {
      font-weight: 700;
      font-size: 1.125rem;
      margin: 0;
      color: #991b1b;
    }

    .header-title p {
      margin: 0;
      font-size: 0.9rem;
      color: #b91c1c;
    }

    .content-body {
      padding: 32px;
    }

    .role-comparison {
      display: flex;
      gap: 16px;
      margin-bottom: 32px;
      align-items: center;
      justify-content: center;
    }

    .role-card {
      flex: 1;
      padding: 20px;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      background: #f8fafc;
      text-align: center;
    }

    .role-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 8px;
    }

    .role-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 4px;
    }

    .role-desc {
      font-size: 0.8rem;
      color: #64748b;
      line-height: 1.4;
    }

    /* Status Colors */
    .border-current {
      border-top: 4px solid #10b981;
      background: #f0fdf4;
      border-color: #bbf7d0;
    }

    .border-intent {
      border-top: 4px solid #ef4444;
      background: #fef2f2;
      border-color: #fca5a5;
    }

    .btn-lg-custom {
      padding: 12px 24px;
      font-weight: 600;
      font-size: 0.95rem;
      border-radius: 8px;
    }

    .actions-area {
      display: flex;
      padding-top: 24px;
      border-top: 1px solid #f1f5f9;
    }

    .divider-icon {
      font-size: 1.5rem;
      color: #ef4444;
    }

    @media (max-width: 600px) {
      .role-comparison {
        flex-direction: column;
      }

      .divider-icon {
        transform: rotate(90deg);
        margin: 10px 0;
      }
    }
  </style>
</head>

<body>

  <div class="confirm-container">
    <div class="header-bar">
      <div class="header-icon">
        <i class="bi bi-shield-lock-fill" style="font-size: 1.5rem;"></i>
      </div>
      <div class="header-title">
        <h4>Akses Login Dibatasi</h4>
        <p>Peran akun Anda tidak sesuai dengan portal login ini.</p>
      </div>
    </div>

    <div class="content-body">

      <div class="role-comparison mb-4">
        {{-- Card 1: Database Role --}}
        <div class="role-card border-current">
          <div class="role-label text-success">Peran Terdaftar Anda</div>
          <div class="role-value">{{ ucfirst($currentRole) }}</div>
          <div class="role-desc">Akun Anda terdaftar sebagai {{ ucfirst($currentRole) }}.</div>
        </div>

        {{-- Arrow / Divider --}}
        <div class="divider-icon">
          <i class="bi bi-x-circle"></i>
        </div>

        {{-- Card 2: Intent Role --}}
        <div class="role-card border-intent">
          <div class="role-label text-danger">Portal Login</div>
          <div class="role-value">{{ ucfirst($intent) }}</div>
          <div class="role-desc">Anda mencoba masuk sebagai {{ ucfirst($intent) }}.</div>
        </div>
      </div>

      <div class="text-center text-muted mb-4" style="max-width: 480px; margin: 0 auto;">
        <p>
          Maaf, Anda <strong>tidak diizinkan</strong> berpindah peran secara otomatis.
        </p>
        <p class="small">
          Silakan login melalui halaman yang sesuai dengan peran Anda, atau hubungi Administrator jika peran Anda perlu diubah.
        </p>
      </div>

      <div class="actions-area justify-content-center">
        {{-- Cancel Only --}}
        <form method="POST" action="{{ route('login.google.confirm_role.cancel') }}">
          @csrf
          <button type="submit" class="btn btn-danger btn-lg-custom px-5 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Halaman Login
          </button>
        </form>
      </div>

    </div>
  </div>

</body>

</html>