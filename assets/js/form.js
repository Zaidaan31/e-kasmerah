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
        <select name="keterangan[]" style="width:200px" class="select-box" onchange="handleSelectChange(this)" required>
        <option value="">Select</option> 
            ${options}
        </select>
        </td>
        <td>
        <textarea name="deskripsi[]" style="width:200px;height:40px" placeholder="Deskripsi"></textarea>
        </td>
        <td>
            <div class="input-container">
                <span class="currency-label">Rp.</span>
                <input type="number" name="jumlah[]" style="width:200px" oninput="updateRowTotal(this); calculateGrandTotal();" step="any" placeholder="Jumlah" >
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
        <input type="file" id="upload-bukti-${rowCount}" name="bukti[]" onchange="handleFileSelect(event, 'icon-bukti-${rowCount}', 'preview-bukti-${rowCount}', 'placeholder-bukti-${rowCount}', 'download-bukti-${rowCount}', 'reset-bukti-${rowCount}')" />
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
      const jumlahInput = row.querySelector('input[name="jumlah[]"]');
      if (jumlahInput) {
        jumlahInput.addEventListener('input', calculateGrandTotal);
      }
      
      // Similarly for jumlahBukti and jumlahTol (if you implement them in the row)
    })
    .catch((error) => console.error("Error fetching options:", error));
}



function handleSelectChange(selectElement) {
  const selectedValue = parseInt(selectElement.value);
  const row = selectElement.closest("tr");
  // const jumlahCell = row.querySelector('input[name="jumlah[]"]');
  const buktiContainer = row.querySelector(".upload-wrapper");

  const SPECIFIC_IDS = [1, 9, 13, 14];
 

  // Hide detail tables if the selected value is not specific
  if (!SPECIFIC_IDS.includes(selectedValue) ) {
    hideAllDetailTables();
  }

  if (SPECIFIC_IDS.includes(selectedValue)) {
    // When a specific ID is selected
    buktiContainer.innerHTML = `

      <a href="javascript:void(0);" class="btn btn-info" onclick="showDetailBukti();">Upload Bukti!</a>
      <input type="file" id="upload-bukti" name="bukti[]" style="display: none;" />
    `;
  }  
  else {
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
                <input type="file" id="upload-bukti-${rowIndex}" name="bukti[]" onchange="handleFileSelect(event, 'icon-bukti-${rowIndex}', 'preview-bukti-${rowIndex}', 'placeholder-bukti-${rowIndex}', 'download-bukti-${rowIndex}', 'reset-bukti-${rowIndex}')" />
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

  detailBuktiTable.style.display = "none"; // Hide the bukti table
 
}



function updateRowTotal(input) {
  calculateGrandTotal();
}



function calculateGrandTotal() {
  const rows = document.querySelectorAll("#barangTable tbody tr");
  let grandTotal = 0;

  // Hitung total dari input 'jumlah[]', 'jumlahBukti[]', dan 'jumlahTol[]'
  rows.forEach((row) => {
    const jumlahInput = row.querySelector("input[name='jumlah[]']");
    const jumlahBuktiInput = row.querySelector("input[name='jumlahBukti[]']");
    const jumlahTolInput = row.querySelector("input[name='jumlahTol[]']");
    console.log(jumlahTolInput);
    if (jumlahInput && jumlahInput.value) {
      grandTotal += parseFloat(jumlahInput.value) || 0;
    }
    if (jumlahBuktiInput && jumlahBuktiInput.value) {
      grandTotal += parseFloat(jumlahBuktiInput.value) || 0;
    }
    if (jumlahTolInput && jumlahTolInput.value) {
      grandTotal += parseFloat(jumlahTolInput.value) || 0;
    }
  });

  // Update tampilan grand total
  document.getElementById("grandTotal").textContent =
    "Rp. " + formatNumber(grandTotal);
  document.getElementById("hiddenTotal").value = grandTotal;

  // Konversi total menjadi kata-kata dan perbarui tampilan
  const grandTotalWords = numberToWords(grandTotal);
  document.getElementById("grandTotalWords").textContent = grandTotalWords;
  document.getElementById("hiddenTerbilang").value = grandTotalWords;
}


// Call initializeTable when the page loads
window.onload = function () {
  initializeTable();
};

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
}

// Function to reset the file input and UI
function resetUpload(iconId, previewId, placeholderId, downloadId, fileId) {
  console.log("Resetting upload for:", fileId);
  const icon = document.getElementById(iconId);
  const preview = document.getElementById(previewId);
  const placeholder = document.getElementById(placeholderId);
  const downloadButton = document.getElementById(downloadId);
  const fileInput = document.getElementById(fileId);

  // Clear the file input
  fileInput.value = "";

  // Hide the preview image and show the icon
  preview.src = "#";
  preview.style.display = "none";
  icon.style.display = "flex";

  // Reset the placeholder text
  placeholder.querySelector("p").textContent = "Upload !";

  // Disable the download and reset buttons
  downloadButton.classList.remove("enabled");
  downloadButton.href = "#";
}

// Function to open modal with image preview
function openModal(previewId) {
  var modal = document.getElementById("imageModal");
  var modalImg = document.getElementById("modalImage");
  var captionText = document.getElementById("modalCaption");
  var previewImg = document.getElementById(previewId);

  modal.style.display = "block";
  modalImg.src = previewImg.src;
  captionText.innerHTML = previewImg.alt;
}

// Close the modal
function closeModal() {
  var modal = document.getElementById("imageModal");
  modal.style.display = "none";
}

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

// Function to show detail tol
// function showDetailTol() {
//   const tolTable = document.getElementById("tolTable");
//   const isDisplayed = tolTable.style.display === "block"; // Check if the table is already displayed

//   hideAllDetailTables(); // Hide other tables first

//   // If detail tol table is not displayed, show it
//   if (!isDisplayed) {
//     tolTable.style.display = "block"; // Show the table
//     resetTableTol(); // Reset the table to ensure starting fresh
//   }
// }


// Function to add a new row to the table
// function addRowbukti() {
//   let rowCount = document.querySelectorAll("#buktiTable tbody tr").length + 1;
//   const tableBody = document.querySelector("#buktiTable tbody");
//   const row = document.createElement("tr");

//   row.innerHTML = `
//       <td>${rowCount}</td>
//       <td>
//           <textarea name="deskripsibukti[]" class="form-control" placeholder="Deskripsi"></textarea>
//       </td>
//       <td>
//           <div class="input-container">
//               <span class="currency-label">Rp.</span>
//               <input type="number" name="biayabukti[]" class="form-control" oninput="updateRowTotalbukti(this)" step="any" placeholder="Biaya">
//           </div>
//       </td>
//       <td>
//           <div class="upload-wrapper">
//               <div class="upload-section">
//                   <div class="upload-content">
//                       <div>
//                           <i class="fa-regular fa-file uploadbukti-icon" id="icon-buktidetail-${rowCount}"></i>
//                           <img id="preview-buktidetail-${rowCount}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-buktidetail-${rowCount}')" style="display: none;" />
//                       </div>
//                       <div class="upload-placeholder" id="placeholder-buktidetail-${rowCount}" onclick="document.getElementById('upload-buktidetail-${rowCount}').click()">
//                           <p>Upload !</p>
//                       </div>
//                   </div>
//                   <input type="file" id="upload-buktidetail-${rowCount}" name="buktidetail[]" onchange="handleFileSelect(event, 'icon-buktidetail-${rowCount}', 'preview-buktidetail-${rowCount}', 'placeholder-buktidetail-${rowCount}', 'download-buktidetail-${rowCount}', 'reset-buktidetail-${rowCount}')" />
//                   <a class="download-button" id="download-buktidetail-${rowCount}" href="#" download><i class="fa-solid fa-download"></i></a>
//                   <button type="button" class="reset-button" id="reset-buktidetail-${rowCount}" onclick="resetUpload('icon-buktidetail-${rowCount}', 'preview-buktidetail-${rowCount}', 'placeholder-buktidetail-${rowCount}', 'download-buktidetail-${rowCount}', 'upload-buktidetail-${rowCount}')"><i class="fa-solid fa-arrows-rotate"></i></button>
//               </div>
//               <div><small style="font-size: 11px; color: #666;">*Catatan : Hanya bisa mengunggah file dengan format: JPG, PNG, PDF, DOC*</small></div>
//           </div>
//       </td>
//       <td>
//           <button type="button" class="btn btn-danger btn-sm" onclick="deleteRowbukti(this)"><i class="fa-solid fa-trash"></i></button>
//       </td>
//   `;

//   tableBody.appendChild(row);
//   updateRowNumbersbukti(); // Ensure row numbers are updated
//   calculateGrandTotalbukti(); // Calculate grand total after adding the row
// }

// function updateRowTotalbukti(input) {
//   calculateGrandTotalbukti(); // Trigger recalculation of the grand total
// }

// function calculateGrandTotalbukti() {
//   let total = 0;
//   const biayaInputs = document.querySelectorAll('input[name="biayabukti[]"]');

//   biayaInputs.forEach((input) => {
//     const value = parseFloat(input.value) || 0;
//     total += value;
//   });

//   // Update total display dan input jumlahBukti[]
//   document.getElementById("grandTotalbukti").innerText = "Rp. " + total.toLocaleString("id-ID");
//   document.getElementById("hiddenTotalbukti").value = total; // Set hidden input value

//   // Update input jumlahBukti[] jika ada
//   const jumlahBuktiInput = document.querySelector('input[name="jumlahBukti[]"]');
//   if (jumlahBuktiInput) {
//     jumlahBuktiInput.value = total;
//   }
// }


// function deleteRowbukti(button) {
//   const row = button.closest("tr");
//   row.remove();
//   updateRowNumbersbukti(); // Update row numbers after deletion
//   calculateGrandTotalbukti(); // Recalculate grand total after row deletion
// }

// function updateRowNumbersbukti() {
//   const rows = document.querySelectorAll("#buktiTable tbody tr");
//   rows.forEach((row, index) => {
//     row.querySelector("td:first-child").textContent = index + 1; // Update the row number
//   });
// }

// // Reset function to clear all fields
// function resetTablebukti() {
//   const tableBody = document.querySelector("#buktiTable tbody");
//   tableBody.innerHTML = ""; // Clear all rows
//   addRowbukti(); // Add a new default row
//   calculateGrandTotalbukti(); // Reset grand total
// }

// // Fungsi untuk mengambil opsi gerbang tol dari file PHP
// async function fetchGerbangTolOptions() {
//   try {
//     const response = await fetch("../includes/get_option_tol.php"); // Ganti dengan path ke file PHP
//     if (!response.ok) {
//       throw new Error("Network response was not ok");
//     }
//     const options = await response.text();
//     return options; // Mengembalikan opsi dalam format HTML
//   } catch (error) {
//     console.error("Error fetching options:", error);
//     return ""; // Jika terjadi error, kembalikan string kosong
//   }
// }

// function addRowTol() {
//   let rowCount = document.querySelectorAll("#tolTable tbody tr").length + 1;
//   const tableBody = document.querySelector("#tolTable tbody");
//   const row = document.createElement("tr");

//   // Fetch options from PHP
//   fetch("includes/get_option_tol.php")
//     .then((response) => response.text())
//     .then((options1) => {
//       row.innerHTML = `
//               <td>${rowCount}</td>
//               <td>
//                   <select name="gerbangTol[]" style="width:200px" class="select-box" >
//                       <option value="">Select</option> 
//                       ${options1}  <!-- Displaying options from PHP -->
//                   </select>
//               </td>
//               <td>
//                   <div class="input-container">
//                       <span class="currency-label">Rp.</span>
//                       <input type="number" name="tarif[]" class="form-control" step="any" placeholder="Biaya Tol"  readonly>
//                   </div>
//               </td>
//               <td>
//             <div class="upload-wrapper">
//               <div class="upload-section">
//                   <div class="upload-content">
//                       <div>
//                           <i class="fa-regular fa-file upload-icon" id="icon-tol-${rowCount}"></i>
//                           <img id="preview-tol-${rowCount}" class="image-preview" src="#" alt="Preview" onclick="openModal('preview-tol-${rowCount}')" style="display: none;" />
//                       </div>
//                       <div class="upload-placeholder" id="placeholder-tol-${rowCount}" onclick="document.getElementById('upload-tol-${rowCount}').click()">
//                           <p>Upload !</p>
//                       </div>
//                   </div>
//                   <input type="file" id="upload-tol-${rowCount}" name="tolBukti[]" onchange="handleFileSelect(event, 'icon-tol-${rowCount}', 'preview-tol-${rowCount}', 'placeholder-tol-${rowCount}', 'download-tol-${rowCount}', 'reset-tol-${rowCount}')" />
//                   <a class="download-button" id="download-tol-${rowCount}" href="#" download><i class="fa-solid fa-download"></i></a>
//                   <button type="button" class="reset-button" id="reset-tol-${rowCount}" onclick="resetUpload('icon-tol-${rowCount}', 'preview-tol-${rowCount}', 'placeholder-tol-${rowCount}', 'download-tol-${rowCount}', 'upload-tol-${rowCount}')"><i class="fa-solid fa-arrows-rotate"></i></button>
//               </div>
//               <div><small class="catatan">*Catatan : Hanya format yang didukung*</small></div>
//             </div>
//           </td>
//               <td>
//                   <button type="button" class="btn btn-danger btn-sm" onclick="deleteRowTol(this)"><i class="fa-solid fa-trash"></i></button>
//               </td>
//           `;

//       tableBody.appendChild(row);
//       updateRowNumbersTol(); // Update row numbers

//       // Attach change event listener to the newly added select element
//       const selectElement = row.querySelector('select[name="gerbangTol[]"]');
//       selectElement.addEventListener("change", function () {
//         var selectedOption = this.options[this.selectedIndex];
//         var biaya = selectedOption.dataset.tarif; // Get tarif from data-tarif

//         console.log("Selected Option:", selectedOption); // Debugging: log selected option
//         console.log("Biaya:", biaya); // Debugging: log the tarif value

//         // Set the corresponding input to the biaya value
//         if (biaya) {
//           // Check if biaya is defined
//           this.closest("tr").querySelector('input[name="tarif[]"]').value =
//             biaya;
//         } else {
//           console.log("Biaya is undefined for selected option."); // Debugging: log if biaya is undefined
//         }
//       });

//       // Re-initialize Select2 for the new select box
//       $(selectElement).select2({
//         width: "100%",
//         placeholder: "Select an option",
//         allowClear: true,
//       });
//     })
//     .catch((error) => {
//       console.error("Error fetching options:", error);
//     });
// }

// $(document).ready(function () {
//   // Initialize Select2 on all select boxes
//   $(".select-box").select2({
//     width: "100%",
//     placeholder: "Select an option",
//     allowClear: true,
//   });

//   // Event listener for changes on gerbangTol select boxes
//   $(document).on("change", 'select[name="gerbangTol[]"]', function () {
//     var selectedOption = $(this).find("option:selected");
//     var tarif = selectedOption.data("tarif");

//     // Set the corresponding input value
//     $(this).closest("tr").find('input[name="tarif[]"]').val(tarif);

//     // Calculate the grand total whenever a tarif is updated
//     calculateGrandTotalTol();
//   });

//   // Event listener for changes in tarif inputs
//   $(document).on("input", 'input[name="tarif[]"]', function () {
//     // Calculate the grand total whenever the input changes
//     calculateGrandTotalTol();
//   });
// });

// // Fungsi untuk memperbarui total per baris
// function updateRowTotalTol(input) {
//   calculateGrandTotalTol(); // Recalculate the grand total
// }

// // Fungsi untuk menghitung total keseluruhan di tabel tol
// function calculateGrandTotalTol() {
//   let total = 0;
//   const biayaInputs = document.querySelectorAll('input[name="tarif[]"]');

//   biayaInputs.forEach((input) => {
//     const value = parseFloat(input.value) || 0;
//     total += value;
//   });

//   // Update total display dan input jumlahTol[]
//   document.getElementById("grandTotalTol").innerText = "Rp. " + total.toLocaleString("id-ID");
//   document.getElementById("hiddenTotalTol").value = total; // Set hidden input value

//   // Update input jumlahTol[] jika ada
//   const jumlahTolInput = document.querySelector('input[name="jumlahTol[]"]');
//   if (jumlahTolInput) {
//     jumlahTolInput.value = total;
//   }
// }


// // Fungsi untuk menghapus baris di tabel tol
// function deleteRowTol(button) {
//   const row = button.closest("tr");
//   row.remove();
//   updateRowNumbersTol(); // Update row numbers after deletion
//   calculateGrandTotalTol(); // Recalculate grand total after deletion
// }

// // Fungsi untuk memperbarui nomor urut setelah penghapusan atau penambahan baris
// function updateRowNumbersTol() {
//   const rows = document.querySelectorAll("#tolTable tbody tr");
//   rows.forEach((row, index) => {
//     row.querySelector("td:first-child").textContent = index + 1;
//   });
// }

// // Fungsi untuk reset tabel tol
// function resetTableTol() {
//   const tableBody = document.querySelector("#tolTable tbody");
//   tableBody.innerHTML = ""; // Clear all rows
//   addRowTol(); // Add a new default row
//   calculateGrandTotalTol(); // Reset grand total
// }