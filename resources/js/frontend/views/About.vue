<template>
  <div class="about">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <h1 class="mb-4 text-center fw-bold text-primary">Hakkımızda</h1>
          <div class="card shadow-sm border-0">
            <div class="card-body p-5">
              <p class="lead mb-4 text-center">Pateez Haber, güncel ve doğru haberciliği hedefleyen bir haber platformudur.</p>
              
              <div class="row align-items-center mb-5">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                  <div class="rounded-circle bg-primary bg-opacity-10 p-4 mx-auto" style="width: 150px; height: 150px;">
                    <i class="ti ti-news text-primary" style="font-size: 4rem;"></i>
                  </div>
                </div>
                <div class="col-md-8">
                  <h4 class="mb-3">Misyonumuz</h4>
                  <p>Misyonumuz, toplumu ilgilendiren her konuda doğru ve güvenilir bilgiyi zamanında sunmak, kamuoyunun bilgilenme hakkına katkı sağlamaktır.</p>
                  <h4 class="mb-3 mt-4">Vizyonumuz</h4>
                  <p>Vizyonumuz, teknolojinin tüm imkanlarını kullanarak habercilikte yeni bir çığır açmak ve küresel ölçekte tanınan bir haber portalı olmaktır.</p>
                </div>
              </div>
              
              <hr class="my-5">
              
              <div class="text-center">
                <h4 class="mb-4">İletişim Bilgileri</h4>
                <div class="d-flex justify-content-center gap-4">
                  <div class="d-flex align-items-center">
                    <i class="ti ti-mail text-primary me-2"></i>
                    <span>info@pateezhaber.com</span>
                  </div>
                  <div class="d-flex align-items-center">
                    <i class="ti ti-phone text-primary me-2"></i>
                    <span>+90 123 456 7890</span>
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
  name: 'About'
}
</script>
