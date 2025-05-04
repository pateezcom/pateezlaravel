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

  // Status object
  var statusObj = {
    0: { title: __('pending'), class: 'bg-label-warning' },
    1: { title: __('inactive'), class: 'bg-label-secondary' },
    2: { title: __('active'), class: 'bg-label-success' }
  };

  // Users datatable
  if (dt_user_table.length) {
    window.addEventListener('translationsLoaded', function () {
      dt_user = dt_user_table.DataTable({
        processing: true,
        serverSide: false,
        ajax: {
          url: baseUrl + 'admin/users',
          error: function (xhr, error, thrown) {
            alert(__('data_anticipation_error') + ': ' + xhr.status);
          }
        },
        columns: [
          { data: '' },
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
            render: function () {
              return '';
            }
          },
          {
            targets: 1,
            searchable: false,
            orderable: false,
            render: function () {
              return '<input type="checkbox" class="dt-checkboxes form-check-input">';
            },
            checkboxes: {
              selectRow: true,
              selectAllRender: '<input type="checkbox" class="form-check-input">'
            }
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
              var statusValue = parseInt(full['status']);
              var badge = statusObj[statusValue] || statusObj[2];
              return '<span class="badge ' + badge.class + ' text-capitalized">' + badge.title + '</span>';
            }
          },
          {
            targets: 6,
            render: function (data, type, full, meta) {
              if (!data) return '--';
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
        order: [[2, 'desc']],
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
            rows: {}
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
                exportOptions: { columns: [1, 2, 3, 4, 5, 6] }
              },
              {
                extend: 'csv',
                text: '<i class="ti ti-file-text me-2" ></i>' + __('csv'),
                className: 'dropdown-item',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6] }
              },
              {
                extend: 'excel',
                text: '<i class="ti ti-file-spreadsheet me-2"></i>' + __('excel'),
                className: 'dropdown-item',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6] }
              },
              {
                extend: 'pdf',
                text: '<i class="ti ti-file-code-2 me-2"></i>' + __('pdf'),
                className: 'dropdown-item',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6] }
              },
              {
                extend: 'copy',
                text: '<i class="ti ti-copy me-2" ></i>' + __('copy'),
                className: 'dropdown-item',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6] }
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
        select: {
          style: 'multi',
          selector: 'td:nth-child(2) input.dt-checkboxes'
        },
        initComplete: function () {
          $('.dataTables_filter .form-control').removeClass('form-control-sm');
          $('.dataTables_length .form-select').removeClass('form-select-sm');
          $('.bulk-actions').html(
            '<div class="selected-status d-none">' +
              '<span class="select-status-text"></span>' +
              '<button class="btn btn-sm btn-danger delete-selected ms-2" style="display: none;">' +
              __('delete_selected') +
              '</button>' +
              '</div>'
          );
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

          // CSS for selected rows
          $('<style>')
            .prop('type', 'text/css')
            .html(
              `
            .datatables-users tbody tr.row_selected {
  background-color: transparent !important;
}
            .selected-status {

            }
            .delete-selected {
              font-size: 0.875rem;
            }
            `
            )
            .appendTo('head');
        }
      });

      // Satır seçildiğinde row_selected sınıfı ekle ve silme butonunu göster
      dt_user.on('select', function (e, dt, type, indexes) {
        if (type === 'row') {
          indexes.forEach(function (index) {
            $(dt_user.row(index).node()).addClass('row_selected');
          });

          // Seçilen satır sayısını güncelle
          var selectedCount = dt_user.rows({ selected: true }).count();
          var selectedStatus = $('.selected-status');
          var deleteButton = $('.delete-selected');
          if (selectedCount > 0) {
            selectedStatus.removeClass('d-none');
            deleteButton.show();
          } else {
            selectedStatus.addClass('d-none');
            deleteButton.hide();
          }
        }
      });

      // Satır seçimi kaldırıldığında row_selected sınıfını kaldır ve silme butonunu gizle
      dt_user.on('deselect', function (e, dt, type, indexes) {
        if (type === 'row') {
          indexes.forEach(function (index) {
            $(dt_user.row(index).node()).removeClass('row_selected');
          });

          // Seçilen satır sayısını güncelle
          var selectedCount = dt_user.rows({ selected: true }).count();
          var selectedStatus = $('.selected-status');
          var deleteButton = $('.delete-selected');
          if (selectedCount > 0) {
            selectedStatus.removeClass('d-none');
            deleteButton.show();
          } else {
            selectedStatus.addClass('d-none');
            deleteButton.hide();
          }
        }
      });

      // Seçili satırları silme işlemi
      $(document).on('click', '.delete-selected', function (e) {
        e.preventDefault();
        var selectedRows = dt_user.rows({ selected: true }).data().toArray();
        var selectedIds = selectedRows.map(row => row.id);

        if (selectedIds.length === 0) {
          if (typeof AppHelpers !== 'undefined' && typeof AppHelpers.Messages !== 'undefined') {
            AppHelpers.Messages.showWarning(__('no_rows_selected'));
          } else {
            toastr.warning(__('no_rows_selected'), __('warning'));
          }
          return;
        }

        // Kullanıcı arayüzünde seçili satır sayısını göster
        var confirmMessage = selectedIds.length + ' ' + __('selected_rows_will_be_deleted');

        // AppHelpers.Messages.showConfirm varsa onu kullan, yoksa SweetAlert2 kullan
        if (
          typeof AppHelpers !== 'undefined' &&
          typeof AppHelpers.Messages !== 'undefined' &&
          typeof AppHelpers.Messages.showConfirm === 'function'
        ) {
          AppHelpers.Messages.showConfirm({
            title: __('are_you_sure'),
            text: confirmMessage,
            icon: 'warning',
            confirmButtonText: __('yes'),
            cancelButtonText: __('cancel')
          }).then(function (result) {
            if (result.isConfirmed) {
              deleteSelectedUsers(selectedIds);
            }
          });
        } else {
          // AppHelpers yoksa SweetAlert2 kullan
          Swal.fire({
            title: __('are_you_sure'),
            text: confirmMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: __('yes'),
            cancelButtonText: __('cancel'),
            customClass: {
              confirmButton: 'btn btn-danger me-3',
              cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              deleteSelectedUsers(selectedIds);
            }
          });
        }
      });

      // Seçili kullanıcıları silen yardımcı fonksiyon
      function deleteSelectedUsers(selectedIds) {
        Swal.showLoading();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!csrfToken) {
          if (typeof AppHelpers !== 'undefined' && typeof AppHelpers.Messages !== 'undefined') {
            AppHelpers.Messages.showError(__('csrf_token_missing'));
          } else {
            toastr.error(__('csrf_token_missing'), __('error'));
          }
          Swal.close();
          return;
        }

        $.ajax({
          url: baseUrl + 'admin/users/bulk-delete',
          method: 'POST',
          data: {
            _token: csrfToken,
            ids: selectedIds
          },
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          success: function (response) {
            if (response.success) {
              Swal.close();
              if (typeof AppHelpers !== 'undefined' && typeof AppHelpers.Messages !== 'undefined') {
                AppHelpers.Messages.showSuccess(__('selected_users_deleted_successfully'));
              } else {
                toastr.success(__('selected_users_deleted_successfully'), __('success'));
              }
              dt_user.ajax.reload(null, false);
              $('.selected-status').addClass('d-none');
              $('.delete-selected').hide();
            } else {
              Swal.close();
              if (typeof AppHelpers !== 'undefined' && typeof AppHelpers.Messages !== 'undefined') {
                AppHelpers.Messages.showError(response.message || __('bulk_deletion_failed'));
              } else {
                toastr.error(response.message || __('bulk_deletion_failed'), __('error'));
              }
            }
          },
          error: function (xhr) {
            Swal.close();
            if (typeof AppHelpers !== 'undefined' && typeof AppHelpers.Messages !== 'undefined') {
              AppHelpers.Messages.showError(__('bulk_deletion_failed'));
            } else {
              toastr.error(__('bulk_deletion_failed'), __('error'));
            }
          }
        });
      }
    });
  }

  // Toastr Options - Bu konfigürasyonu kaldırabiliriz, artık AppHelpers.Messages sınıfı kullanıyoruz
  // Geriye dönük uyumluluk için bırakıyoruz ama projede AppHelpers.Messages kullanmalıyız
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

  // Add User button
  $('.add-new').on('click', function () {
    loadRolesForAddModal();
  });

  // Load roles for add modal
  function loadRolesForAddModal() {
    $.ajax({
      url: baseUrl + 'admin/roles',
      type: 'GET',
      success: function (response) {
        if (response.success) {
          const roleSelect = $('#add-user-role');
          roleSelect.empty();
          roleSelect.append('<option value="">' + __('select_role') + '</option>');
          response.data.forEach(role => {
            roleSelect.append(`<option value="${role.id}">${role.name}</option>`);
          });
        } else {
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(__('failed_to_load_roles'));
          } else {
            toastr.error(__('failed_to_load_roles'));
          }
        }
      },
      error: function () {
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(__('failed_to_load_roles'));
        } else {
          toastr.error(__('failed_to_load_roles'));
        }
      }
    });
  }

  $('#addUserModal').on('show.bs.modal', function () {
    loadRolesForAddModal();
  });

  // Edit Record
  $(document).on('click', '.edit-record', function () {
    var userId = $(this).data('id');
    $.ajax({
      url: baseUrl + 'admin/roles',
      type: 'GET',
      success: function (roleResponse) {
        if (roleResponse.success) {
          const roleSelect = $('#edit-user-role');
          roleSelect.empty();
          roleSelect.append('<option value="">' + __('select_role') + '</option>');
          roleResponse.data.forEach(role => {
            roleSelect.append(`<option value="${role.id}">${role.name}</option>`);
          });
          $.ajax({
            url: baseUrl + 'admin/users/' + userId + '/edit',
            type: 'GET',
            success: function (response) {
              if (response.success) {
                var user = response.data;
                $('#edit-user-id').val(user.id);
                $('#edit-user-fullname').val(user.name);
                $('#edit-user-username').val(user.username);
                $('#edit-user-email').val(user.email);
                $('#edit-user-role').val(user.role_id);
                const statusRadios = document.querySelectorAll('input[name="status"]');
                const userStatus = user.status !== undefined ? parseInt(user.status) : 2;
                statusRadios.forEach(radio => {
                  radio.checked = false;
                });
                statusRadios.forEach(radio => {
                  if (parseInt(radio.value) === userStatus) {
                    radio.checked = true;
                  }
                });
                $('#edit-user-reward').prop('checked', user.reward_system_active);
                $('#edit-user-password').val('');
                $('#edit-user-confirm-password').val('');
                $('#editUserModal').modal('show');
              } else {
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showError(__('failed_to_load_user_data'));
                } else {
                  toastr.error(__('failed_to_load_user_data'));
                }
              }
            },
            error: function () {
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(__('failed_to_load_user_data'));
              } else {
                toastr.error(__('failed_to_load_user_data'));
              }
            }
          });
        } else {
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(__('failed_to_load_roles'));
          } else {
            toastr.error(__('failed_to_load_roles'));
          }
        }
      },
      error: function () {
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(__('failed_to_load_roles'));
        } else {
          toastr.error(__('failed_to_load_roles'));
        }
      }
    });
  });

  // Delete Record
  $(document).on('click', '.delete-record', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');

    // AppHelpers yoksa doğrudan SweetAlert2 kullan
    if (
      typeof AppHelpers !== 'undefined' &&
      typeof AppHelpers.Messages !== 'undefined' &&
      typeof AppHelpers.Messages.showConfirm === 'function'
    ) {
      AppHelpers.Messages.showConfirm({
        title: __('are_you_sure'),
        text: __('action_cannot_be_undone'),
        icon: 'warning',
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel')
      }).then(function (result) {
        if (result.isConfirmed) {
          deleteUserRecord(userId);
        }
      });
    } else {
      // AppHelpers yoksa SweetAlert2 kullan
      Swal.fire({
        title: __('are_you_sure'),
        text: __('action_cannot_be_undone'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel'),
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          deleteUserRecord(userId);
        }
      });
    }
  });

  // Kullanıcı silme işlemini gerçekleştiren yardımcı fonksiyon
  function deleteUserRecord(userId) {
    Swal.showLoading();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!csrfToken) {
      console.error('CSRF token bulunamadı!');
      if (typeof AppHelpers !== 'undefined') {
        AppHelpers.Messages.showError(__('csrf_token_missing'));
      } else {
        toastr.error(__('csrf_token_missing'), __('error'));
      }
      Swal.close();
      return;
    }

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
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showSuccess(__('user_deleted_successfully'));
          } else {
            toastr.success(__('user_deleted_successfully'), __('success'));
          }
          dt_user.ajax.reload(null, false);
        } else {
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(response.message || __('user_deletion_failed'));
          } else {
            toastr.error(response.message || __('user_deletion_failed'), __('error'));
          }
        }
      },
      error: function (xhr) {
        console.error('Silme hatası:', xhr.responseText);
        Swal.close();
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(__('user_deletion_failed'));
        } else {
          toastr.error(__('user_deletion_failed'), __('error'));
        }
      }
    });
  }

  // Update Profile
  $(document).on('click', '.update-profile', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    window.location.href = baseUrl + 'admin/users/' + userId + '/profile';
  });

  // Confirm Email
  $(document).on('click', '.confirm-email', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    if (typeof AppHelpers !== 'undefined') {
      AppHelpers.Messages.showConfirm({
        title: __('verify_email'),
        text: __('verify_email_confirmation'),
        icon: 'warning',
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel')
      }).then(function (result) {
        if (result.isConfirmed) {
          Swal.showLoading();
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          $.ajax({
            url: baseUrl + 'admin/users/' + userId + '/resend-verification',
            method: 'POST',
            data: {
              _token: csrfToken
            },
            headers: {
              'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
              if (response.success) {
                Swal.close();
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showSuccess(response.message);
                } else {
                  toastr.success(response.message, __('success'));
                }
                dt_user.ajax.reload(null, false);
              } else {
                if (typeof AppHelpers !== 'undefined') {
                  AppHelpers.Messages.showError(response.message || __('email_verification_failed'));
                } else {
                  toastr.error(response.message || __('email_verification_failed'), __('error'));
                }
              }
            },
            error: function () {
              if (typeof AppHelpers !== 'undefined') {
                AppHelpers.Messages.showError(__('email_verification_failed'));
              } else {
                toastr.error(__('email_verification_failed'), __('error'));
              }
            }
          });
        }
      });
    }
  });

  // Toggle User Status
  $(document).on('click', '.ban-user', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var currentStatus = parseInt($(this).data('status'));

    // Status değerine göre aktif/pasif durumunu belirle
    var isCurrentlyActive = currentStatus === 2; // 2 = active
    var newStatus = isCurrentlyActive ? 1 : 2; // Tersine çevir

    // AppHelpers kontrolü ve alternatif yöntem
    if (
      typeof AppHelpers !== 'undefined' &&
      typeof AppHelpers.Messages !== 'undefined' &&
      typeof AppHelpers.Messages.showConfirm === 'function'
    ) {
      AppHelpers.Messages.showConfirm({
        title: isCurrentlyActive ? __('deactivate_user') : __('activate_user'),
        text: isCurrentlyActive ? __('deactivate_user_confirmation') : __('activate_user_confirmation'),
        icon: 'warning',
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel')
      }).then(function (result) {
        if (result.isConfirmed) {
          toggleUserStatusFixed(userId, newStatus);
        }
      });
    } else {
      // AppHelpers yoksa SweetAlert2 kullan
      Swal.fire({
        title: isCurrentlyActive ? __('deactivate_user') : __('activate_user'),
        text: isCurrentlyActive ? __('deactivate_user_confirmation') : __('activate_user_confirmation'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel'),
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          toggleUserStatusFixed(userId, newStatus);
        }
      });
    }
  });

  // Kullanıcı durumunu değiştiren düzeltilmiş yardımcı fonksiyon
  function toggleUserStatusFixed(userId, newStatus) {
    Swal.showLoading();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // URL'i düzeltiyoruz - Rota "toggle-status" olmalı "update-status" değil
    const requestUrl = baseUrl + 'admin/users/' + userId + '/toggle-status';

    if (!csrfToken) {
      if (typeof AppHelpers !== 'undefined') {
        AppHelpers.Messages.showError(__('csrf_token_missing'));
      } else {
        toastr.error(__('csrf_token_missing'), __('error'));
      }
      Swal.close();
      return;
    }

    $.ajax({
      url: requestUrl,
      method: 'POST',
      data: {
        _token: csrfToken,
        status: newStatus
      },
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      success: function (response) {
        if (response.success) {
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showSuccess(
              response.message ||
                (newStatus === 2 ? __('user_activated_successfully') : __('user_deactivated_successfully')),
              __('success')
            );
          } else {
            toastr.success(
              response.message ||
                (newStatus === 2 ? __('user_activated_successfully') : __('user_deactivated_successfully')),
              __('success')
            );
          }
          dt_user.ajax.reload(null, false);
        } else {
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(response.message || __('status_change_failed'));
          } else {
            toastr.error(response.message || __('status_change_failed'), __('error'));
          }
        }
      },
      error: function (xhr) {
        Swal.close();
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(__('status_change_failed'));
        } else {
          toastr.error(__('status_change_failed'), __('error'));
        }
      }
    });
  }

  // Toggle Reward System
  $(document).on('click', '.toggle-reward', function (e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var currentReward = $(this).data('reward');
    var isActivate = !currentReward;

    // AppHelpers kontrolü ve alternatif yöntem
    if (
      typeof AppHelpers !== 'undefined' &&
      typeof AppHelpers.Messages !== 'undefined' &&
      typeof AppHelpers.Messages.showConfirm === 'function'
    ) {
      AppHelpers.Messages.showConfirm({
        title: isActivate ? __('enable_reward_system') : __('disable_reward_system'),
        text: isActivate ? __('enable_reward_system_confirmation') : __('disable_reward_system_confirmation'),
        icon: 'warning',
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel')
      }).then(function (result) {
        if (result.isConfirmed) {
          toggleRewardSystem(userId, isActivate);
        }
      });
    } else {
      // AppHelpers yoksa SweetAlert2 kullan
      Swal.fire({
        title: isActivate ? __('enable_reward_system') : __('disable_reward_system'),
        text: isActivate ? __('enable_reward_system_confirmation') : __('disable_reward_system_confirmation'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('yes'),
        cancelButtonText: __('cancel'),
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          toggleRewardSystem(userId, isActivate);
        }
      });
    }
  });

  // Ödül sistemini değiştiren yardımcı fonksiyon
  function toggleRewardSystem(userId, isActivate) {
    Swal.showLoading();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const requestUrl = baseUrl + 'admin/users/' + userId + '/toggle-reward';

    if (!csrfToken) {
      if (typeof AppHelpers !== 'undefined') {
        AppHelpers.Messages.showError(__('csrf_token_missing'));
      } else {
        toastr.error(__('csrf_token_missing'), __('error'));
      }
      Swal.close();
      return;
    }

    $.ajax({
      url: requestUrl,
      method: 'POST',
      data: {
        _token: csrfToken,
        reward_system_active: isActivate ? 1 : 0 // Parametre adını 'reward_system_active' olarak değiştirdik
      },
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      success: function (response) {
        if (response.success) {
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showSuccess(response.message);
          } else {
            toastr.success(response.message, __('success'));
          }
          dt_user.ajax.reload(null, false);
        } else {
          Swal.close();
          if (typeof AppHelpers !== 'undefined') {
            AppHelpers.Messages.showError(response.message || __('reward_system_change_failed'));
          } else {
            toastr.error(response.message || __('reward_system_change_failed'), __('error'));
          }
        }
      },
      error: function (xhr) {
        Swal.close();
        if (typeof AppHelpers !== 'undefined') {
          AppHelpers.Messages.showError(__('reward_system_change_failed'));
        } else {
          toastr.error(__('reward_system_change_failed'), __('error'));
        }
      }
    });
  }
});
