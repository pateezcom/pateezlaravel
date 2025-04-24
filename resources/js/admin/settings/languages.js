/**
 * Language Settings JavaScript
 * Dil Ayarları JavaScript
 */

'use strict';

// DOM Loaded
document.addEventListener('DOMContentLoaded', function () {
  // DataTable initialization
  const langTable = $('.datatables-basic');
  
  if (langTable.length) {
    const langTableInstance = langTable.DataTable({
      responsive: true,
      autoWidth: false,
      orderCellsTop: true,
      order: [[1, 'asc']],
      columnDefs: [
        {
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // ID
          targets: 1,
          responsivePriority: 1
        }
      ],
      dom:
        '<"row"' +
        '<"col-md-2"<l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        search: '',
        searchPlaceholder: 'Dil adı, kodu ara...',
        info: "_START_ - _END_ / _TOTAL_ kayıt",
        paginate: {
          previous: '<i class="ti ti-chevron-left"></i>',
          next: '<i class="ti ti-chevron-right"></i>'
        },
        lengthMenu: 'Göster _MENU_ kayıt',
        zeroRecords: "Eşleşen kayıt bulunamadı",
        emptyTable: "Tabloda veri yok",
        infoEmpty: "0 kayıttan 0 - 0 arası gösteriliyor",
        infoFiltered: "(_MAX_ kayıt arasından filtrelendi)"
      },
      buttons: [
        {          
          extend: 'collection',
          className:
            'btn btn-label-secondary dropdown-toggle me-3 waves-effect waves-light border-left-0 border-right-0 rounded',
          text: '<i class="ti ti-upload ti-xs me-sm-1 align-text-bottom"></i> <span class="d-none d-sm-inline-block">Dışa Aktar</span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-1" ></i>Yazdır',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-1" ></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-description me-1" ></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5]
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-1" ></i>Kopyala',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5]
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-file-import ti-xs me-sm-1"></i><span class="d-none d-sm-inline-block">Dil İçe Aktar</span>',
          className: 'btn btn-label-secondary waves-effect waves-light me-3 rounded border-left-0 border-right-0',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#importLanguageModal'
          }
        },
        {
          text: '<i class="ti ti-plus ti-xs me-md-2"></i><span class="d-md-inline-block d-none">Yeni Dil Ekle</span>',
          className: 'btn btn-primary waves-effect waves-light rounded border-left-0 border-right-0',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addLanguageModal'
          }
        }
      ],
      displayLength: 10,
      lengthMenu: [10, 25, 50, 100]
    });
  }

  // Table initialization sonrası
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
    $('.dataTables_filter').addClass('ms-n4 me-4 mt-0 mt-md-6');
  }, 300);

  // Select2 Initialization
  const select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        dropdownParent: $this.parent(),
        placeholder: 'Seçiniz'
      });
    });
  }

  // Form validation
  const addLanguageForm = document.getElementById('addLanguageForm');
  if (addLanguageForm) {
    FormValidation.formValidation(addLanguageForm, {
      fields: {
        languageName: {
          validators: {
            notEmpty: {
              message: 'Lütfen dil adını giriniz'
            }
          }
        },
        shortForm: {
          validators: {
            notEmpty: {
              message: 'Lütfen kısa formu giriniz'
            }
          }
        },
        languageCode: {
          validators: {
            notEmpty: {
              message: 'Lütfen dil kodunu giriniz'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.mb-4'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function () {
      // Form geçerli olduğunda yapılacak işlemler
      // Sunucuya form verilerini gönder vs.
      
      // Örnek: Modal'ı kapat
      var modal = new bootstrap.Modal(document.getElementById('addLanguageModal'));
      modal.hide();
    });
  }
});
