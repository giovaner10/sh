import "../css/layout.css";

export class Grid{
    menuAberto = false;

    static expandirGrid() {
        menuAberto = !menuAberto;
        let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
        let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

        if (menuAberto) {
            $(".img-expandir").attr("src", buttonShow);
            $(".col-md-3").fadeOut(250, function () {
                $(".content-container").removeClass("col-md-9").addClass("col-md-12");
            });
        } else {
            $(".img-expandir").attr("src", buttonHide);
            $(".content-container").removeClass("col-md-12").addClass("col-md-9");
            setTimeout(() => {
                $(".col-md-3").fadeIn(250);
            }, 510);
        }
    }
}