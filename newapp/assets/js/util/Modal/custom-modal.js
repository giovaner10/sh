class CustomModal {
    constructor(modalId) {
        this.modalId = modalId;
        this.modalElement = document.getElementById(modalId);
        this.closeButtons = this.modalElement.querySelectorAll('.custom-close, .btn-secondary');
        
        this.init();
    }

    init() {
        this.closeButtons.forEach(button => {
            button.addEventListener('click', () => this.close());
        });
    }

    open() {
        this.modalElement.classList.add('show');
    }

    close() {
        this.modalElement.classList.remove('show');
    }

    setContent(title, content) {
        this.modalElement.querySelector('.modal-title').innerText = title;
        this.modalElement.querySelector('.custom-modal-body').innerHTML = content;
    }
}

// Initialize and control the custom modal
document.addEventListener('DOMContentLoaded', function () {
    const customModal = new CustomModal('modalVisualizarDocumentoFuncionario');

    // Example usage: Opening the modal with content
    document.getElementById('openModalButton').addEventListener('click', function () {
        customModal.setContent('Visualização de Documento', '<div id="pdfContainer"></div>');
        customModal.open();
    });

    // Example usage: Closing the modal
    document.getElementById('closeModalButton').addEventListener('click', function () {
        customModal.close();
    });
});
