<template>
  <div class="vue-frontend-app">
    <!-- Header -->
    <header class="header">
      <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
          <router-link class="navbar-brand" to="/">
            <span class="fw-bolder text-primary fs-4">Pateez Haber</span>
          </router-link>

          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="ti ti-menu-2"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <router-link class="nav-link fw-semibold py-3 px-4" to="/">Ana Sayfa</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link fw-semibold py-3 px-4" to="/about">Hakkımızda</router-link>
              </li>
              <li class="nav-item d-grid d-lg-block ms-lg-2">
                <a class="btn btn-primary rounded-pill px-4" href="admin/login">
                  <i class="ti ti-user me-1"></i> Admin Panel
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <!-- Page Content -->
    <main class="content-wrapper">
      <router-view></router-view>
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark py-4 mt-auto text-white">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <span class="fw-bold">Pateez Haber</span> © {{ new Date().getFullYear() }}
          </div>
          <div class="col-md-6 text-center text-md-end">
            <div class="d-inline-flex gap-3">
              <a href="#" class="text-white">Kullanım Şartları</a>
              <a href="#" class="text-white">Gizlilik Politikası</a>
              <a href="#" class="text-white">İletişim</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>
.vue-frontend-app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.content-wrapper {
  flex: 1;
}

.footer {
  margin-top: auto;
}

.nav-link.router-link-active {
  color: var(--bs-primary) !important;
  font-weight: 600;
}
</style>
