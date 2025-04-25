<!-- Import Language Modal -->
<div class="modal fade" id="importLanguageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <h5 class="modal-title w-100 text-center">Dil İçe Aktar</h5>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <p>JSON dil dosyasını yükleyin</p>
        </div>
        
        <form id="importLanguageForm" class="row g-3" enctype="multipart/form-data">
            @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="languageFile">JSON Dil Dosyası</label>
            <input class="form-control" type="file" id="languageFile" name="languageFile" accept=".json">
            <div class="invalid-feedback"></div>
            <small class="text-muted">Sadece .json uzantılı dosyalar kabul edilir</small>
          </div>
          <div class="col-12 text-center mt-4">
            <button type="submit" id="importLanguageSubmitBtn" class="btn btn-primary me-3">İçe Aktar</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">İptal</button>
          </div>
          
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              // Modal temizleme işlevleri
              $('#importLanguageModal').on('hidden.bs.modal', function () {
                console.log('Modal kapatıldı, form temizleniyor...');
                document.getElementById('importLanguageForm').reset();
                $('.invalid-feedback').text('');
                $('.is-invalid').removeClass('is-invalid');
              });

              $('#importLanguageModal').on('show.bs.modal', function () {
                console.log('Modal açılıyor, form temizleniyor...');
                document.getElementById('importLanguageForm').reset();
                $('.invalid-feedback').text('');
                $('.is-invalid').removeClass('is-invalid');
              });
              
              // Form submit olayını engelle (bir kere bağlan)
              let formSubmitHandled = false;
              
              // Form submit işlemi
              function handleFormSubmit(e) {
                e.preventDefault();
                e.stopPropagation(); // İkinci formun çalışmasını engelle
                
                console.log('Form submit edildi');
                
                // Dosya kontrolü
                const fileInput = document.getElementById('languageFile');
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                  Swal.fire({
                    title: 'Uyarı!',
                    text: 'Lütfen bir dosya seçin',
                    icon: 'warning',
                    customClass: {
                      confirmButton: 'btn btn-warning waves-effect waves-light'
                    },
                    buttonsStyling: false
                  });
                  return false;
                }
                
                // Submit butonunu devre dışı bırak
                const submitBtn = document.getElementById('importLanguageSubmitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> İşleniyor...';
                
                // FormData oluştur
                const formData = new FormData(document.getElementById('importLanguageForm'));
                
                // AJAX isteği
                $.ajax({
                  url: '{{ route("admin.settings.languages.import") }}',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                  },
                  success: function(response) {
                    console.log('Başarılı yanıt:', response);
                    
                    // Submit butonunu geri etkinleştir
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    
                    if (response.success) {
                      // Modal kapat ve tabloyu yenile
                      $('#importLanguageModal').modal('hide');
                      if (typeof window.refreshLanguageTable === 'function') {
                        window.refreshLanguageTable();
                      }
                      
                      // Başarı mesajı göster
                      Swal.fire({
                        title: 'Başarılı!',
                        text: response.message || 'Dil ve çeviriler başarıyla içe aktarıldı.',
                        icon: 'success',
                        customClass: {
                          confirmButton: 'btn btn-success waves-effect waves-light'
                        },
                        buttonsStyling: false
                      });
                    } else {
                      // Hata mesajı göster
                      Swal.fire({
                        title: 'Uyarı!',
                        text: response.error || response.message || 'Bir hata oluştu.',
                        icon: 'warning',
                        customClass: {
                          confirmButton: 'btn btn-warning waves-effect waves-light'
                        },
                        buttonsStyling: false
                      });
                    }
                  },
                  error: function(xhr, status, error) {
                    console.error('AJAX Hatası:', status, error);
                    console.log('Yanıt:', xhr.responseText);
                    
                    // Submit butonunu geri etkinleştir
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    
                    let errorMessage = 'İşlem sırasında bir hata oluştu.';
                    
                    if (xhr.responseJSON) {
                      if (xhr.responseJSON.error) errorMessage = xhr.responseJSON.error;
                      else if (xhr.responseJSON.message) errorMessage = xhr.responseJSON.message;
                    }
                    
                    // Hata mesajı göster
                    Swal.fire({
                      title: 'Hata!',
                      text: errorMessage,
                      icon: 'error',
                      customClass: {
                        confirmButton: 'btn btn-danger waves-effect waves-light'
                      },
                      buttonsStyling: false
                    });
                  }
                });
                
                return false;
              }
              
              // Form submit olayı dinleyici ekle
              const importForm = document.getElementById('importLanguageForm');
              if (importForm && !formSubmitHandled) {
                formSubmitHandled = true;
                importForm.addEventListener('submit', handleFormSubmit);
              }
            });
          </script>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Import Language Modal -->
