<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ env('APP_NAME')  }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  </head>
  <style>
    .pre-loader{
      height: 100vh;
      width: 100%;
      position: fixed;
      top: 0;
      bottom: 0;
      display: grid;
      place-items: center;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }

    .loader{
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: transparent;
      border: 4px solid #fff;
      border-left-color: transparent;
      animation: spin 1s infinite linear;
      transform-origin: center;
    }

    @keyframes spin{
      to{
        transform: rotate(360deg);
      }
    }
  </style>
  <body style="background: #ddd; background-repeat: no-repeat; min-height: 100vh;">

    <div class="pre-loader">
      <div class="loader"></div>
    </div>

    <nav class="navbar navbar-expand-lg bg-dark navbar-light" data-bs-theme="dark">
        <div class="container-fluid">
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto">
              <li class="nav-item">
                <a class="nav-link @if(Route::is('page.order')) active @endif" href="{{ route('page.order') }}">Pesanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(Route::is('page.product')) active @endif" href="{{ route('page.product') }}">Produk</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./api/documentation">Dokumentasi API</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <div class="container-fluid">
        <h3 class="text-center mt-3 mb-5">@yield('title')</h3>
        <div class="card">
            <div class="card-body shadow">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
      const closeLoader = () => {
        $(".pre-loader").fadeOut();
      }
    </script>

    @stack('script')
</body>
</html>