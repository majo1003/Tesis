document.addEventListener("DOMContentLoaded", () => {
    const uploadArea = document.getElementById("uploadArea");
    const fileInput = document.getElementById("fileInput");
    const fileNameDisplay = document.getElementById("fileName");
    const submitButton = document.getElementById("submitButton");
  
    // Manejador para arrastrar y soltar
    uploadArea.addEventListener("dragover", (event) => {
      event.preventDefault();
      uploadArea.classList.add("dragover");
    });
  
    uploadArea.addEventListener("dragleave", () => {
      uploadArea.classList.remove("dragover");
    });
  
    uploadArea.addEventListener("drop", (event) => {
      event.preventDefault();
      uploadArea.classList.remove("dragover");
  
      const file = event.dataTransfer.files[0];
      if (file) {
        fileInput.files = event.dataTransfer.files; // Asignar archivo al input
        fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
      }
    });
  
    // Manejador para selección manual
    fileInput.addEventListener("change", () => {
      const file = fileInput.files[0];
      if (file) {
        fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
      }
    });
  
    // Manejador para enviar el archivo
    submitButton.addEventListener("click", () => {
      if (fileInput.files.length === 0) {
        alert("Por favor, selecciona un archivo antes de enviar.");
      } else {
        alert(`Enviando archivo: ${fileInput.files[0].name}`);
        // Aquí puedes implementar la lógica de subida al servidor.
      }
    });
  });
  