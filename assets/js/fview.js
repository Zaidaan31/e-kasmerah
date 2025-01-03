function formatNumber(input) {
  // Remove any non-digit characters except for periods (.)
  let value = input.value.replace(/\D/g, '');
  
  // Add commas every three digits
  input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}


function handleFileSelect(event, iconId, previewId, placeholderId, downloadId, resetId) {
  const files = event.target.files;
  const file = files[0];

  // Define accepted file types
  const acceptedImageTypes = ['image/jpeg', 'image/png'];
  const acceptedDocumentTypes = ['application/pdf', 'application/msword'];

  if (file) {
    const fileType = file.type;

    if (acceptedImageTypes.includes(fileType) || acceptedDocumentTypes.includes(fileType)) {
      const reader = new FileReader();
      const icon = document.getElementById(iconId);
      const preview = document.getElementById(previewId);
      const placeholder = document.getElementById(placeholderId);
      const downloadButton = document.getElementById(downloadId);
      const resetButton = document.getElementById(resetId);

      if (acceptedImageTypes.includes(fileType)) {
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = "block"; // Show image preview
          icon.style.display = "none"; // Hide icon
          placeholder.querySelector("p").textContent = file.name;
          downloadButton.href = e.target.result;
          downloadButton.download = file.name;

          // Enable download and reset buttons
          downloadButton.classList.add('enabled');
          resetButton.classList.add('enabled');
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
        downloadButton.classList.add('enabled');
        resetButton.classList.add('enabled');
      }
    } else {
      // Show SweetAlert if file type is not allowed
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Format file tidak mendukung. Hanya mendukung JPG, PNG, PDF, dan DOC.",
      
      });

      // Clear the file input
      event.target.value = "";
    }
  }
}
// Function to open the modal and display the image
function openModal(imageUrl, imageName) {
  var modal = document.getElementById("imageModal");
  var modalImg = document.getElementById("modalImage");
  var captionText = document.getElementById("modalCaption");

  modal.style.display = "block";
  modalImg.src = imageUrl;
  captionText.innerHTML = imageName;
}

// Function to close the modal
function closeModal() {
  var modal = document.getElementById("imageModal");
  modal.style.display = "none";
}

// Close modal if clicked outside of it
window.onclick = function(event) {
  const modal = document.getElementById("modal");
  if (modal && event.target == modal) {
      closeModal();
  }
}





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
