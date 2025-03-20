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
        return response.json(); // ✅ Harus JSON, bukan HTML
    })
    .then(data => {
        alert(data.message);
        window.location.href = '/infusee';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Coba lagi nanti.');
    });
}
