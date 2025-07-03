function printContent() {
    $("#print-content").printThis({
        debug: false,
        importCSS: true,
        importStyle: true,
        printContainer: true,
        loadCSS: "https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css",
        pageTitle: "",
        removeInline: false,
        removeInlineSelector: "*",
        printDelay: 1000,
        header: null,
        footer: null,
        base: false,
        formValues: true,
        canvas: false,
        removeScripts: false,
        copyTagClasses: true,
        copyTagStyles: true,
        beforePrintEvent: null,
        beforePrint: null,
        afterPrint: null
    });
}