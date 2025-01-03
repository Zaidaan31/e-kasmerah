function initializeTable() {
  addRow();
  addRowbukti();
  addRowTol();
}



function addRow() {
  let rowCount = document.querySelectorAll("#barangTable tbody tr").length + 1;
  const tableBody = document.querySelector("#barangTable tbody");
  const row = document.createElement("tr");

  // Load options via AJAX
  fetch("includes/get_option.php")
    .then((response) => response.text())
    .then((options) => {
      row.innerHTML = `
        <td>${rowCount}</td>
        <td>
        <select name="keterangan_new[]" style="width:200px" class="select-box" onchange="handleSelectChange(this)" required>
        <option value="">Select</option> 
            ${options}
        </select>
        </td>
        <td>
        <textarea name="deskripsi_new[]" style="width:200px;height:40px" placeholder="Deskripsi"></textarea>
        </td>
        <td>
            <div class="input-container">
                <span class="currency-label">Rp.</span>
                <input type="number" name="jumlah_new[]" style="width:200px" oninput="updateRowTotal(this); calculateGrandTotal();" step="any" placeholder="Jumlah" >
            </div>
        </td>
        <td>
         <div class="upload-wrapper">
          <div class="upload-sectionTB">
              <div class="upload-content">
                  <div>
                      <i class="fa-regular fa-file upload-icon" id="icon-bukti-${rowCount}"></i>
                      <img id="preview-bukti-${rowCount}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-bukti-${rowCount}')" style="display: none;" />
                  </div>
                  <div class="upload-placeholder" id="placeholder-bukti-${rowCount}" onclick="document.getElementById('upload-bukti-${rowCount}').click()">
                      <p>Upload !</p>
                  </div>
              </div>
        <input type="file" id="upload-bukti-${rowCount}" name="bukti_new[]" onchange="handleFileSelect(event, 'icon-bukti-${rowCount}', 'preview-bukti-${rowCount}', 'placeholder-bukti-${rowCount}', 'download-bukti-${rowCount}', 'reset-bukti-${rowCount}')" />
        <a class="download-button" id="download-bukti-${rowCount}" href="#" download><i class="fa-solid fa-download"></i></a>
        <button type="button" class="reset-button" id="reset-bukti-${rowCount}" onclick="resetUpload('icon-bukti-${rowCount}', 'preview-bukti-${rowCount}', 'placeholder-bukti-${rowCount}', 'download-bukti-${rowCount}', 'upload-bukti-${rowCount}')"><i class="fa-solid fa-arrows-rotate"></i></button>
    </div>
    <div><small class="catatan">*Catatan : Hanya format yang didukung*</small></div>
    
</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button>
        </td>
    `;

      tableBody.appendChild(row);
      updateRowNumbers(); // Ensure row numbers are updated

      // Attach input event listeners to the new inputs
      const jumlahInput = row.querySelector('input[name="jumlah_new[]"]');
      if (jumlahInput) {
        jumlahInput.addEventListener('input', calculateGrandTotal);
      }
      
      // Similarly for jumlahBukti and jumlahTol (if you implement them in the row)
    })
    .catch((error) => console.error("Error fetching options:", error));
}






// Event listener untuk memastikan semua select diproses saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
    console.log('JavaScript loaded');  // Periksa apakah skrip berjalan
    document.querySelectorAll('select[name="keterangan[]"]').forEach(function (selectElement) {
        const rowId = selectElement.id.split('-')[1]; // Ambil ID baris dari ID elemen
        updateJumlahType(selectElement, rowId); // Panggil fungsi update untuk inisialisasi
    });
});

// Tambahkan event listener pada perubahan select
document.querySelectorAll('select[name="keterangan[]"]').forEach((select) => {
    select.addEventListener('change', function () {
        const rowId = this.id.split('-')[1]; // Ambil ID baris dari ID elemen
        updateJumlahType(this, rowId);
    });
});



function updateBuktiType(selectElement, rowId) {
  const selectedValue = selectElement.value;
  const uploadWrapper = document.querySelector(`#upload-wrapper-${rowId}`);
  const detailBuktiButton = document.querySelector(`#detail-bukti-${rowId}`);
  const uploadTolButton = document.querySelector(`#upload-tol-${rowId}`);

  // Hide everything by default
  uploadWrapper.style.display = 'none';
  detailBuktiButton.style.display = 'none';
  uploadTolButton.style.display = 'none';

  // Show elements based on the selected value
  if (selectedValue == '1' || selectedValue == '9' || selectedValue == '14') {
      detailBuktiButton.style.display = 'inline-block';
  } else if (selectedValue == '13') {
      uploadTolButton.style.display = 'inline-block';
  } else {
      uploadWrapper.style.display = 'block';
  }
}

// Function to initialize the button visibility on page load
function initializeButtons() {
  // Loop through each select element and update the buttons based on the initial selection
  const selectElements = document.querySelectorAll('select[name="keterangan[]"]');
  selectElements.forEach(selectElement => {
      const rowId = selectElement.id.split('-')[1]; // Get rowId from the id of the select element
      updateBuktiType(selectElement, rowId); // Run the update function immediately
  });
}

// Call the function on page load
window.onload = initializeButtons;



function handleSelectChange(selectElement) {
  const selectedValue = parseInt(selectElement.value);
  const row = selectElement.closest("tr");  
  // const jumlahCell = row.querySelector('input[name="jumlah[]"], input[name="jumlahTol[]"], input[name="jumlahBukti[]"]');
  const buktiContainer = row.querySelector(".upload-wrapper");

  const SPECIFIC_IDS = [1, 9, 14];
  const TOL_ID = 13;

  // Hide detail tables if the selected value is not specific
  if (!SPECIFIC_IDS.includes(selectedValue) && selectedValue !== TOL_ID) {
    hideAllDetailTables();
  }

  if (SPECIFIC_IDS.includes(selectedValue)) {
    // When a specific ID is selected
    buktiContainer.innerHTML = `
      <a href="javascript:void(0);" class="btn btn-info" onclick="showDetailBukti();">Lihat Detail Bukti</a>
    `;
  } else if (selectedValue === TOL_ID) {
    // When the toll ID is selected
    buktiContainer.innerHTML = `
      <a href="javascript:void(0);" class="btn btn-info" onclick="showDetailTol();">Lihat Detail Bukti Tol</a>
    `;
  } else {
    const rowIndex = Array.from(row.parentElement.children).indexOf(row) + 1; // Indeks dimulai dari 1

    // Kembalikan upload section seperti semula
    buktiContainer.innerHTML = `
        <div class="upload-wrapper">
            <div class="upload-sectionTB">
                <div class="upload-content">
                    <div>
                        <i class="fa-regular fa-file upload-icon" id="icon-bukti-${rowIndex}"></i>
                        <img id="preview-bukti-${rowIndex}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-bukti-${rowIndex}')" style="display: none;" />
                    </div>
                    <div class="upload-placeholder" id="placeholder-bukti-${rowIndex}" onclick="document.getElementById('upload-bukti-${rowIndex}').click()">
                        <p>Upload !</p>
                    </div>
                </div>
                <input type="file" id="upload-bukti-${rowIndex}" name="bukti_new[]" onchange="handleFileSelect(event, 'icon-bukti-${rowIndex}', 'preview-bukti-${rowIndex}', 'placeholder-bukti-${rowIndex}', 'download-bukti-${rowIndex}', 'reset-bukti-${rowIndex}')" />
                <a class="download-button" id="download-bukti-${rowIndex}" href="#" download><i class="fa-solid fa-download"></i></a>
                <button type="button" class="reset-button" id="reset-bukti-${rowIndex}" onclick="resetUpload('icon-bukti-${rowIndex}', 'preview-bukti-${rowIndex}', 'placeholder-bukti-${rowIndex}', 'download-bukti-${rowIndex}', 'upload-bukti-${rowIndex}')"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
            <div><small class="catatan">*Catatan : Hanya format yang didukung*</small></div>
        </div>
    `;
  }
}

// Function to hide both detail tables
function hideAllDetailTables() {
  const detailBuktiTable = document.getElementById("buktiTable");
  const tolTable = document.getElementById("tolTable");

  detailBuktiTable.style.display = "none"; // Hide the bukti table
  tolTable.style.display = "none"; // Hide the tol table
}



function updateRowTotal(inputElement) {
  var rowId = inputElement.id.split('-')[1]; // Extract row ID from the input element ID
  var keteranganSelect = document.getElementById("keterangan-" + rowId);

  // Check if the selected id_keterangan is 1, 9, 14, or 13
  var selectedId = parseInt(keteranganSelect.value);
  if ([1, 9, 14, 13].includes(selectedId)) {
      // Implement row total calculation here
      var rowTotal = parseFloat(inputElement.value) || 0;

      // Update the row total field if needed (assuming there's a field to display it)
      // e.g., document.getElementById("rowTotal-" + rowId).value = rowTotal;

      // Call calculateGrandTotal to update the grand total
      calculateGrandTotal();
  }
}

function calculateGrandTotal() {
  let grandTotal = 0;

  // Loop untuk menghitung semua input jumlah
  document.querySelectorAll('input[name="jumlah[]"], input[name="jumlah_new[]"]').forEach((input) => {
      const value = parseFloat(input.value) || 0;
      grandTotal += value;
  });

  // Perbarui tampilan total
  document.getElementById("grandTotal").textContent = formatNumber(grandTotal);
  document.getElementById("hiddenTotal").value = grandTotal;

  // Konversi angka ke kata-kata dan perbarui
  const grandTotalWords = numberToWords(grandTotal);
  document.getElementById("grandTotalWords").textContent = grandTotalWords;
  document.getElementById("hiddenTerbilang").value = grandTotalWords;
}

// Event listener untuk perubahan input
document.addEventListener("input", (event) => {
  if (event.target.matches('input[name="jumlah[]"], input[name="jumlah_new[]"]')) {
      calculateGrandTotal();
  }
});

// Trigger initial calculation if there are already pre-filled values (e.g., populated dynamically)
window.addEventListener('load', calculateGrandTotal); // Ensure grand total is calculated on page load


// Function to delete a row with confirmation
function deleteRow(button) {
  // Show confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Remove the row if confirmed
      const row = button.closest("tr");
      row.remove();

      // Show success message
      Swal.fire({
        title: "Deleted!",
        text: "Your file has been deleted.",
        icon: "success",
      });

      // Update row numbers and calculate grand total
      updateRowNumbers();
      calculateGrandTotal();
    }
  });
}

// Function to update row numbers
function updateRowNumbers() {
  const rows = document.querySelectorAll("#barangTable tbody tr");
  rows.forEach((row, index) => {
    row.querySelector("td:first-child").textContent = index + 1;
  });
}

// Function to format number with commas
function formatNumber(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Function to convert number to words
function numberToWords(amount) {
  const ones = [
    "",
    "Satu",
    "Dua",
    "Tiga",
    "Empat",
    "Lima",
    "Enam",
    "Tujuh",
    "Delapan",
    "Sembilan",
  ];
  const teens = [
    "Sepuluh",
    "Sebelas",
    "Dua Belas",
    "Tiga Belas",
    "Empat Belas",
    "Lima Belas",
    "Enam Belas",
    "Tujuh Belas",
    "Delapan Belas",
    "Sembilan Belas",
  ];
  const tens = [
    "",
    "",
    "Dua Puluh",
    "Tiga Puluh",
    "Empat Puluh",
    "Lima Puluh",
    "Enam Puluh",
    "Tujuh Puluh",
    "Delapan Puluh",
    "Sembilan Puluh",
  ];
  const thousands = ["", "Ribu", "Juta", "Miliar", "Triliun"];

  if (amount === 0) return "Nol Rupiah";

  let words = "";
  let i = 0;

  while (amount > 0) {
    let chunk = amount % 1000;
    if (chunk) {
      let str = "";
      let h = Math.floor(chunk / 100);
      let t = Math.floor((chunk % 100) / 10);
      let u = chunk % 10;

      if (h) {
        if (chunk === 100) {
          str += "Seratus ";
        } else {
          str += ones[h] + " Ratus ";
        }
      }

      if (t > 1) {
        str += tens[t] + " ";
        str += ones[u];
      } else if (t === 1) {
        str += teens[u];
      } else {
        str += ones[u];
      }

      if (i > 0) {
        if (chunk === 1 && i === 1) {
          str = "Seribu ";
        } else {
          str += " " + thousands[i] + " ";
        }
      }

      words = str + words;
    }
    amount = Math.floor(amount / 1000);
    i++;
  }

  words = words.replace("Satu Ratus", "Seratus");
  return `(${words.trim()} Rupiah)`;
}

  function handleFileSelect(
    event,
    iconId,
    previewId,
    placeholderId,
    downloadId,
    resetId
  ) {
    const files = event.target.files;
    const file = files[0];

    // Define accepted file types
    const acceptedImageTypes = ['image/jpeg', 'image/png'];
    const acceptedDocumentTypes = [
        'application/pdf',
        'application/msword', // .doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/vnd.ms-excel', 
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/tiff',
        'application/zip',  // .zip
        'application/x-rar-compressed'  // .rar

    ];

    if (file) {
      const fileType = file.type;

      if (
        acceptedImageTypes.includes(fileType) ||
        acceptedDocumentTypes.includes(fileType)
      ) {
        const reader = new FileReader();
        const icon = document.getElementById(iconId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        const downloadButton = document.getElementById(downloadId);
        const resetButton = document.getElementById(resetId);

        if (acceptedImageTypes.includes(fileType)) {
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block"; // Show image preview
            icon.style.display = "none"; // Hide icon
            placeholder.querySelector("p").textContent = file.name;
            downloadButton.href = e.target.result;
            downloadButton.download = file.name;

            // Enable download and reset buttons
            downloadButton.classList.add("enabled");
            resetButton.classList.add("enabled");
          };

          reader.readAsDataURL(file);
        } else {
          // For document files
          preview.style.display = "none"; // Hide preview for documents
          icon.style.display = "flex"; // Show document icon
          placeholder.querySelector("p").textContent = file.name;
          downloadButton.href = URL.createObjectURL(file);
          downloadButton.download = file.name;

          // Enable download and reset buttons
          downloadButton.classList.add("enabled");
          resetButton.classList.add("enabled");
        }
      } else {
        // Show SweetAlert if file type is not allowed
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Format file tidak mendukung.",
        });

        // Clear the file input
        event.target.value = "";
      }
    }
  }function handleFileSelect(event, iconId, previewId, placeholderId, downloadId, resetId) {
    const files = event.target.files;
    const file = files[0];
  
    // Define accepted file types
    const acceptedImageTypes = ["image/jpeg", "image/png"];
    const acceptedDocumentTypes = [
      "application/pdf",
      "application/msword",
      "application/vnd.ms-excel",
      "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      "image/tiff",
    ];
  
    if (file) {
      const fileType = file.type;
  
      if (
        acceptedImageTypes.includes(fileType) ||
        acceptedDocumentTypes.includes(fileType)
      ) {
        const reader = new FileReader();
        const icon = document.getElementById(iconId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        const downloadButton = document.getElementById(downloadId);
        const resetButton = document.getElementById(resetId);
  
        if (acceptedImageTypes.includes(fileType)) {
          // Handling image file preview
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block"; // Show image preview
            icon.style.display = "none"; // Hide icon
            placeholder.querySelector("p").textContent = file.name;
            downloadButton.href = e.target.result;
            downloadButton.download = file.name;
  
            // Enable download and reset buttons
            downloadButton.classList.add("enabled");
            resetButton.classList.add("enabled");
          };
  
          reader.readAsDataURL(file);
        } else {
          // Handling document file
          preview.style.display = "none"; // Hide image preview for document
          icon.style.display = "flex"; // Show document icon
          placeholder.querySelector("p").textContent = file.name;
          downloadButton.href = URL.createObjectURL(file);
          downloadButton.download = file.name;
  
          // Enable download and reset buttons
          downloadButton.classList.add("enabled");
          resetButton.classList.add("enabled");
        }
      } else {
        // Show SweetAlert if file type is not allowed
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Format file tidak mendukung.",
        });
  
        // Clear the file input
        event.target.value = "";
      }
    }
  }
  
  function resetFileInput(inputId, iconId, previewId, placeholderId, downloadId) {
    // Clear the file input and reset the display
    document.getElementById(inputId).value = '';
    document.getElementById(iconId).style.display = 'block'; // Show file icon again
    document.getElementById(previewId).style.display = 'none'; // Hide image preview
    document.getElementById(placeholderId).style.display = 'block'; // Show placeholder text
    document.getElementById(downloadId).style.display = 'none'; // Hide download button
  }
  

// // Function to open modal with image preview
// function openModal(previewId) {
//   var modal = document.getElementById("imageModal");
//   var modalImg = document.getElementById("modalImage");
//   var captionText = document.getElementById("modalCaption");
//   var previewImg = document.getElementById(previewId);

//   modal.style.display = "block";
//   modalImg.src = previewImg.src;
//   captionText.innerHTML = previewImg.alt;
// }

// // Close the modal
// function closeModal() {
//   var modal = document.getElementById("imageModal");
//   modal.style.display = "none";
// }

// Close modal if clicked outside of it
window.onclick = function (event) {
  if (event.target == document.getElementById("modal")) {
    closeModal();
  }
};

function resetTable() {
  // Hapus semua baris yang ada
  document.querySelector("#barangTable tbody").innerHTML = "";

  // Reset penghitung baris
  rowCount = 1;

  // Tambahkan satu baris kosong
  addRow();

  // Hitung jumlah total
  calculateGrandTotal();
}







// Function to show detail bukti
function showDetailBukti() {
  const detailBuktiTable = document.getElementById("buktiTable");
  const isDisplayed = detailBuktiTable.style.display === "block"; // Check if the table is already displayed

  hideAllDetailTables(); // Hide other tables first

  // If detail bukti table is not displayed, show it
  if (!isDisplayed) {
    detailBuktiTable.style.display = "block"; // Show the table
    const buktiTableBody = document.getElementById("buktiTableBody");
    buktiTableBody.innerHTML = ""; // Clear previous table content
    addRowbukti(); // Add a new default row
  }
}

function showDetailTol() {
  const detailTolTable = document.getElementById("tolTable");
  const isDisplayed = detailTolTable.style.display === "block"; // Check if the table is already displayed

  hideAllDetailTables(); // Hide other tables first

  // If detail tol table is not displayed, show it
  if (!isDisplayed) {
    detailTolTable.style.display = "block"; // Show the table
    const tolTableBody = document.getElementById("tolTableBody");
    tolTableBody.innerHTML = ""; // Clear previous table content
    addRowTol(); // Add a new default row
  }
}



// Function to add a new row to the table
function addRowbukti() {
  let rowCount = document.querySelectorAll("#buktiTable tbody tr").length + 1;
  const tableBody = document.querySelector("#buktiTable tbody");
  const row = document.createElement("tr");

  row.innerHTML = `
      <td>${rowCount}</td>
      <td>
          <textarea name="deskripsi_buktiNew[]" class="form-control" placeholder="Deskripsi"></textarea>
      </td>
      <td>
          <div class="input-container">
              <span class="currency-label">Rp.</span>
              <input type="number" name="biayabukti_New[]" class="form-control" oninput="updateRowTotalbukti(this)" step="any" placeholder="Biaya">
          </div>
      </td>
      <td>
          <div class="upload-wrapper">
              <div class="upload-section">
                  <div class="upload-content">
                      <div>
                          <i class="fa-regular fa-file uploadbukti-icon" id="icon-buktidetail-${rowCount}"></i>
                          <img id="preview-buktidetail-${rowCount}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-buktidetail-${rowCount}')" style="display: none;" />
                      </div>
                      <div class="upload-placeholder" id="placeholder-buktidetail-${rowCount}" onclick="document.getElementById('upload-buktidetail-${rowCount}').click()">
                          <p>Upload !</p>
                      </div>
                  </div>
                  <input type="file" id="upload-buktidetail-${rowCount}" name="bukti1[]" onchange="handleFileSelect(event, 'icon-buktidetail-${rowCount}', 'preview-buktidetail-${rowCount}', 'placeholder-buktidetail-${rowCount}', 'download-buktidetail-${rowCount}', 'reset-buktidetail-${rowCount}')" />
                  <a class="download-button" id="download-buktidetail-${rowCount}" href="#" download><i class="fa-solid fa-download"></i></a>
                  <button type="button" class="reset-button" id="reset-buktidetail-${rowCount}" onclick="resetUpload('icon-buktidetail-${rowCount}', 'preview-buktidetail-${rowCount}', 'placeholder-buktidetail-${rowCount}', 'download-buktidetail-${rowCount}', 'upload-buktidetail-${rowCount}')"><i class="fa-solid fa-arrows-rotate"></i></button>
              </div>
              <div><small style="font-size: 11px; color: #666;">*Catatan : Hanya bisa mengunggah file dengan format: JPG, PNG, PDF, DOC*</small></div>
          </div>
      </td>
      <td>
          <button type="button" class="btn btn-danger btn-sm" onclick="deleteRowbukti(this)"><i class="fa-solid fa-trash"></i></button>
      </td>
  `;

  tableBody.appendChild(row);
  updateRowNumbersbukti(); // Ensure row numbers are updated
  calculateGrandTotalbukti(); // Calculate grand total after adding the row
}

function updateRowTotalbukti(input) {
  calculateGrandTotalbukti(); // Trigger recalculation of the grand total
}

function calculateGrandTotalbukti() {
  let total = 0;

  // Mengambil semua input dari biayabukti[]
  const biayaInputs = document.querySelectorAll('input[name="biayabukti[]"]');
  biayaInputs.forEach((input) => {
      const value = parseFloat(input.value) || 0;
      total += value;
  });

  // Mengambil semua input dari biayabukti_new[]
  const biayaNewInputs = document.querySelectorAll('input[name="biayabukti_New[]"]');
  biayaNewInputs.forEach((input) => {
      const value = parseFloat(input.value) || 0;
      total += value;
  });

  // Update total display dan input 'jumlahBukti[]'
  document.getElementById("grandTotalbukti").innerText = "Rp. " + total.toLocaleString("id-ID");
  document.getElementById("hiddenTotalbukti").value = total;

  // Update input jumlah_lov_new2[] dengan total yang dihitung
  const jumlahLovNew2Inputs = document.querySelectorAll('input[name="jumlah_lov_new2[]"]');
  jumlahLovNew2Inputs.forEach(input => {
      input.value = total; // Assign nilai total ke setiap jumlah_lov_new2 input
  });

  return total;
}




function deleteRowbukti(button) {
  const row = button.closest("tr");
  row.remove();
  updateRowNumbersbukti(); // Update row numbers after deletion
  calculateGrandTotalbukti(); // Recalculate grand total after row deletion
}

function updateRowNumbersbukti() {
  const rows = document.querySelectorAll("#buktiTable tbody tr");
  rows.forEach((row, index) => {
    row.querySelector("td:first-child").textContent = index + 1; // Update the row number
  });
}

// Reset function to clear all fields
function resetTablebukti() {
  const tableBody = document.querySelector("#buktiTable tbody");
  tableBody.innerHTML = ""; // Clear all rows
  addRowbukti(); // Add a new default row
  calculateGrandTotalbukti(); // Reset grand total
}

// Fungsi untuk mengambil opsi gerbang tol dari file PHP
async function fetchGerbangTolOptions() {
  try {
    const response = await fetch("../includes/get_option_tol.php"); // Ganti dengan path ke file PHP
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const options = await response.text();
    return options; // Mengembalikan opsi dalam format HTML
  } catch (error) {
    console.error("Error fetching options:", error);
    return ""; // Jika terjadi error, kembalikan string kosong
  }
}

// Function to open modal and display the image
function openModal(imageUrl, imageName) {
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  const modalCaption = document.getElementById('modalCaption');

  modal.style.display = 'block';
  modalImage.src = imageUrl;
  modalCaption.innerHTML = imageName;
}

// Function to close modal
function closeModal() {
  const modal = document.getElementById('imageModal');
  modal.style.display = 'none';
}

// Function to handle file input change
function handleFileInput(fileInputId, iconId, previewId, placeholderId, downloadButtonId) {
  const fileInput = document.getElementById(fileInputId);
  const icon = document.getElementById(iconId);
  const preview = document.getElementById(previewId);
  const placeholder = document.getElementById(placeholderId);
  const downloadButton = document.getElementById(downloadButtonId);

  fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (file) {
          const reader = new FileReader();

          // Read the file and update the UI dynamically
          reader.onload = function (e) {
              preview.src = e.target.result; // Set the preview image source
              preview.style.display = 'block'; // Show the preview
              icon.style.display = 'none'; // Hide the icon
              placeholder.style.display = 'none'; // Hide the placeholder text
              downloadButton.href = e.target.result; // Update the download link
              downloadButton.download = file.name; // Update the download filename
          };

          reader.readAsDataURL(file);
      } else {
          resetFileInput(fileInputId, iconId, previewId, placeholderId, downloadButtonId);
      }
  });
}

// Function to reset file input
function resetFileInput(fileInputId, iconId, previewId, placeholderId, downloadButtonId) {
  const fileInput = document.getElementById(fileInputId);
  const icon = document.getElementById(iconId);
  const preview = document.getElementById(previewId);
  const placeholder = document.getElementById(placeholderId);
  const downloadButton = document.getElementById(downloadButtonId);

  fileInput.value = ''; // Clear the file input
  icon.style.display = 'block'; // Show the icon
  preview.style.display = 'none'; // Hide the preview
  placeholder.style.display = 'block'; // Show the placeholder text
  downloadButton.href = '#'; // Reset the download link
  downloadButton.download = ''; // Clear the download filename
}

// Initialize file input handlers for all file inputs
document.querySelectorAll('input[type="file"]').forEach((fileInput) => {
  const id = fileInput.id.replace('upload-bukti-', '');
  handleFileInput(
      `upload-bukti-${id}`,
      `icon-bukti-${id}`,
      `preview-bukti-${id}`,
      `placeholder-bukti-${id}`,
      `download-bukti-${id}`
  );
});






function addRowTol() {
  let rowCount = document.querySelectorAll("#tolTable tbody tr").length + 1;
  const tableBody = document.querySelector("#tolTable tbody");
  const row = document.createElement("tr");

  // Fetch options from PHP
  fetch("includes/get_option_tol.php")
    .then((response) => response.text())
    .then((options1) => {
      row.innerHTML = `
              <td>${rowCount}</td>
              <td>
                  <select name="gerbangTol_New[]" style="width:200px" class="select-box" >
                      <option value="">Select</option> 
                      ${options1}  <!-- Displaying options from PHP -->
                  </select>
              </td>
              <td>
                  <div class="input-container">
                      <span class="currency-label">Rp.</span>
                      <input type="number" name="tarif_New[]" class="form-control" step="any" placeholder="Biaya Tol"  readonly>
                  </div>
              </td>
              <td>
           <div class="upload-wrapper">
  <div class="upload-section">
      <div class="upload-content">
          <div>
              <i class="fa-regular fa-file upload-icon" id="icon-tol2-${rowCount}"></i>
              <img id="preview-tol2-${rowCount}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-tol2-${rowCount}')" style="display: none;" />
          </div>
          <div class="upload-placeholder" id="placeholder-tol2-${rowCount}" onclick="document.getElementById('upload-tol2-${rowCount}').click()">
              <p>Upload !</p>
          </div>
      </div>
      <input type="file" id="upload-tol2-${rowCount}" name="buktiTol_New[]" onchange="handleFileSelect(event, 'icon-tol2-${rowCount}', 'preview-tol2-${rowCount}', 'placeholder-tol2-${rowCount}', 'download-tol2-${rowCount}', 'reset-tol2-${rowCount}')" />
      <a class="download-button" id="download-tol2-${rowCount}" href="#" download><i class="fa-solid fa-download"></i></a>
      <button type="button" class="reset-button" id="reset-tol2-${rowCount}" onclick="resetUpload('icon-tol2-${rowCount}', 'preview-tol2-${rowCount}', 'placeholder-tol2-${rowCount}', 'download-tol2-${rowCount}', 'upload-tol2-${rowCount}')"><i class="fa-solid fa-arrows-rotate"></i></button>
  </div>
  <div><small class="catatan">*Catatan : Hanya format yang didukung*</small></div>
</div>

              <td>
                  <button type="button" class="btn btn-danger btn-sm" onclick="deleteRowTol(this)"><i class="fa-solid fa-trash"></i></button>
              </td>
          `;

      tableBody.appendChild(row);
      updateRowNumbersTol(); // Update row numbers

      // Attach change event listener to the newly added select element
      const selectElement = row.querySelector('select[name="gerbangTol[]"]');
      selectElement.addEventListener("change", function () {
        var selectedOption = this.options[this.selectedIndex];
        var biaya = selectedOption.dataset.tarif; // Get tarif from data-tarif

        console.log("Selected Option:", selectedOption); // Debugging: log selected option
        console.log("Biaya:", biaya); // Debugging: log the tarif value

        // Set the corresponding input to the biaya value
        if (biaya) {
          // Check if biaya is defined
          this.closest("tr").querySelector('input[name="tarif[]"]').value =
            biaya;
        } else {
          console.log("Biaya is undefined for selected option."); // Debugging: log if biaya is undefined
        }
      });

      // Re-initialize Select2 for the new select box
      $(selectElement).select2({
        width: "100%",
        placeholder: "Select an option",
        allowClear: true,
      });
    })
    .catch((error) => {
      console.error("Error fetching options:", error);
    });
}

$(document).ready(function () {
  // Initialize Select2 on all select boxes
  $(".select-box").select2({
    width: "100%",
    placeholder: "Select an option",
    allowClear: true,
  });

  // Event listener for changes on gerbangTol select boxes
  $(document).on("change", 'select[name="gerbangTol_New[]"]', function () {
    var selectedOption = $(this).find("option:selected");
    var tarif = selectedOption.data("tarif");

    // Set the corresponding input value
    $(this).closest("tr").find('input[name="tarif_New[]"]').val(tarif);

    // Calculate the grand total whenever a tarif is updated
    calculateGrandTotalTol();
  });

  // Event listener for changes in tarif inputs
  $(document).on("input", 'input[name="tarif_New[]"]', function () {
    // Calculate the grand total whenever the input changes
    calculateGrandTotalTol();
  });
});


// Fungsi untuk memperbarui total per baris
function updateRowTotalTol(input) {
  calculateGrandTotalTol(); // Recalculate the grand total
}

function calculateGrandTotalTol() {
  let total = 0;

  // Mengambil semua input dari tarif[]
  const tarifInputs = document.querySelectorAll('input[name="tarif[]"]');
  tarifInputs.forEach((input) => {
      const value = parseFloat(input.value) || 0;
      total += value;
  });

  // Mengambil semua input dari tarif_New[] (jika ada)
  const tarifNewInputs = document.querySelectorAll('input[name="tarif_New[]"]');
  tarifNewInputs.forEach((input) => {
      const value = parseFloat(input.value) || 0;
      total += value;
  });

  // Update elemen display grand total
  document.getElementById("grandTotalTol").innerText = "Rp. " + total.toLocaleString("id-ID");

  // Set nilai hidden input 'hiddenTotalTol' agar bisa dikirim dalam form
  document.getElementById("hiddenTotalTol").value = total;

  // Update jumlah_lov_new3[] dengan nilai total (jika diperlukan)
  const jumlahLovNew3Inputs = document.querySelectorAll('input[name="jumlah_lov_new3[]"]');
  jumlahLovNew3Inputs.forEach(input => {
      input.value = total;
  });

  return total;
}

// Fungsi untuk menghapus baris di tabel tol
function deleteRowTol(button) {
  const row = button.closest("tr");
  row.remove();
  updateRowNumbersTol(); // Update row numbers after deletion
  calculateGrandTotalTol(); // Recalculate grand total after deletion
}

// Fungsi untuk memperbarui nomor urut setelah penghapusan atau penambahan baris
function updateRowNumbersTol() {
  const rows = document.querySelectorAll("#tolTable tbody tr");
  rows.forEach((row, index) => {
    row.querySelector("td:first-child").textContent = index + 1;
  });
}

// Fungsi untuk reset tabel tol
function resetTableTol() {
  const tableBody = document.querySelector("#tolTable tbody");
  tableBody.innerHTML = ""; // Clear all rows
  addRowTol(); // Add a new default row
  calculateGrandTotalTol(); // Reset grand total
}


