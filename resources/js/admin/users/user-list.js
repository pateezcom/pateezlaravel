/**
 * Page User List - Handles the display and management of users with roles and permissions
 * Uses Vuexy template with Bootstrap 5, DataTable and Spatie Permission integration
 * 
 * Kullanıcılar Listesi Sayfası - Rol ve izinlerle kullanıcıların görüntülenmesi ve yönetimi
 * Vuexy şablonu, Bootstrap 5, DataTable ve Spatie Permission entegrasyonu kullanır
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
    1: { title: 'Pending', class: 'bg-label-warning' },
    2: { title: 'Active', class: 'bg-label-success' },
    3: { title: 'Inactive', class: 'bg-label-secondary' }
  };

  // Users datatable
  if (dt_user_table.length) {
    dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: baseUrl + 'admin/settings/user-list',
        error: function (xhr, error, thrown) {
          console.error('AJAX Error:', xhr.status, xhr.responseText);
          alert('Veri yüklenirken hata oluştu: ' + xhr.status);
        }
      },
      columns: [
        { data: 'id' },
        { data: 'id' },
        { data: 'full_name' },
        { data: 'role' },
        { data: 'reward_system_active' },
        { data: 'status' },
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
              $image = full['avatar'];
            if ($image) {
              var $output =
                '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-4">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="javascript:void(0);" class="text-heading text-truncate"><span class="fw-medium">' +
              $name +
              '</span></a>' +
              '<small class="text-muted">' + ($username ? '@' + $username : '') + '</small>' +
              '<small>' +
              $email +
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
            Admin: '<i class="ti ti-crown ti-md text-danger me-2"></i>',
            Moderator: '<i class="ti ti-shield-check ti-md text-info me-2"></i>',
            Author: '<i class="ti ti-edit ti-md text-warning me-2"></i>',
            Member: '<i class="ti ti-user ti-md text-success me-2"></i>',
            Unknown: '<i class="ti ti-question-mark ti-md text-secondary me-2"></i>'
            };
            return (
              "<span class='text-truncate d-flex align-items-center text-heading'>" +
              roleBadgeObj[$role] +
              $role +
              '</span>'
            );
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
              ($reward_active ? 'Active' : 'Inactive') +
              '</span>'
            );
          }
        },
        {
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['status'] || 2; // Default to 2 (Active) if status is undefined
            var statusObjValue = statusObj[$status] || { title: 'Active', class: 'bg-label-success' }; // Default value if status is not in statusObj
            return (
              '<span class="badge ' +
              statusObjValue.class +
              '" text-capitalized>' +
              statusObjValue.title +
              '</span>'
            );
          }
        },
        {
          targets: 6,
          title: 'Options',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-record me-1" data-id="' + full['id'] + '" data-bs-toggle="tooltip" data-bs-placement="top" title="' + 'Edit' + '"><i class="ti ti-edit ti-md"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record me-1" data-id="' + full['id'] + '" data-bs-toggle="tooltip" data-bs-placement="top" title="' + 'Delete' + '"><i class="ti ti-trash ti-md"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill permission-record me-1" data-id="' + full['id'] + '" data-bs-toggle="tooltip" data-bs-placement="top" title="' + 'Permissions' + '"><i class="ti ti-lock ti-md"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:;" class="dropdown-item">View Details</a>' +
              '<a href="javascript:;" class="dropdown-item">Suspend User</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"row"' +
        '<"col-md-2"<"ms-n2"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search User',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-2 ti-xs"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
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
              text: '<i class="ti ti-file-text me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
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
              text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
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
              text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
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
              text: '<i class="ti ti-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
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
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>',
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
              return 'Details of ' + data['full_name'];
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
      initComplete: function () {
        // Adding role filter
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="UserRole" class="form-select text-capitalize"><option value="">Select Role</option><option value="Admin">Admin</option><option value="Moderator">Moderator</option><option value="Author">Author</option><option value="Member">Member</option></select>'
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
              '<select id="UserReward" class="form-select text-capitalize"><option value="">Select Reward Status</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>'
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
              '<select id="FilterTransaction" class="form-select text-capitalize"><option value="">Select Status</option><option value="Pending">Pending</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>'
            )
              .appendTo('.user_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });
          });
      }
    });
  }

  // Add User button
  $('.add-new').on('click', function() {
    $('#addUserModal').modal('show');
  });

  // Edit Record
  $(document).on('click', '.edit-record', function() {
    var userId = $(this).data('id');
    
    // Fetch user data
    $.ajax({
      url: baseUrl + 'admin/users/' + userId + '/edit',
      type: 'GET',
      success: function(response) {
        if (response.success) {
          // Fill form with user data
          var user = response.data;
          
          $('#edit-user-id').val(user.id);
          $('#edit-user-fullname').val(user.name);
          $('#edit-user-username').val(user.username);
          $('#edit-user-email').val(user.email);
          $('#edit-user-role').val(user.role);
          $('#edit-user-status').val(user.status || 2);
          $('#edit-user-reward').prop('checked', user.reward_system_active);
          
          // Clear password fields
          $('#edit-user-password').val('');
          $('#edit-user-confirm-password').val('');
          
          // Show modal
          $('#editUserModal').modal('show');
        } else {
          // Show error message
          toastr.error('Failed to load user data');
        }
      },
      error: function() {
        // Show error message
        toastr.error('Failed to load user data');
      }
    });
  });

  // Permission Record
  $(document).on('click', '.permission-record', function() {
    var userId = $(this).data('id');
    
    // Fetch user permissions
    $.ajax({
      url: baseUrl + 'admin/users/' + userId + '/permissions/edit',
      type: 'GET',
      success: function(response) {
        if (response.success) {
          // Set user ID
          $('#permission-user-id').val(userId);
          
          // Reset all checkboxes
          $('input[name="permissions[]"]').prop('checked', false);
          $('#selectAll').prop('checked', false);
          
          // Set user permissions
          var permissions = response.data;
          permissions.forEach(function(permission) {
            $('input[name="permissions[]"][value="' + permission + '"]').prop('checked', true);
          });
          
          // Show modal
          $('#editPermissionModal').modal('show');
        } else {
          // Show error message
          toastr.error('Failed to load user permissions');
        }
      },
      error: function() {
        // Show error message
        toastr.error('Failed to load user permissions');
      }
    });
  });

  // Delete Record
  $(document).on('click', '.delete-record', function() {
    var userId = $(this).data('id');
    
    // Confirm delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-outline-danger ms-1'
      },
      buttonsStyling: false
    }).then(function(result) {
      if (result.isConfirmed) {
        // Delete user
        $.ajax({
          url: baseUrl + 'admin/users/' + userId,
          type: 'DELETE',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              // Show success message
              toastr.success('User deleted successfully');
              
              // Reload table
              dt_user.ajax.reload();
            } else {
              // Show error message
              toastr.error(response.message || 'An error occurred while deleting the user');
            }
          },
          error: function() {
            // Show error message
            toastr.error('An error occurred while deleting the user');
          }
        });
      }
    });
  });

  // Filter form control to default size
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
