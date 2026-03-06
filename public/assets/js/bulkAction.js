document.addEventListener('DOMContentLoaded', function () {
    // Fungsi menampilkan toast (bisa diganti dengan Swal, atau tetap pakai alert/toast custom)
    function showToast(message, type = 'info') {
        // Jika pakai toast custom, implementasinya di sini
        // Kalau tidak, pakai alert sementara
        alert(message); // Ganti dengan toast system kamu jika ada
    }

    // Fungsi untuk menangani bulk action
    function handleBulkAction(button) {
        const selectedCheckboxes = document.querySelectorAll('.bulk-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Ambil tipe item dari tombol, default ke "item"
            const itemType = button.getAttribute('data-item-type') || 'item';
            const singular = itemType.replace(/s$/, ''); // Naive plural to singular
            const message = `Please select at least one ${singular}.`;
            showToast(message, 'error');
            return null;
        }

        return selectedIds;
    }

    // Bulk Delete - Non-AJAX Approach
    const bulkDeleteBtn = document.querySelector('.bulk-delete-btn');
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const selectedIds = handleBulkAction(this);
            if (!selectedIds) return;

            // Buat form untuk submit DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.dataset.route;

            // CSRF Token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfToken);

            // Method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Kirim semua ID
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });
    }

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.bulk-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Update indeterminate state
    const userCheckboxes = document.querySelectorAll('.bulk-checkbox');
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const allChecked = document.querySelectorAll('.bulk-checkbox:checked').length === userCheckboxes.length;
            const someChecked = document.querySelectorAll('.bulk-checkbox:checked').length > 0;

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
});