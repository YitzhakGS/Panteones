function loader() {
  $(window).on("load", function () {
    $("#ctn-preloader").addClass("loaded"); // Una vez haya terminado el preloader aparezca el scroll

    if ($("#ctn-preloader").hasClass("loaded")) {
      // Es para que una vez que se haya ido el preloader se elimine toda la seccion preloader
      $("#preloader").delay(700).queue(function () {
        $(this).remove();
      });
    }
  });
}

loader();
