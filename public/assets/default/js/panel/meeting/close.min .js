

!function(e) {
  "use strict";
  e("body").on("click", ".js-finish-meeting-reserve", function(t) {
    t.preventDefault();
    var i = e(this).attr("data-id"),
      n = '<div class="">\n    <p class="">' + finishReserveHint + '</p>\n    <div class="mt-30 d-flex align-items-center justify-content-center">\n        <button type="button" id="finishReserve" data-href="' + ("/panel/meetings/" + i + "/finish") + '" class="btn btn-sm btn-primary">' + finishReserveConfirm + '</button>\n        <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">' + finishReserveCancel + "</button>\n    </div>\n</div>";
    Swal.fire({
      title: finishReserveTitle,
      html: n,
      icon: "warning",
      showConfirmButton: !1,
      showCancelButton: !1,
      allowOutsideClick: function() {
        return !Swal.isLoading();
      }
    });
  }), e("body").on("click", "#finishReserve", function(t) {
    t.preventDefault();
    var i = e(this),
      n = i.attr("data-href");
    i.addClass("loadingbar primary").prop("disabled", !0), e.get(n, function(e) {
      e && 200 === e.code ? (Swal.fire({
        title: finishReserveSuccess,
        text: finishReserveSuccessHint,
        showConfirmButton: !1,
        icon: "success"
      }), setTimeout(function() {
        // Arahkan ke /panel/diary/create saat berhasil
        window.location.href = '/panel/diary';
      }, 1e3)) : Swal.fire({
        title: finishReserveFail,
        text: finishReserveFailHint,
        icon: "error"
      })
    }).error(function(e) {
      Swal.fire({
        title: finishReserveFail,
        text: finishReserveFailHint,
        icon: "error"
      })
    }).always(function() {
      i.removeClass("loadingbar primary").prop("disabled", !1)
    })
  });

  // ... (kode lainnya)
}(jQuery);
