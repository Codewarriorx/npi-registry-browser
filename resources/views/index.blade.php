<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>NPI Registry Browser</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <!-- Styles -->

  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/main.scss'])
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary"
    x-data="{ isNavOpen: false }"
    x-on:click.outside="isNavOpen = false">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        NPI Registry Browser
      </a>
      <button class="navbar-toggler"
        x-bind:class="{ 'collapsed': !isNavOpen }"
        x-on:click="isNavOpen = !isNavOpen"
        type="button"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse"
        id="navbarSupportedContent"
        x-bind:class="{ 'show': isNavOpen }">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://npiregistry.cms.hhs.gov/search">Registry</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container mt-4">
    <livewire:npi.app />
  </main>

  <footer class="flex-shrink-0 py-4 bg-dark-subtle mt-5">
    <div class="container text-center">
      <small>Copyright &copy; {{ date('Y') }} Michael Lauridsen</small>
    </div>
  </footer>
</body>

</html>
