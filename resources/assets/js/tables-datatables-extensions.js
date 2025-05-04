/**
 * DataTables Extensions (jquery)
 */

'use strict';

$(function () {
  var dt_scrollable_table = $('.dt-scrollableTable'),
    dt_fixedheader_table = $('.dt-fixedheader'),
    dt_fixedcolumns_table = $('.dt-fixedcolumns'),
    dt_select_table = $('.datatables-users'); // user-list.blade.php'deki tablo

  // Select (datatables-users)
  // --------------------------------------------------------------------
  if (dt_select_table.length) {
    var dt_select = dt_select_table.DataTable({
      ajax: baseUrl + 'admin/users', // İkinci blade'deki AJAX endpoint
      columns: [
        { data: '' }, // Responsive kontrol sütunu
        { data: 'id' }, // Checkbox sütunu
        { data: 'full_name' },
        { data: 'role' },
        { data: 'reward_system_active' },
        { data: 'status' },
        { data: 'date' },
        { data: 'action' }
      ],
      columnDefs: [
        {
          // Responsive kontrol sütunu
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
          // Checkbox'lar
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
          // Kullanıcı adı ve email
          targets: 2,
          render: function (data, type, full) {
            var $name = full['full_name'],
              $email = full['email'];
            return (
              '<div class="d-flex align-items-center">' +
              '<div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-primary">U</span></div>' +
              '<div><span class="fw-medium">' +
              $name +
              '</span><br><small>' +
              $email +
              '</small></div>' +
              '</div>'
            );
          }
        },
        {
          // Rol
          targets: 3,
          render: function (data) {
            return '<span>' + (data || 'Unknown') + '</span>';
          }
        },
        {
          // Ödül sistemi
          targets: 4,
          render: function (data) {
            return (
              '<span class="badge ' +
              (data ? 'bg-label-success' : 'bg-label-secondary') +
              '">' +
              (data ? 'Active' : 'Inactive') +
              '</span>'
            );
          }
        },
        {
          // Durum
          targets: 5,
          render: function (data) {
            var badges = {
              0: { title: 'Pending', class: 'bg-label-warning' },
              1: { title: 'Inactive', class: 'bg-label-dark' },
              2: { title: 'Active', class: 'bg-label-info' }
            };
            var badge = badges[data] || badges[2];
            return '<span class="badge ' + badge.class + '">' + badge.title + '</span>';
          }
        },
        {
          // Tarih
          targets: 6,
          render: function (data) {
            return data ? moment(data).format('DD MMM YYYY') : '--';
          }
        },
        {
          // İşlemler
          targets: 7,
          searchable: false,
          orderable: false,
          render: function (data, type, full) {
            return (
              '<div class="d-flex">' +
              '<a href="javascript:;" class="btn btn-icon edit-record" data-id="' +
              full['id'] +
              '"><i class="ti ti-edit"></i></a>' +
              '<a href="javascript:;" class="btn btn-icon delete-record" data-id="' +
              full['id'] +
              '"><i class="ti ti-trash"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language: {
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      select: {
        style: 'multi',
        selector: 'td:nth-child(2) input.dt-checkboxes' // Checkbox'lar ikinci sütunda
      },
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column'
        }
      }
    });

    // Satır seçildiğinde alert göster ve row_selected sınıfı ekle
    dt_select.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        let rowData = dt_select.rows(indexes).data().toArray();
        alert('Satır seçildi! Seçilen veri: ' + JSON.stringify(rowData));
        indexes.forEach(function (index) {
          $(dt_select.row(index).node()).addClass('row_selected');
        });
      }
    });

    // Satır seçimi kaldırıldığında row_selected sınıfını kaldır
    dt_select.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        indexes.forEach(function (index) {
          $(dt_select.row(index).node()).removeClass('row_selected');
        });
      }
    });
  }

  // Filter form control to default size
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 200);
});
