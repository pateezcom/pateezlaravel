/**
 * Pateez Haber - Uygulama Yardımcı Fonksiyonları
 *
 * Toast mesajları ve alert mesajları için standardizasyon sağlar
 */

'use strict';

// Uygulama yardımcıları namespace
window.AppHelpers = window.AppHelpers || {};

// Toast ve Alert Mesajları için standardize edilmiş yardımcı
AppHelpers.Messages = {
  // Toastr ayarları
  toastrDefaults: {
    closeButton: true,
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
  },

  // Tüm toast mesajları için başlatma
  init: function () {
    if (typeof toastr !== 'undefined') {
      toastr.options = this.toastrDefaults;
    }
  },

  // Başarı mesajı göster
  showSuccess: function (message, title, callback) {
    title = title || __('success');
    this._showToast('success', message, title, callback);
    return this;
  },

  // Hata mesajı göster
  showError: function (message, title, callback) {
    title = title || __('error');
    this._showToast('error', message, title, callback);
    return this;
  },

  // Bilgi mesajı göster
  showInfo: function (message, title, callback) {
    title = title || __('info');
    this._showToast('info', message, title, callback);
    return this;
  },

  // Uyarı mesajı göster
  showWarning: function (message, title, callback) {
    title = title || __('warning');
    this._showToast('warning', message, title, callback);
    return this;
  },

  // Yenileme işlemleri için mesaj göster (sayfa yenilemeden önce bekler)
  showWithReload: function (type, message, title, delay) {
    delay = delay || 1500;

    return new Promise(resolve => {
      this._showToast(type, message, title, function () {
        setTimeout(function () {
          resolve();
          window.location.reload();
        }, delay);
      });
    });
  },

  // Yönlendirme işlemleri için mesaj göster (yönlendirmeden önce bekler)
  showWithRedirect: function (type, message, title, url, delay) {
    delay = delay || 1500;

    return new Promise(resolve => {
      this._showToast(type, message, title, function () {
        setTimeout(function () {
          resolve();
          window.location.href = url;
        }, delay);
      });
    });
  },

  // SweetAlert2 onay kutusu göster
  showConfirm: function (options) {
    const defaults = {
      title: __('are_you_sure'),
      text: __('action_cannot_be_undone'),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: __('yes'),
      cancelButtonText: __('cancel'),
      customClass: {
        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    };

    const settings = Object.assign({}, defaults, options);
    return Swal.fire(settings);
  },

  // SweetAlert2 başarı kutusu göster
  showSuccessAlert: function (title, text, buttonText) {
    return Swal.fire({
      icon: 'success',
      title: title || __('success'),
      text: text || '',
      confirmButtonText: buttonText || __('ok'),
      customClass: {
        confirmButton: 'btn btn-success waves-effect waves-light'
      },
      buttonsStyling: false
    });
  },

  // SweetAlert2 hata kutusu göster
  showErrorAlert: function (title, text, buttonText) {
    return Swal.fire({
      icon: 'error',
      title: title || __('error'),
      text: text || '',
      confirmButtonText: buttonText || __('ok'),
      customClass: {
        confirmButton: 'btn btn-danger waves-effect waves-light'
      },
      buttonsStyling: false
    });
  },

  // Private: Toast mesajını göster
  _showToast: function (type, message, title, callback) {
    if (typeof toastr === 'undefined') {
      // Toastr yoksa SweetAlert2 kullan
      Swal.fire({
        icon: type,
        title: title,
        text: message,
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didClose: function () {
          if (typeof callback === 'function') {
            callback();
          }
        }
      });
      return;
    }

    // Callback fonksiyonu varsa
    if (typeof callback === 'function') {
      toastr.options.onHidden = callback;
    }

    // Toast mesajını göster
    toastr[type](message, title);
  }
};

// Sayfa yüklendiğinde toast ayarlarını başlat
document.addEventListener('DOMContentLoaded', function () {
  AppHelpers.Messages.init();
});
