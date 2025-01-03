$("#myTable").DataTable({
  scrollX: true,
  searching: false,
  language: {
    lengthMenu: "<i class='fa-solid fa-bars'></i> _MENU_ ",
    zeroRecords: "Tidak ada data yang ditemukan",
    infoFiltered: "(difilter dari _MAX_ total baris)",
    paginate: {
      previous: "<i class='fa-solid fa-arrow-left'></i>",
      next: "<i class='fa-solid fa-arrow-right'></i>",
    },
  },
});

document.addEventListener("DOMContentLoaded", function () {
  const rows = document.querySelectorAll(".clickable-row");
  rows.forEach((row) => {
    row.addEventListener("click", () => {
      const id = row.getAttribute("data-id");
      window.location.href = `fviewSH.php?id=${id}`;
    });
  });

  flatpickr("#startDate", {
    dateFormat: "Y-m-d",
    allowInput: true,
  });
  flatpickr("#endDate", {
    dateFormat: "Y-m-d",
    allowInput: true,
  });

  document.getElementById("resetFilter").addEventListener("click", function () {
    const url = new URL(window.location.href);
    url.searchParams.delete("startDate");
    url.searchParams.delete("endDate");
    url.searchParams.delete("Finvoice");
    url.searchParams.delete("Fvendor");
    url.searchParams.delete("Fdepartemen");
    window.location.href = url.toString();
  });

  document.getElementById("showF").addEventListener("click", function () {
    var card = document.getElementById("cardContainer");
    if (card.style.display === "none" || card.style.display === "") {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });

  document.addEventListener("click", function (event) {
    var card = document.getElementById("cardContainer");
    var button = document.getElementById("showF");
    if (
      card.style.display === "block" &&
      !card.contains(event.target) &&
      !button.contains(event.target)
    ) {
      card.style.display = "none";
    }
  });
});



