// @ts-nocheck

// Fungsi untuk menampilkan atau menyembunyikan sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleButton = document.querySelector('.toggle-btn-open');

    sidebar.classList.toggle('show');
    content.classList.toggle('shift');

    // Sembunyikan tombol ketika sidebar dibuka
    if (sidebar.classList.contains('show')) {
        toggleButton.classList.add('hide');
    } else {
        toggleButton.classList.remove('hide');
    }
}

// ✅ Fungsi untuk mencari pasien berdasarkan no_register
document.getElementById('btn-search').addEventListener('click', async () => {
    const noRegister = document.getElementById('no_reg_pasien').value.trim();

    if (noRegister) {
        try {
            const response = await fetch(`/register/search?no_reg_pasien=${noRegister}`);
            if (!response.ok) throw new Error('Data tidak ditemukan.');

            const data = await response.json();

            document.getElementById('nama_pasien').value = data.nama_pasien;
            document.getElementById('umur').value = data.umur;
            document.getElementById('no_ruangan').value = data.no_ruangan;

        } catch (error) {
            alert(error.message);
        }
    }
});

document.addEventListener('click', (event) => {
    if (event.target.classList.contains('select-device')) {
        const deviceId = event.target.dataset.deviceId;
        console.log('Device clicked:', deviceId);
        selectDevice(deviceId);
    }
});

function selectDevice(deviceId) {
    fetch('/devices/assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            device_id: deviceId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ Hapus elemen dari DOM setelah sukses
            document.querySelector(`[data-id="${data.device_id}"]`)?.remove();
            document.querySelector('.patient-info')?.remove();

            // ✅ Buat alert box sukses
            const alertBox = document.createElement('div');
            alertBox.style.position = 'fixed';
            alertBox.style.top = '20px';
            alertBox.style.left = '50%';
            alertBox.style.transform = 'translateX(-50%)';
            alertBox.style.backgroundColor = '#4CAF50';
            alertBox.style.color = 'white';
            alertBox.style.padding = '12px 20px';
            alertBox.style.borderRadius = '8px';
            alertBox.style.zIndex = '9999';
            alertBox.style.display = 'flex';
            alertBox.style.alignItems = 'center';
            alertBox.style.gap = '10px';

            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-circle-check status-icon';
            icon.style.fontSize = '20px';
            icon.style.color = 'white';

            const message = document.createElement('span');
            message.innerText = data.message;

            alertBox.appendChild(icon);
            alertBox.appendChild(message);

            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.remove();
                window.location.href = '/infusee';
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Coba lagi nanti.');
    });
}

var currentFormId = null;

function openConfirmPopup(formId) {
    currentFormId = formId;
    document.getElementById('confirm-overlay').classList.add('active');
}

function closeConfirmPopup() {
    document.getElementById('confirm-overlay').classList.remove('active');
    currentFormId = null;
}

document.getElementById('confirmYes').addEventListener('click', () => {
    if (currentFormId) {
        document.getElementById(currentFormId).submit();
    }
    closeConfirmPopup();
});

document.getElementById('confirmNo').addEventListener('click', closeConfirmPopup);