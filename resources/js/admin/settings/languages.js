/**
 * Language Settings Javascript
 * Dil Ayarları Javascript Dosyası
 */

'use strict';

// DataTable (jquery)
$(function () {
  // Değişken tanımlamaları
  var dt_language_table = $('.datatables-languages'),
    offCanvasForm = $('#offcanvasAddUser');

  // DataTable nesnesini global olarak tanımla
  var dt_language;

  // Ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Tabloyu yenileme fonksiyonu
  window.refreshLanguageTable = function () {
    // Mevcut tabloyu yok et
    if (dt_language) {
      dt_language.destroy();
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
              ? '<span class="badge bg-label-primary">Varsayılan</span>'
              : '<button class="btn btn-sm btn-outline-primary set-default-language" data-id="' +
                  full.id +
                  '">Varsayılan Yap</button>';
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
              '<button class="btn btn-sm btn-outline-info me-1 edit-translations" data-id="' +
              full.id +
              '">' +
              '<i class="ti ti-edit ti-xs me-1"></i>Çeviriler' +
              '</button>' +
              '<a href="' +
              baseUrl +
              'admin/settings/languages/' +
              full.id +
              '/export" class="btn btn-sm btn-outline-secondary">' +
              '<i class="ti ti-download ti-xs me-1"></i>Dışa Aktar' +
              '</a>' +
              '</div>'
            );
          }
        },
        {
          // İşlemler
          targets: -1,
          title: 'İşlemler',
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
              '<i class="ti ti-pencil me-1"></i>Düzenle' +
              '</a>' +
              '<a class="dropdown-item text-danger delete-record" href="javascript:void(0);" data-id="' +
              full.id +
              '">' +
              '<i class="ti ti-trash me-1"></i>Sil' +
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
        searchPlaceholder: 'Dil Ara',
        info: 'Toplam _TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor',
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
          text: '<i class="ti ti-download me-1"></i> Dışa Aktar',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2"></i>Yazdır',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2"></i>CSV',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>PDF',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3]
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-file-import me-1"></i>Dil İçe Aktar',
          className: 'add-new btn btn-secondary me-2',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#importLanguageModal'
          }
        },
        {
          text: '<i class="ti ti-plus me-1"></i>Yeni Dil Ekle',
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
              return 'Dil Detayları: ' + data.name;
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // Başlık boş değilse modal popup'ta göster
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

  // Dil DataTable
  if (dt_language_table.length) {
    // İlk yükleme
    window.refreshLanguageTable();
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
      },
      error: function (xhr) {
        // Sessizce hatayı geç
      }
    });
  });

  // Dil Sil
  $(document).on('click', '.delete-record', function () {
    var id = $(this).data('id');

    // Doğrudan sil (onay isteme)
    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/' + id,
      method: 'DELETE',
      success: function (response) {
        if (response.success) {
          // Tabloyu yenile
          window.refreshLanguageTable();
        } else {
          // Hata durumunda tabloyu yine de yenile
          window.refreshLanguageTable();
        }
      },
      error: function (xhr) {
        // Hata durumunda sessizce tabloyu yenile
        window.refreshLanguageTable();
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
          // Tabloyu yenile
          window.refreshLanguageTable();
        } else {
          // Hata durumunda tabloyu yine de yenile
          window.refreshLanguageTable();
        }
      },
      error: function (xhr) {
        // Hata durumunda sessizce tabloyu yenile
        window.refreshLanguageTable();
      }
    });
  });

  // Dil İçe Aktar
  $('#importLanguageForm').on('submit', function (e) {
    // Eğer language-form-validation.js tarafından zaten işleniyorsa bu olayı ele alma
    if (e.isDefaultPrevented()) return;

    e.preventDefault();

    var formData = new FormData(this);

    // AJAX isteği
    $.ajax({
      url: baseUrl + 'admin/settings/languages/import',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          // Modal kapat ve tabloyu yenile
          $('#importLanguageModal').modal('hide');
          window.refreshLanguageTable();
        } else {
          // Hata durumunda modalı kapat ve tabloyu yenile
          $('#importLanguageModal').modal('hide');
          window.refreshLanguageTable();
        }
      },
      error: function (xhr) {
        // Hata durumunda modalı kapat ve tabloyu yenile
        $('#importLanguageModal').modal('hide');
        window.refreshLanguageTable();
      }
    });
  });
});
