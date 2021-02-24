(function ($) {
  showSuccessToast = function (textShow, url = "") {
    // 'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Sukses',
      // text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
      text: textShow,
      showHideTransition: 'plain',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'top-right',
      hideAfter: 750,
      afterHidden: function () {
        if (url != "") {
          if (url == "self") {
            window.location.reload();
          } else {
            window.location = url;
          }
        }
      },
    })
  };

  showDangerToast = function (textShow) {
    // 'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Danger',
      text: textShow,
      showHideTransition: 'plain',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };

  showInfoToast = function () {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Info',
      text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
      showHideTransition: 'plain',
      icon: 'info',
      loaderBg: '#46c35f',
      position: 'top-right'
    })
  };

  showWarningToast = function () {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
      showHideTransition: 'plain',
      icon: 'warning',
      loaderBg: '#57c7d4',
      position: 'top-right'
    })
  };

  showToastPosition = function (position) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      position: String(position),
      icon: 'info',
      stack: false,
      loaderBg: '#f96868'
    })
  }

  showToastInCustomPosition = function () {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Custom positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      icon: 'info',
      position: {
        left: 120,
        top: 120
      },
      stack: false,
      loaderBg: '#f96868'
    })
  }

  resetToastPosition = function () {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    }); //to remove previous position style
  }
})(jQuery);