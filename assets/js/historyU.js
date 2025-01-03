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
        initializeDataTable('myTable3');
        initializeDataTable('myTable4');
        initializeDataTable('myTable5');

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
    showPage(5);

    $(".nav-btn").on("click", function() {
        var pageNumber = $(this).attr("id").split("-")[1];
        showPage(pageNumber);
    });

    const rows1 = document.querySelectorAll(".clickable-row1");
    rows1.forEach((row) => {
        row.addEventListener("click", () => {
            const id = row.getAttribute("data-id");
            window.location.href = `fview.php?id=${id}`;
        });
    });

    const rows2 = document.querySelectorAll(".clickable-row2");
    rows2.forEach((row) => {
        row.addEventListener("click", () => {
            const id = row.getAttribute("data-id");
            window.location.href = `fview.php?id=${id}`;
        });
    });

    const rows3 = document.querySelectorAll(".clickable-row3");
    rows3.forEach((row) => {
        row.addEventListener("click", () => {
            const id = row.getAttribute("data-id");
            window.location.href = `fviewsuccess_user.php?id=${id}`;
        });
    });
    const rows4 = document.querySelectorAll(".clickable-row4");
    rows4.forEach((row) => {
        row.addEventListener("click", () => {
            const id = row.getAttribute("data-id");
            window.location.href = `fviewrevisi.php?id=${id}`;
        });
    });
    const rows5 = document.querySelectorAll(".clickable-row5");
    rows5.forEach((row) => {
        row.addEventListener("click", () => {
            const id = row.getAttribute("data-id");
            window.location.href = `formdraft.php?id=${id}`;
        });
    });



    $('#startDate1, #endDate1').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate2, #endDate2').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate3, #endDate3').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate4, #endDate4').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    $('#startDate5, #endDate5').flatpickr({
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

document.getElementById("resetFilter3").addEventListener("click", function() {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate3");
    url.searchParams.delete("endDate3");
    url.searchParams.delete("Finvoice3");
    url.searchParams.delete("Fvendor3");
    url.searchParams.delete("Fdepartemen3");
    window.location.href = url.toString();
});

document.getElementById("showF3").addEventListener("click", function() {
    var card3 = document.getElementById("cardContainer3");
    if (card3.style.display === "none" || card3.style.display === "") {
        card3.style.display = "block";
    } else {
        card3.style.display = "none";
    }
});

document.addEventListener("click", function(event) {
    var card3 = document.getElementById("cardContainer3");
    var button = document.getElementById("showF3");
    if (
        card3.style.display === "block" &&
        !card3.contains(event.target) &&
        !button.contains(event.target)
    ) {
        card3.style.display = "none";
    }

    
});

document.getElementById("resetFilter4").addEventListener("click", function() {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate4");
    url.searchParams.delete("endDate4");
    url.searchParams.delete("Finvoice4");
    url.searchParams.delete("Fvendor4");
    url.searchParams.delete("Fdepartemen4");
    window.location.href = url.toString();
});

document.getElementById("showF4").addEventListener("click", function() {
    var card4 = document.getElementById("cardContainer4");
    if (card4.style.display === "none" || card4.style.display === "") {
        card4.style.display = "block";
    } else {
        card4.style.display = "none";
    }
});

document.addEventListener("click", function(event) {
    var card4 = document.getElementById("cardContainer4");
    var button = document.getElementById("showF4");
    if (
        card4.style.display === "block" &&
        !card4.contains(event.target) &&
        !button.contains(event.target)
    ) {
        card4.style.display = "none";
    }

    
});


document.getElementById("resetFilter5").addEventListener("click", function() {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate5");
    url.searchParams.delete("endDate5");
    url.searchParams.delete("Finvoice5");
    url.searchParams.delete("Fvendor5");
    url.searchParams.delete("Fdepartemen5");
    window.location.href = url.toString();
});

document.getElementById("showF5").addEventListener("click", function() {
    var card5 = document.getElementById("cardContainer5");
    if (card5.style.display === "none" || card5.style.display === "") {
        card5.style.display = "block";
    } else {
        card5.style.display = "none";
    }
});

document.addEventListener("click", function(event) {
    var card5 = document.getElementById("cardContainer5");
    var button = document.getElementById("showF5");
    if (
        card5.style.display === "block" &&
        !card5.contains(event.target) &&
        !button.contains(event.target)
    ) {
        card5.style.display = "none";
    }

    
});