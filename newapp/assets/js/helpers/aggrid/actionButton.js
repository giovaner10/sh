class ActionButton {
    constructor(tableId, dropdownClass) {
        this.tableId = tableId;
        this.dropdownClass = dropdownClass;
    }

    static abrirDropdown(dropdownId, buttonId, tableId) {
        var dropdown = $('#' + dropdownId);
        var altDropdown = dropdown.height() + 10;
        dropdown.css('bottom', `auto`).css('top', '100%');

        var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
        var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
        var posDropdown = $('#' + dropdownId).get(0).getBoundingClientRect().bottom;
        var posDropdownTop = $('#' + dropdownId).get(0).getBoundingClientRect().top;

        if (altDropdown > (posBordaTabela - posDropdownTop)) {
            if (altDropdown < (posDropdownTop - posBordaTabelaTop)) {
                dropdown.css('top', `auto`).css('bottom', '90%');
            } else {
                let diferenca = altDropdown - (posDropdownTop - posBordaTabelaTop);
                dropdown.css('top', `-${(altDropdown - 60) - (diferenca)}px`);
            }
        }
    }

    static abrirDropdownReady(dropdownId, buttonId, tableId) {
        var dropdown = $("#" + dropdownId);

        if (dropdown.is(":visible")) {
            dropdown.hide();
            return;
        }

        $(".dropdown-menu").hide();

        dropdown.show();
        var posDropdown = dropdown.height() + 4;

        var dropdownItems = $("#" + dropdownId + " .dropdown-item-acoes");
        var alturaDrop = 0;
        for (var i = 0; i <= dropdownItems.length; i++) {
            alturaDrop += dropdownItems.height();
        }

        if (alturaDrop >= 235) {
            $(".dropdown-menu-acoes").css("overflow-y", "scroll");
        }

        var posBordaTabela = $("#" + tableId + " .ag-body-viewport")
            .get(0)
            .getBoundingClientRect().bottom;
        var posBordaTabelaTop = $("#" + tableId + " .ag-header")
            .get(0)
            .getBoundingClientRect().bottom;

        var posButton = $("#" + buttonId)
            .get(0)
            .getBoundingClientRect().bottom;
        var posButtonTop = $("#" + buttonId)
            .get(0)
            .getBoundingClientRect().top;

        if (posDropdown > posBordaTabela - posButton) {
            if (posDropdown < posButtonTop - posBordaTabelaTop) {
                dropdown.css("top", `-${alturaDrop - 150}px`);
            } else {
                let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
                dropdown.css("top", `-${alturaDrop / 2 - diferenca}px`);
            }
        }

        $(document).on("click", function (event) {
            if (!dropdown.is(event.target) && !$("#" + buttonId).is(event.target)) {
                dropdown.hide();
            }
        });
    }

    init() {
        $(document).on('shown.bs.dropdown', `${this.dropdownClass}`, function () {
            var dropdownId = $(this).find('.dropdown-menu').attr('id');
            var buttonId = $(this).find('.btn-dropdown').attr('id');
            ActionButton.abrirDropdown(dropdownId, buttonId, this.tableId);
        });
    }

    initReady() {
        $(document).ready(function () {
            $(document).on('shown.bs.dropdown', `${this.dropdownClass}`, function () {
                var dropdownId = $(this).find('.dropdown-menu').attr('id');
                var buttonId = $(this).find('.btn-dropdown').attr('id');
                var tableId = $(this).attr("data-tableId");
                ActionButton.abrirDropdownReady(dropdownId, buttonId, tableId);
            });
        });
    }
}