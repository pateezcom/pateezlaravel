/**
 * Language Settings Javascript
 * Dil Ayarları Javascript Dosyası
 */

'use strict';

// DataTable (jquery)
$(function () {
  // Değişken tanımlamaları
  var dt_language_table = $('.datatables-languages');

  // DataTable nesnesini global olarak tanımla
  var dt_language;

  // Modal odak sorunu için özel kapatıcı
  function setupModalCloseHandler() {
    // Kapatma butonları için
    $(document)
      .off('click.modal-close')
      .on('click.modal-close', '.modal .btn-close, .modal [data-bs-dismiss="modal"]', function (e) {
        // Butonun olduğu modal'ı bul
        const modalElement = $(this).closest('.modal');
        const modalId = modalElement.attr('id');

        // Aktif elementten focus'ı kaldır
        if (document.activeElement) {
          document.activeElement.blur();
        }
      });
  }

  // Sayfa yüklenirken özel kapatıcıyı ayarla
  setupModalCloseHandler();

  // Hide event'i için özel işlem
  $(document).on('hide.bs.modal', '.modal', function (e) {
    // Aktif elementten focus'ı kaldır
    if (document.activeElement) {
      document.activeElement.blur();
    }
  });

  // Modal tamamen kapandığında form temizle
  $(document).on('hidden.bs.modal', '.modal', function (e) {
    const modalId = $(this).attr('id');
    if (modalId === 'addLanguageModal') {
      const form = document.getElementById('addLanguageForm');
      if (form) {
        form.reset();
        $(form).find('.invalid-feedback').text('');
        $(form).find('.is-invalid').removeClass('is-invalid');
      }
    } else if (modalId === 'editLanguageModal') {
      const form = document.getElementById('editLanguageForm');
      if (form) {
        form.reset();
        $(form).find('.invalid-feedback').text('');
        $(form).find('.is-invalid').removeClass('is-invalid');
      }
    } else if (modalId === 'importLanguageModal') {
      const form = document.getElementById('importLanguageForm');
      if (form) {
        form.reset();
        $(form).find('.invalid-feedback').text('');
        $(form).find('.is-invalid').removeClass('is-invalid');
      }
    }
  });

  // Ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Tabloyu yenileme fonksiyonu
  window.refreshLanguageTable = function () {
    // Eğer tablo zaten yüklenmişse sadece reload et, destroy/recreate yapma
    if (dt_language) {
      dt_language.ajax.reload();
      return;
    }

    // Yeni tablo oluştur
    dt_language = dt_language_table.DataTable({
      processing: true,
      serverSide: false, // Basit yapı için false, gerçek veri için true olacak
      ajax: {
        url: baseUrl + 'admin/settings/languages'
      },
      columns: [
        { data: '' },
        { data: 'id' },
        { data: 'name' },
        { data: 'is_default' },
        { data: '' }, // Çeviri/Dışa aktar butonları
        { data: '' } // İşlem butonları
      ],
      columnDefs: [
        {
          // Responsive için
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // ID
          targets: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.id}</span>`;
          }
        },
        {
          // Dil adı
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full.name;
            var $code = full.code;

            // Avatar oluştur
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
            var $state = states[stateNum];
            var $initials = $name.match(/\b\w/g) || [];
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            var $output =
              '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';

            // Çıktı oluştur
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center language-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="fw-medium">' +
              $name +
              '</span>' +
              '<small class="text-muted">' +
              $code +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Varsayılan dil
          targets: 3,
          render: function (data, type, full, meta) {
            return full.is_default
              ? '<span class="badge bg-label-primary">' + __('default') + '</span>'
              : '<button class="btn btn-sm btn-outline-primary set-default-language" data-id="' +
                  full.id +
                  '">' +
                  __('set_as_default') +
                  '</button>';
          }
        },
        {
          // Çeviri/Dışa aktar
          targets: 4,
          orderable: false,
          searchable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-block">' +
              '<a href="' +
              baseUrl +
              'admin/settings/translations/' +
              full.id +
              '" class="btn btn-sm btn-outline-info me-1">' +
              '<i class="ti ti-edit ti-xs me-1"></i>' +
              __('edit_translations') +
              '</a>' +
              '<a href="' +
              baseUrl +
              'admin/settings/languages/' +
              full.id +
              '/export" class="btn btn-sm btn-outline-secondary">' +
              '<i class="ti ti-download ti-xs me-1"></i>' +
              __('export') +
              '</a>' +
              '</div>'
            );
          }
        },
        {
          // İşlemler
          targets: -1,
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="dropdown">' +
              '<button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">' +
              '<i class="ti ti-dots-vertical text-muted"></i>' +
              '</button>' +
              '<div class="dropdown-menu">' +
              '<a class="dropdown-item edit-record" href="javascript:void(0);" data-id="' +
              full.id +
              '" data-bs-toggle="modal" data-bs-target="#editLanguageModal">' +
              '<i class="ti ti-pencil me-1"></i>' +
              __('edit') +
              '</a>' +
              '<a class="dropdown-item text-danger delete-record" href="javascript:void(0);" data-id="' +
              full.id +
              '">' +
              '<i class="ti ti-trash me-1"></i>' +
              __('delete') +
              '</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'asc']],
      dom:
        '<"row g-2"' +
        '<"col-md-3"<"me-3"l>>' +
        '<"col-md-9 d-flex align-items-center justify-content-end"' +
        'f' +
        '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start ms-3"B>' +
        '>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 25, 50, 75, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: __('placeholder_search_language'),
        info: __('table_pagination_info'),
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-2',
          text: '<i class="ti ti-download me-1"></i> ' + __('export'),
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2"></i>' + __('print'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2"></i>' + __('csv'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>' + __('excel'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>' + __('pdf'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-file-import me-1"></i>' + __('import_language'),
          className: 'add-new btn btn-secondary me-2',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#importLanguageModal'
          }
        },
        {
          text: '<i class="ti ti-plus me-1"></i>' + __('add_language'),
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addLanguageModal'
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return __('language_details') + ': ' + data.name;
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== ''
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    // Form stillerini düzenle
    setTimeout(() => {
      $('.dataTables_filter .form-control').removeClass('form-control-sm');
      $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
  };

  // Dil DataTable - sayfada tablo varsa yükle
  if (dt_language_table.length) {
    // Sayfa ilk yüklemesinde 100ms bekleyerek tarayıcının hazır olmasını sağla - bu hızlı yükleme/zıplama sorununu önler
    setTimeout(function () {
      // İlk yükleme
      window.refreshLanguageTable();
    }, 100);
  }

  // Dil Ekle Modalı Açıldığında
  $('#addLanguageModal').on('show.bs.modal', function () {
    // Formu sıfırla
    $('#addLanguageForm')[0].reset();
  });

  // Dil Düzenle
  $(document).on('click', '.edit-record', function () {
    var id = $(this).data('id');

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/' + id + '/edit',
      method: 'GET',
      success: function (response) {
        if (response.language) {
          var language = response.language;

          // Form alanlarını doldur
          $('#editLanguageId').val(language.id);
          $('#editLanguageName').val(language.name);
          $('#editShortForm').val(language.code);
          $('#editLanguageCode').val(language.code);

          // Text Editör dili
          if (language.text_editor_lang) {
            $('#editTextEditorLanguage').val(language.text_editor_lang);
          } else {
            $('#editTextEditorLanguage').val(language.code);
          }

          // Yazı yönü
          if (language.is_rtl) {
            $('#editTextDirectionRTL').prop('checked', true);
          } else {
            $('#editTextDirectionLTR').prop('checked', true);
          }

          // Durum
          if (language.is_active) {
            $('#editStatusActive').prop('checked', true);
          } else {
            $('#editStatusInactive').prop('checked', true);
          }
        }
      }
    });
  });

  // Dil Sil
  $(document).on('click', '.delete-record', function () {
    var id = $(this).data('id');

    // Silmeden önce onay iste
    Swal.fire({
      title: __('confirm_language'),
      text: __('msg_language_delete_warning'),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: __('delete'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // AJAX isteği
        $.ajax({
          url: baseUrl + 'admin/settings/languages/' + id,
          method: 'DELETE',
          success: function (response) {
            if (response.success) {
              // Başarı mesajı
              Swal.fire({
                icon: 'success',
                title: __('success'),
                text: __('msg_deleted'),
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              }).then(function () {
                // Sayfa yenilemesi yap
                window.location.reload();
              });
            } else {
              // Hata mesajı
              Swal.fire({
                icon: 'error',
                title: __('error'),
                text: response.error || __('msg_error'),
                customClass: {
                  confirmButton: 'btn btn-danger'
                }
              }).then(function () {
                // Sayfa yenilemesi yap
                window.location.reload();
              });
            }
          },
          error: function (xhr) {
            // Hata mesajı
            Swal.fire({
              icon: 'error',
              title: __('error'),
              text: __('msg_error'),
              customClass: {
                confirmButton: 'btn btn-danger'
              }
            }).then(function () {
              // Sayfa yenilemesi yap
              window.location.reload();
            });
          }
        });
      }
    });
  });

  // Varsayılan Olarak Ayarla
  $(document).on('click', '.set-default-language', function () {
    var id = $(this).data('id');

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/' + id + '/set-default',
      method: 'POST',
      success: function (response) {
        if (response.success) {
          // Başarı mesajı
          Swal.fire({
            icon: 'success',
            title: __('success'),
            text: __('msg_default_language_updated'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });

          // Sayfa yenilemesi yap
          window.location.reload();
        } else {
          // Hata mesajı
          Swal.fire({
            icon: 'error',
            title: __('error'),
            text: response.error || __('msg_error'),
            customClass: {
              confirmButton: 'btn btn-danger'
            }
          });
          // Sayfa yenilemesi yap
          window.location.reload();
        }
      },
      error: function (xhr) {
        // Hata mesajı
        Swal.fire({
          icon: 'error',
          title: __('error'),
          text: __('msg_error'),
          customClass: {
            confirmButton: 'btn btn-danger'
          }
        });
        // Tabloyu yine de yenile
        window.refreshLanguageTable();
      }
    });
  });

  // Dil İçe Aktar
  $(document).on('submit', '#importLanguageForm', function (e) {
    e.preventDefault();

    // Önce hata mesajlarını temizle
    $('.invalid-feedback').text('');
    $('.is-invalid').removeClass('is-invalid');

    // Dosya kontrolü
    const fileInput = $('#languageFile')[0];
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
      $('#languageFile').addClass('is-invalid');
      $('#languageFile').next('.invalid-feedback').text(__('msg_select_file'));
      return false;
    }

    // Dosya uzantısı kontrolü
    const file = fileInput.files[0];
    const fileExt = file.name.split('.').pop().toLowerCase();
    if (fileExt !== 'json') {
      $('#languageFile').addClass('is-invalid');
      $('#languageFile').next('.invalid-feedback').text(__('msg_invalid_json_file'));
      return false;
    }

    // Submit butonunu devre dışı bırak
    const submitButton = $(this).find('button[type="submit"]');
    const originalButtonText = submitButton.html();
    submitButton.prop('disabled', true);
    submitButton.html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + __('processing')
    );

    var formData = new FormData(this);

    // CSRF token'ı formData'ya ekle (eğer form içinde yoksa)
    if (!formData.has('_token')) {
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    }

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/import',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        // Başarılı yanıt
        if (response.success) {
          // Modal kapat ve sayfayı yenile
          $('#importLanguageModal').modal('hide');
          window.location.reload();

          // Başarı mesajı göster
          Swal.fire({
            icon: 'success',
            title: __('success'),
            text: response.message || __('msg_import_language_success'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        } else {
          // Hata mesajını göster
          submitButton.prop('disabled', false);
          submitButton.html(originalButtonText);

          if (response.error) {
            Swal.fire({
              icon: 'error',
              title: __('error'),
              text: response.error,
              customClass: {
                confirmButton: 'btn btn-danger'
              }
            });
          } else if (response.message) {
            Swal.fire({
              icon: 'warning',
              title: __('warning'),
              text: response.message,
              customClass: {
                confirmButton: 'btn btn-warning'
              }
            });
          }
        }
      },
      error: function (xhr, status, error) {
        // AJAX Hatası

        // Submit butonunu sıfırla
        submitButton.prop('disabled', false);
        submitButton.html(originalButtonText);

        let errorMessage = __('msg_error');

        if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }

        Swal.fire({
          icon: 'error',
          title: __('error'),
          text: errorMessage,
          customClass: {
            confirmButton: 'btn btn-danger'
          }
        });
      }
    });

    return false;
  });
});
