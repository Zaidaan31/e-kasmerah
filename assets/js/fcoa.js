



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

function resetUpload(iconId, previewId, placeholderId, downloadId, fileId, resetId) {
    const icon = document.getElementById(iconId);
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    const downloadButton = document.getElementById(downloadId);
    const fileInput = document.getElementById(fileId);
    const resetButton = document.getElementById(resetId);

    // Prevent default behavior of the <a> tag
    event.preventDefault();

    // Clear the file input
    fileInput.value = "";

    // Hide the preview image and show the icon
    preview.src = "#";
    preview.style.display = "none";
    icon.style.display = "flex";

    // Reset the placeholder text
    placeholder.querySelector("p").textContent = "Upload !";

    // Disable the download and reset buttons
    downloadButton.classList.remove('enabled');
    downloadButton.href = "#";
    resetButton.classList.remove('enabled');
    resetButton.href = "#";
}



// Function to open modal with image preview
function openModal(previewId) {
    const modal = document.getElementById("modal");
    const modalImage = document.getElementById("modal-image");
    const preview = document.getElementById(previewId);

    modalImage.src = preview.src;
    modal.style.display = "block";
}

// Function to close modal
function closeModal() {
    const modal = document.getElementById("modal");
    modal.style.display = "none";
}

// Close modal if clicked outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById("modal")) {
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