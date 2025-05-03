/**
 * Page User List - Kullanıcıların listelenmesi ve yönetimi
 * Vuexy şablonu ile Bootstrap 5, DataTable ve Spatie Permission entegrasyonu
 */

'use strict';

// Datatable (jquery)
$(function () {
  var dt_user_table = $('.datatables-users');

  // DataTable nesnesini global olarak tanımla
  var dt_user;

  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // User view URL
  var userView = baseUrl + 'admin/users/view';

  // Status object - Çeviriler için düzgün yapı (0: pending, 1: inactive, 2: active)
  function refreshStatusObject() {
    return {
      0: { title: __('pending'), class: 'bg-label-warning' },
      1: { title: __('inactive'), class: 'bg-label-secondary' },
      2: { title: __('active'), class: 'bg-label-success' }
    };
  }
  var statusObj = refreshStatusObject();

  // StatusObj'yi global olarak erişilebilir yap ki düzenlemede kullanılabilsin
  window.refreshStatusObject = refreshStatusObject;

  // Tabloyu yenileme fonksiyonu
  window.refreshUserTable = function () {
    // Güncel status object oluştur
    statusObj = refreshStatusObject();

    if (dt_user) {
      dt_user.ajax.reload();
      return;
    }

    // Users datatable
    dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: baseUrl + 'admin/users',
        error: function (xhr, error, thrown) {
          console.error('AJAX Error:', xhr.status, xhr.responseText);
          alert(__('data anticipation_error') + ': ' + xhr.status);
        }
      },
      columns: [
        { data: 'id' },
        { data: 'id' },
        { data: 'full_name' },
        { data: 'role' },
        { data: 'reward_system_active' },
        { data: 'status' },
        { data: 'date' },
        { data: 'action' }
      ],
      columnDefs: [
        {
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
          targets: 1,
          className: 'dt-checkboxes-cell',
          orderable: false,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          searchable: false
        },
        {
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['full_name'],
              $email = full['email'],
              $username = full['username'] || '',
              $image = full['avatar'],
              $emailVerified = full['email_verified_at'] ? true : false;
            if ($image) {
              var $output =
                '<img src="' +
                $image +
                '" alt="Avatar" class="rounded-circle border border-primary" width="48" height="48" style="object-fit: cover;">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output =
                '<span class="avatar-initial rounded-circle bg-label-' +
                $state +
                '" style="width: 48px; height: 48px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem;">' +
                $initials +
                '</span>';
            }
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-4">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="javascript:void(0);" class="text-heading text-truncate"><span class="fw-medium">' +
              $name +
              '</span></a>' +
              '<small class="text-muted">' +
              ($username ? '@' + $username : '') +
              '</small>' +
              '<small>' +
              $email +
              ' ' +
              ($emailVerified
                ? '<span class="text-success fs-tiny">(' + __('confirmed') + ')</span>'
                : '<span class="text-danger fs-tiny">(' + __('unconfirmed') + ')</span>') +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          targets: 3,
          render: function (data, type, full, meta) {
            var $role = full['role'] || 'Unknown';
            var roleBadgeObj = {
              admin: '<i class="ti ti-crown ti-md text-danger me-2"></i>',
              administrator: '<i class="ti ti-crown ti-md text-danger me-2"></i>',
              moderator: '<i class="ti ti-shield-check ti-md text-info me-2"></i>',
              author: '<i class="ti ti-edit ti-md text-warning me-2"></i>',
              editor: '<i class="ti ti-edit ti-md text-warning me-2"></i>',
              publisher: '<i class="ti ti-edit ti-md text-success me-2"></i>',
              member: '<i class="ti ti-user ti-md text-success me-2"></i>',
              user: '<i class="ti ti-user ti-md text-success me-2"></i>',
              guest: '<i class="ti ti-user ti-md text-secondary me-2"></i>',
              unknown: '<i class="ti ti-question-mark ti-md text-secondary me-2"></i>'
            };

            // Convert role to lowercase for matching
            var roleLower = $role.toLowerCase();
            var icon = roleBadgeObj[roleLower] || roleBadgeObj['unknown'];

            return "<span class='text-truncate d-flex align-items-center text-heading'>" + icon + $role + '</span>';
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var $reward_active = full['reward_system_active'];
            return (
              '<span class="badge ' +
              ($reward_active ? 'bg-label-success' : 'bg-label-secondary') +
              '">' +
              ($reward_active ? __('active') : __('inactive')) +
              '</span>'
            );
          }
        },
        {
          targets: 5,
          render: function (data, type, full, meta) {
            // Log actual data coming from server
            var statusValue = parseInt(full['status']);

            var badges = {
              0: { title: __('pending'), class: 'bg-label-warning' },
              1: { title: __('inactive'), class: 'bg-label-dark' },
              2: { title: __('active'), class: 'bg-label-info' }
            };

            // Varsayılan olarak aktif yap, diğer değerleri kontrol et
            var badge = badges[2]; // Default active

            if (statusValue === 0 || statusValue === '0') {
              badge = badges[0]; // Pending
            } else if (statusValue === 1 || statusValue === '1') {
              badge = badges[1]; // Inactive
            } else {
            }

            return '<span class="badge ' + badge.class + ' text-capitalized">' + badge.title + '</span>';
          }
        },
        {
          targets: 6,
          render: function (data, type, full, meta) {
            if (!data) return '--';

            // Moment.js kullanarak tarihi formatlama
            var date = moment(full['date']).format('DD MMM YYYY');
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          targets: 7,
          title: __('options'),
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-record me-1" data-id="' +
              full['id'] +
              '" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
              __('edit') +
              '"><i class="ti ti-edit ti-md text-primary"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record me-1" data-id="' +
              full['id'] +
              '" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
              __('delete') +
              '"><i class="ti ti-trash ti-md text-danger"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md text-secondary"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:;" class="dropdown-item update-profile" data-id="' +
              full['id'] +
              '"><i class="ti ti-user-circle me-1 text-info"></i>' +
              __('update_profile') +
              '</a>' +
              '<a href="javascript:;" class="dropdown-item confirm-email" data-id="' +
              full['id'] +
              '"><i class="ti ti-mail-check me-1 text-success"></i>' +
              __('confirm_user_email') +
              '</a>' +
              '<a href="javascript:;" class="dropdown-item ban-user" data-id="' +
              full['id'] +
              '" data-status="' +
              full['status'] +
              '">' +
              '<i class="ti ti-' +
              (parseInt(full['status']) === 2 ? 'user-off me-1 text-warning' : 'user-check me-1 text-success') +
              '"></i>' +
              (parseInt(full['status']) === 2 ? __('deactivate_user') : __('activate_user')) +
              '</a>' +
              '<a href="javascript:;" class="dropdown-item toggle-reward" data-id="' +
              full['id'] +
              '" data-reward="' +
              full['reward_system_active'] +
              '">' +
              '<i class="ti ti-trophy' +
              (full['reward_system_active'] ? '-off me-1 text-warning' : ' me-1 text-success') +
              '"></i>' +
              (full['reward_system_active'] ? __('disable_reward_system') : __('enable_reward_system')) +
              '</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [], // Varsayılan sıralama yok, backend sıralaması kullanılacak
      dom:
        '<"row"' +
        '<"col-md-6 d-flex align-items-center"<"ms-n2 me-2"l><"bulk-actions">>' +
        '<"col-md-6"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        class: 'form-control',
        search: '',
        searchPlaceholder: __('search'),
        info: __('table_pagination_info'),
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        },
        select: {
          rows: {
            _: '%d satır seçildi',
            0: '',
            1: '1 satır seçildi'
          }
        }
      },
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-2 ti-xs"></i>' + __('export'),
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>' + __('print'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2" ></i>' + __('csv'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>' + __('excel'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>' + __('pdf'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2" ></i>' + __('copy'),
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text:
            '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">' +
            __('add_user') +
            '</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addUserModal'
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return __('details_of') + ' ' + data['full_name'];
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
      },
      // YENİ: Satır seçme özelliğini etkinleştir (checkbox ile çoklu seçim)
      select: {
        style: 'multi',
        selector: 'td.dt-checkboxes-cell input.dt-checkboxes',
        info: true
      },
      initComplete: function () {
        // Remove small classes
        $('.dataTables_filter input').removeClass('form-control-sm');
        $('.dataTables_length select').removeClass('form-select-sm');

        // Bulk actions alanına seçim bilgisini ekle
        $('.bulk-actions').html(
          '<div class="selected-status d-none ms-2"><span class="select-status-text"></span></div>'
        );

        // Adding role filter
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="UserRole" class="form-select text-capitalize"><option value="">' +
                __('select_role') +
                '</option><option value="Admin">Admin</option><option value="Moderator">Moderator</option><option value="Author">Author</option><option value="Member">Member</option></select>'
            )
              .appendTo('.user_role')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });
          });
        // Adding reward system filter
        this.api()
          .columns(4)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="UserReward" class="form-select text-capitalize"><option value="">' +
                __('select_reward_status') +
                '</option><option value="' +
                __('active') +
                '">' +
                __('active') +
                '</option><option value="' +
                __('inactive') +
                '">' +
                __('inactive') +
                '</option></select>'
            )
              .appendTo('.user_reward')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });
          });
        // Adding status filter
        this.api()
          .columns(5)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="FilterTransaction" class="form-select text-capitalize"><option value="">' +
                __('select_status') +
                '</option><option value="' +
                __('pending') +
                '">' +
                __('pending') +
                '</option><option value="' +
                __('inactive') +
                '">' +
                __('inactive') +
                '</option><option value="' +
                __('active') +
                '">' +
                __('active') +
                '</option></select>'
            )
              .appendTo('.user_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });
          });

        // Add CSS for selected rows
        $('<style>')
          .prop('type', 'text/css')
          .html(
            `
            .datatables-users tbody tr.row_selected {
              background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
              color: var(--bs-primary) !important;
              box-shadow: inset 0 0 0 1px rgba(var(--bs-primary-rgb), 0.3);
            }
            .selected-status {
              background-color: rgba(var(--bs-primary-rgb), 0.08);
              color: var(--bs-primary);
              padding: 5px 12px;
              border-radius: 0.375rem;
              font-weight: 500;
            }
          `
          )
          .appendTo('head');
      }
    });

    // YENİ: Satır seçildiğinde ve seçim kaldırıldığında row_selected sınıfını yönet
    dt_user
      .on('select', function (e, dt, type, indexes) {
        if (type === 'row') {
          indexes.forEach(function (index) {
            $(dt_user.row(index).node()).addClass('row_selected');
          });

          // Seçilen satır sayısını güncelle
          var selectedCount = dt_user.rows({ selected: true }).count();
          var selectedRows = dt_user.rows({ selected: true }).nodes();
          var selectedStatus = $('.selected-status');

          if (selectedCount > 0) {
            selectedStatus.removeClass('d-none');

            // Tek satır seçildiyse "1 row selected", birden fazla seçildiyse "X rows selected" göster
            if (selectedCount === 1) {
              $('.select-status-text').html('1 satır seçildi');
            } else {
              $('.select-status-text').html(selectedCount + ' satır seçildi');
            }
          } else {
            selectedStatus.addClass('d-none');
          }
        }
      })
      .on('deselect', function (e, dt, type, indexes) {
        if (type === 'row') {
          indexes.forEach(function (index) {
            $(dt_user.row(index).node()).removeClass('row_selected');
          });

          // Seçilen satır sayısını güncelle
          var selectedCount = dt_user.rows({ selected: true }).count();
          var selectedStatus = $('.selected-status');

          if (selectedCount > 0) {
            selectedStatus.removeClass('d-none');

            // Tek satır seçildiyse "1 row selected", birden fazla seçildiyse "X rows selected" göster
            if (selectedCount === 1) {
              $('.select-status-text').html('1 satır seçildi');
            } else {
              $('.select-status-text').html(selectedCount + ' satır seçildi');
            }
          } else {
            selectedStatus.addClass('d-none');
          }
        }
      });
  };

  // DataTable başlatma
  if (dt_user_table.length) {
    if (window.translationsLoaded) {
      window.refreshUserTable();
    } else {
      window.addEventListener('translationsLoaded', function () {
        // Status nesnesi güncellensin
        window.refreshStatusObject = function () {
          return {
            0: { title: __('pending'), class: 'bg-label-warning' },
            1: { title: __('inactive'), class: 'bg-label-secondary' },
            2: { title: __('active'), class: 'bg-label-success' }
          };
        };
        window.refreshUserTable();
      });
    }
  }

  // Toastr Options
  toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: 'toast-bottom-center',
    preventDuplicates: true,
    onclick: null,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
  };

  // Add User button - Load roles when modal is opened
  $('.add-new').on('click', function () {
    loadRolesForAddModal();
  });

  // Load roles for add modal when page is ready
  function loadRolesForAddModal() {
    // Rolleri AJAX ile getir ve select'e ekle
    $.ajax({
      url: baseUrl + 'admin/roles',
      type: 'GET',
      success: function (response) {
        if (response.success) {
          const roleSelect = $('#add-user-role');
          roleSelect.empty(); // Mevcut seçenekleri temizle
          roleSelect.append('<option value="">' + __('select_role') + '</option>');

          // Rolleri ekle
          response.data.forEach(role => {
            roleSelect.append(`<option value="${role.id}">${role.name}</option>`);
          });
        } else {
          toastr.error(__('failed_to_load_roles'));
        }
      },
      error: function () {
        toastr.error(__('failed_to_load_roles'));
      }
    });
  }

  // Modal gösterildiğinde rolleri yükle
  $('#addUserModal').on('show.bs.modal', function () {
    loadRolesForAddModal();
  });

  // Edit Record
  $(document).on('click', '.edit-record', function () {
    var userId = $(this).data('id');

    // Get roles for select box
    $.ajax({
      url: baseUrl + 'admin/roles',
      type: 'GET',
      success: function (roleResponse) {
        if (roleResponse.success) {
          const roleSelect = $('#edit-user-role');
          roleSelect.empty(); // Mevcut seçenekleri temizle
          roleSelect.append('<option value="">' + __('select_role') + '</option>');

          // Rolleri ekle
          roleResponse.data.forEach(role => {
            roleSelect.append(`<option value="${role.id}">${role.name}</option>`);
          });

          // Fetch user data
          $.ajax({
            url: baseUrl + 'admin/users/' + userId + '/edit',
            type: 'GET',
            success: function (response) {
              if (response.success) {
                // Fill form with user data
                var user = response.data;

                $('#edit-user-id').val(user.id);
                $('#edit-user-fullname').val(user.name);
                $('#edit-user-username').val(user.username);
                $('#edit-user-email').val(user.email);
                $('#edit-user-role').val(user.role_id);

                // Status radio butonunu ayarla (status değerlerine dikkat et)
                const statusRadios = document.querySelectorAll('input[name="status"]');
                const userStatus = user.status !== undefined ? parseInt(user.status) : 2;

                // Önce tüm radio butonları temizle
                statusRadios.forEach(radio => {
                  radio.checked = false;
                });

                // Sonra uygun status değerini seç
                statusRadios.forEach(radio => {
                  const radioValue = parseInt(radio.value);

                  if (radioValue === userStatus) {
                    radio.checked = true;
                  }
                });

                $('#edit-user-reward').prop('checked', user.reward_system_active);

                // Clear password fields
                $('#edit-user-password').val('');
                $('#edit-user-confirm-password').val('');

                // Show modal
                $('#editUserModal').modal('show');
              } else {
                // Show error message
                toastr.error(__('failed_to_load_user_data'));
              }
            },
            error: function () {
              // Show error message
              toastr.error(__('failed_to_load_user_data'));
            }
          });
        } else {
          toastr.error(__('failed_to_load_roles'));
        }
      },
      error: function () {
        toastr.error(__('failed_to_load_roles'));
      }
    });
  });

  // Delete Record
  $(document).on('click', '.delete-record', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');

    // Confirm delete
    Swal.fire({
      title: __('are_you_sure'),
      text: __('action_cannot_be_undone'),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // Disable buttons to prevent multiple clicks
        Swal.showLoading();

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Delete user
        $.ajax({
          url: baseUrl + 'admin/users/' + userId,
          method: 'POST',
          data: {
            _token: csrfToken,
            _method: 'DELETE'
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          success: function (response) {
            if (response.success) {
              // Hide SweetAlert
              Swal.close();

              // Show success toast
              toastr.success(__('user_deleted_successfully'), __('success'));

              // Reload datatable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Hide SweetAlert
              Swal.close();

              // Show error toast
              toastr.error(response.message || __('user_deletion_failed'), __('error'));
            }
          },
          error: function (xhr) {
            // Hide SweetAlert
            Swal.close();

            // Show error toast
            toastr.error(__('user_deletion_failed'), __('error'));
          }
        });
      }
    });
  });

  // Update Profile - Redirect to profile page
  $(document).on('click', '.update-profile', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    window.location.href = baseUrl + 'admin/users/' + userId + '/profile';
  });

  // Confirm Email
  $(document).on('click', '.confirm-email', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');

    // Confirm email verification
    Swal.fire({
      title: __('verify_email'),
      text: __('verify_email_confirmation'),
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // Disable buttons to prevent multiple clicks
        Swal.showLoading();

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Verify email
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/verify-email',
          method: 'POST',
          data: {
            _token: csrfToken
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          success: function (response) {
            if (response.success) {
              // Hide SweetAlert
              Swal.close();

              // Show success toast
              toastr.success(response.message, __('success'));

              // Reload datatable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Hide SweetAlert
              Swal.close();

              // Show error toast
              toastr.error(response.message || __('email_verification_failed'), __('error'));
            }
          },
          error: function (xhr) {
            // Hide SweetAlert
            Swal.close();

            // Show error toast
            toastr.error(__('email_verification_failed'), __('error'));
          }
        });
      }
    });
  });

  // Toggle User Status (Activate/Deactivate)
  $(document).on('click', '.ban-user', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var currentStatus = parseInt($(this).data('status'));
    var isActivate = currentStatus !== 2; // Eğer 2 (aktif) değilse, aktifleştiriyoruz

    // Confirm status change
    Swal.fire({
      title: isActivate ? __('activate_user') : __('deactivate_user'),
      text: isActivate ? __('activate_user_confirmation') : __('deactivate_user_confirmation'),
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: isActivate ? 'btn btn-success' : 'btn btn-warning',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // Disable buttons to prevent multiple clicks
        Swal.showLoading();

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle user status
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/toggle-status',
          method: 'POST',
          data: {
            _token: csrfToken,
            status: isActivate ? 2 : 1 // 2 for active, 1 for inactive
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          success: function (response) {
            if (response.success) {
              // Hide SweetAlert
              Swal.close();

              // Show success toast
              toastr.success(
                response.message ||
                  (isActivate ? __('user_activated_successfully') : __('user_deactivated_successfully')),
                __('success')
              );

              // Reload datatable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Hide SweetAlert
              Swal.close();

              // Show error toast
              toastr.error(response.message || __('status_change_failed'), __('error'));
            }
          },
          error: function (xhr) {
            // Show error message
            Swal.fire({
              icon: 'error',
              title: __('error'),
              text: __('status_change_failed'),
              customClass: {
                confirmButton: 'btn btn-danger'
              }
            });
          }
        });
      }
    });
  });

  // Toggle User Status (Activate/Deactivate) - Artık kullanılmıyor, ban-user kullanıyoruz
  $(document).on('click', '.toggle-status', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var action = $(this).data('action');
    var isActivate = action === 'activate';

    // Confirm status change
    Swal.fire({
      title: isActivate ? __('activate_user') : __('deactivate_user'),
      text: isActivate ? __('activate_user_confirmation') : __('deactivate_user_confirmation'),
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: isActivate ? 'btn btn-success' : 'btn btn-warning',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // Disable buttons to prevent multiple clicks
        Swal.showLoading();

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle user status
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/toggle-status',
          method: 'POST',
          data: {
            _token: csrfToken,
            status: isActivate ? 2 : 1 // 2 for active, 1 for inactive
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          success: function (response) {
            if (response.success) {
              // Hide SweetAlert
              Swal.close();

              // Show success toast
              toastr.success(response.message, __('success'));

              // Reload datatable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Hide SweetAlert
              Swal.close();

              // Show error toast
              toastr.error(response.message || __('status_change_failed'), __('error'));
            }
          },
          error: function (xhr) {
            // Show error message
            Swal.fire({
              icon: 'error',
              title: __('error'),
              text: __('status_change_failed'),
              customClass: {
                confirmButton: 'btn btn-danger'
              }
            });
          }
        });
      }
    });
  });

  // Toggle Reward System (Activate/Deactivate)
  $(document).on('click', '.toggle-reward', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var currentReward = $(this).data('reward');
    var isActivate = !currentReward; // Boolean değeri tersini alıyoruz

    // Confirm reward system change
    Swal.fire({
      title: isActivate ? __('enable_reward_system') : __('disable_reward_system'),
      text: isActivate ? __('enable_reward_system_confirmation') : __('disable_reward_system_confirmation'),
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: isActivate ? 'btn btn-success' : 'btn btn-warning',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        // Disable buttons to prevent multiple clicks
        Swal.showLoading();

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle reward system
        $.ajax({
          url: baseUrl + 'admin/users/' + userId + '/toggle-reward',
          method: 'POST',
          data: {
            _token: csrfToken,
            reward_system_active: isActivate
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          success: function (response) {
            if (response.success) {
              // Hide SweetAlert
              Swal.close();

              // Show success toast
              toastr.success(response.message, __('success'));

              // Reload datatable
              $('.datatables-users').DataTable().ajax.reload(null, false);
            } else {
              // Hide SweetAlert
              Swal.close();

              // Show error toast
              toastr.error(response.message || __('reward_system_change_failed'), __('error'));
            }
          },
          error: function (xhr) {
            // Hide SweetAlert
            Swal.close();

            // Show error toast
            toastr.error(__('reward_system_change_failed'), __('error'));
          }
        });
      }
    });
  });
});
