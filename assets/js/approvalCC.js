document.addEventListener("DOMContentLoaded", function () {
    function initializeDataTables() {
        function initializeDataTable(tableId) {
            $(`#${tableId}`).DataTable({
                scrollX: true,
                searching: false,
                language: {
                    "lengthMenu": "<i class='fa-solid fa-bars'></i> _MENU_ ",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "infoFiltered": "(difilter dari _MAX_ total baris)",
                    "paginate": {
                        "previous": "<i class='fa-solid fa-arrow-left'></i>",
                        "next": "<i class='fa-solid fa-arrow-right'></i>"
                    }
                }
            });
        }

        // Initialize DataTables for both tables
        initializeDataTable('myTable1');
        initializeDataTable('myTable2');

    }

    function showPage(pageNumber) {
        $(".table-page").hide();
        $("#page-" + pageNumber).show();
        $(".table-page")
            .find("table")
            .each(function() {
                var table = $(this).DataTable();
                table.columns.adjust().draw();
            });
        $(".nav-btn").removeClass("active");
        $("#btn-" + pageNumber).addClass("active");
    }

    initializeDataTables();
    showPage(1);

    $(".nav-btn").on("click", function() {
        var pageNumber = $(this).attr("id").split("-")[1];
        showPage(pageNumber);
    });

    document.querySelector("#myTable1 tbody").addEventListener("click", function(event) {
        const row = event.target.closest(".clickable-row1");
        if (row) {
            const id = row.getAttribute("data-id");
            window.location.href = `fviewCC.php?id=${id}`;
        }
    });
    
    document.querySelector("#myTable2 tbody").addEventListener("click", function(event) {
        const row = event.target.closest(".clickable-row2");
        if (row) {
            const id = row.getAttribute("data-id");
            window.location.href = `fviewsuccess.php?id=${id}`;
        }
    });
    


    $('#startDate, #endDate').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate1, #endDate1').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate2, #endDate2').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
});


document.getElementById("resetFilter1").addEventListener("click", function() {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate1");
    url.searchParams.delete("endDate1");
    url.searchParams.delete("Finvoice");
    url.searchParams.delete("Fvendor");
    url.searchParams.delete("Fdepartemen");
    window.location.href = url.toString();
});

document.getElementById("showF1").addEventListener("click", function() {
    var card = document.getElementById("cardContainer1");
    if (card.style.display === "none" || card.style.display === "") {
        card.style.display = "block";
    } else {
        card.style.display = "none";
    }
});

document.addEventListener("click", function(event) {
    var card = document.getElementById("cardContainer1");
    var button = document.getElementById("showF1");
    if (
        card.style.display === "block" &&
        !card.contains(event.target) &&
        !button.contains(event.target)
    ) {
        card.style.display = "none";
    }
});


document.getElementById("resetFilter2").addEventListener("click", function() {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate2");
    url.searchParams.delete("endDate2");
    url.searchParams.delete("Finvoice2");
    url.searchParams.delete("Fvendor2");
    url.searchParams.delete("Fdepartemen2");
    window.location.href = url.toString();
});

document.getElementById("showF2").addEventListener("click", function() {
    var card2 = document.getElementById("cardContainer2");
    if (card2.style.display === "none" || card2.style.display === "") {
        card2.style.display = "block";
    } else {
        card2.style.display = "none";
    }
});

document.addEventListener("click", function(event) {
    var card2 = document.getElementById("cardContainer2");
    var button = document.getElementById("showF2");
    if (
        card2.style.display === "block" &&
        !card2.contains(event.target) &&
        !button.contains(event.target)
    ) {
        card2.style.display = "none";
    }
});