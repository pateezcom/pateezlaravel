<template>
  <div class="home">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-10 text-center">
          <h1 class="mb-4 fw-bold text-primary">Pateez Haber Ön Sayfa</h1>
          <div class="card shadow-sm border-0">
            <div class="card-body p-5">
              <p class="lead mb-4">Bu bir örnek Vue.js ön sayfa tasarımıdır. Buraya özel içeriklerinizi ekleyebilirsiniz.</p>
              
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle me-2 fs-5"></i>
                <div>
                  Vue.js kullanılarak oluşturulmuş örnek bir sayfadır. Laravel admin paneli ile entegre çalışacak şekilde düzenlenmiştir.
                </div>
              </div>
              
              <div class="row mt-5">
                <div class="col-md-4 mb-4 mb-md-0">
                  <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                      <i class="ti ti-news text-primary fs-1 mb-3"></i>
                      <h4 class="card-title">Haberler</h4>
                      <p class="card-text text-muted">En güncel haberleri okuyun</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                  <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                      <i class="ti ti-user text-success fs-1 mb-3"></i>
                      <h4 class="card-title">Yazarlar</h4>
                      <p class="card-text text-muted">Uzman yorumcuları takip edin</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                      <i class="ti ti-photo text-info fs-1 mb-3"></i>
                      <h4 class="card-title">Galeri</h4>
                      <p class="card-text text-muted">Fotoğraflar ve videolar</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home'
}
</script>
