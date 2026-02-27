<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'What\'s Around')</title>
  <meta name="theme-color" content="#111827">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="stylesheet" href="{{ asset('app.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="header-content">
      <a class="lien-discret" href="{{ route('accueil') }}"><h1>What's Around</h1></a>
      @guest
      <a class="lien-discret" href="{{ route('connexion') }}"><button>Se connecter</button></a>
      @endguest
      @auth
      <div class="user-info">
        <span style="display: flex; align-items: center;">
          <span class="pc-menu">
            <a href="{{ route('abonnements') }}" class="lien-discret"><i class="fa-solid fa-star {{ request()->routeIs('abonnements') ? 'active-icon' : '' }}"></i></a>
            <a href="{{ route('evenements.inscriptions') }}" class="lien-discret"><i class="fa-solid fa-calendar-day {{ request()->routeIs('evenements.inscriptions') ? 'active-icon' : '' }}"></i></a>
            <a href="{{ route('evenements.enregistres') }}" class="lien-discret"><i class="fa-solid fa-bookmark {{ request()->routeIs('evenements.enregistres') ? 'active-icon' : '' }}"></i></a>
            <a href="{{ route('evenements.crees') }}" class="lien-discret"><i class="fa-solid fa-calendar-plus {{ request()->routeIs('evenements.crees') ? 'active-icon' : '' }}"></i></a>
            <a id="tkt" href="{{ route('profil', auth()->id()) }}" class="lien-discret"><i class="fas fa-user {{ request()->routeIs('profil') ? 'active-icon' : '' }}"></i></a>
          </span>
          <span class="phone-menu">
            <i class="fas fa-user profil"></i>
          </span>
        </span>
        <div class="user-menu">
          <a href="{{ route('profil', auth()->id()) }}"><i class="fas fa-user-circle"></i> Profil</a>
          <span class="phone-menu">
            <a href="{{ route('evenements.enregistres') }}"><i class="fa-solid fa-bookmark"></i> Evenements enregistrés</a>
            <a href="{{ route('evenements.inscriptions') }}"><i class="fa-solid fa-calendar-day"></i> Mes Inscriptions</a>
            <a href="{{ route('evenements.crees') }}"><i class="fa-solid fa-calendar-plus"></i> Mes Evenements</a>
            <a href="{{ route('abonnements') }}"><i class="fa-solid fa-star"></i> Mes Abonnements</a>
          </span>
          @if (auth()->user()->est_prive)
            <a href="{{ route('demandes') }}"><i class="fa-solid fa-user-plus"></i> Mes demandes d'abonnement</a>
          @endif
          <form action="{{ route('logout') }}" method="POST" style="display:block; margin:0;">
            @csrf
            <button type="submit" style="width:100%; text-align:left; background:none; border:none; padding:12px 20px; cursor:pointer; color:#2c3e50; font-size:1rem;"><i class="fas fa-sign-out-alt"></i> Se déconnecter</button>
          </form>
        </div>
      </div>
      @endauth
    </div>
  </header>
  <div class="content">
    <div class="pull-to-refresh">
      <span>Chargement...</span>
    </div>
    @if(session('message'))<p class="alert-success">{{ session('message') }}</p>@endif
    @if(session('error'))<p class="alert-error">{{ session('error') }}</p>@endif
    @yield('content')
  </div>
  <script>
    (function() {
      var tkt = document.getElementById('tkt');
      var userMenu = document.querySelector('.user-menu');
      if (tkt && userMenu) {
        tkt.addEventListener('mouseover', function() { userMenu.style.display = 'block'; });
        tkt.addEventListener('focus', function() { userMenu.style.display = 'block'; });
        userMenu.addEventListener('mouseleave', function() { userMenu.style.display = 'none'; });
      }
      var header = document.querySelector('header');
      if (header) {
        var lastScrollY = window.scrollY;
        window.addEventListener('scroll', function() {
          var currentScrollY = window.scrollY;
          if (currentScrollY > lastScrollY) header.classList.add('hidden');
          else header.classList.remove('hidden');
          lastScrollY = currentScrollY;
        });
      }
    })();
  </script>
  <script>
    (function() {
      var pullToRefresh = document.querySelector('.pull-to-refresh');
      if (!pullToRefresh) return;
      var touchstartY = 0;
      document.addEventListener('touchstart', function(e) {
        touchstartY = e.touches[0].clientY;
      });
      document.addEventListener('touchmove', function(e) {
        var touchY = e.touches[0].clientY;
        if (touchY - touchstartY > 0 && window.scrollY === 0) {
          pullToRefresh.classList.add('visible');
          e.preventDefault();
        }
      });
      document.addEventListener('touchend', function() {
        if (pullToRefresh.classList.contains('visible')) {
          pullToRefresh.classList.remove('visible');
          location.reload();
        }
      });
    })();
  </script>
  <script>
    (function() {
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
          navigator.serviceWorker.register('{{ asset('service-worker.js') }}').catch(function(e) {
            console.error('SW registration failed', e);
          });
        });
      }
    })();
  </script>
  @stack('scripts')
</body>
</html>
