(function () {
  var quickCache = {};

  function setArrows($scope) {
    // задаваме буквени стрелки за всички slick инстанции в $scope
    $scope.find(".slick-prev").html("❮");
    $scope.find(".slick-next").html("❯");
  }

  function hydrateIn($scope) {
    if (typeof $.fn.slick === "undefined") {
      console.error("[QuickView] Slick не е зареден.");
      return;
    }

    // 1) Вързване main <-> thumbs по ID (двупосочно)
    $scope.find(".js-slick-carousel[data-nav-for]").each(function () {
      var $main = $(this);
      var thumbsSel = $main.attr("data-nav-for"); // напр. "#sliderSyncingThumb"
      var $thumbs = $scope.find(thumbsSel);

      if ($main.length && $thumbs.length) {
        var thumbsOpts = $thumbs.data("slick") || {};
        if (!$thumbs.hasClass("slick-initialized")) {
          $thumbs.slick(
            Object.assign(
              {
                slidesToShow: +($thumbs.attr("data-slides-show") || 5),
                asNavFor: "#" + $main.attr("id"), // thumbs -> main
                focusOnSelect: true,
                arrows: true,
                infinite: true,
                slidesToScroll: 1,
                waitForAnimate: false,
              },
              thumbsOpts
            )
          );
        }

        var mainOpts = $main.data("slick") || {};
        if (!$main.hasClass("slick-initialized")) {
          $main.slick(
            Object.assign(
              {
                asNavFor: "#" + $thumbs.attr("id"), // main -> thumbs
                arrows: true,
                infinite: true,
                slidesToScroll: 1,
                waitForAnimate: false,
              },
              mainOpts
            )
          );
        }

        // задължително след инициализацията
        setArrows($scope);
      }
    });

    // 2) Всички останали .js-slick-carousel (без nav-for)
    $scope
      .find(".js-slick-carousel")
      .not("[data-nav-for]")
      .each(function () {
        var $el = $(this);
        if ($el.hasClass("slick-initialized")) {
          $el.slick("setPosition").slick("refresh");
        } else {
          $el.slick(
            $el.data("slick") || {
              dots: false,
              arrows: true,
              infinite: true,
              waitForAnimate: false,
            }
          );
        }
      });

    // стрелки и за тях
    setArrows($scope);

    // 3) Fancybox
    if ($.fancybox) {
      $scope.find(".img-fancybox").fancybox();
    }

    // 4) Quantity
    $scope.find(".js-quantity").each(function () {
      var $w = $(this),
        $inp = $w.find(".js-result");
      $w.find(".js-plus")
        .off("click.qv")
        .on("click.qv", function () {
          $inp.val(+($inp.val() || 1) + 1);
        });
      $w.find(".js-minus")
        .off("click.qv")
        .on("click.qv", function () {
          var v = +($inp.val() || 1);
          $inp.val(Math.max(1, v - 1));
        });
    });

    // 5) Принудително позициониране след кратко време
    setTimeout(function () {
      $scope.find(".js-slick-carousel.slick-initialized").each(function () {
        $(this).slick("setPosition").slick("refresh");
      });
      setArrows($scope); // подсигуряване след refresh
    }, 50);
  }

  // Отваряне на QuickView
  $(document).on("click", ".quickview-btn", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var url = $btn.data("quick-url");
    var $modal = $("#quickViewModal");
    var $body = $("#quickViewBody");

    if ($btn.data("loading")) return;
    $btn.data("loading", true).prop("disabled", true);

    $body.html(
      '<div class="p-5 text-center" id="quickViewSpinner">' +
        '<div class="spinner-border" role="status"></div>' +
        '<div class="mt-3">Зареждане…</div>' +
        "</div>"
    );
    $modal.modal("show");

    function hydrateWhenVisible() {
      if ($modal.hasClass("show")) {
        hydrateIn($body);
        setTimeout(function () {
          $body.find(".js-slick-carousel.slick-initialized").each(function () {
            $(this).slick("setPosition").slick("refresh");
          });
          setArrows($body);
        }, 0);
      } else {
        $modal.off("shown.bs.modal.qv").one("shown.bs.modal.qv", function () {
          hydrateIn($body);
          setTimeout(function () {
            $body.find(".js-slick-carousel.slick-initialized").each(function () {
              $(this).slick("setPosition").slick("refresh");
            });
            setArrows($body);
          }, 0);
        });
      }
    }

    if (quickCache[url]) {
      $body.html(quickCache[url]);
      hydrateWhenVisible();
      $btn.data("loading", false).prop("disabled", false);
      return;
    }

    $.ajax({
      url: url,
      type: "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      timeout: 15000,
    })
      .done(function (html) {
        quickCache[url] = html;
        $body.html(html);
        hydrateWhenVisible();
      })
      .fail(function (xhr) {
        var msg = "Грешка при зареждане на продукта.";
        if (xhr && xhr.status) msg += " (HTTP " + xhr.status + ")";
        $body.html(
          '<div class="p-5 text-center text-danger">' + msg + "</div>"
        );
      })
      .always(function () {
        $btn.data("loading", false).prop("disabled", false);
      });
  });

  // Почисти тялото при затваряне (по желание)
  $("#quickViewModal").on("hidden.bs.modal", function () {
    $("#quickViewBody").empty();
  });
})();
