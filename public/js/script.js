// @ts-nocheck
function showAlert(message, type = 'success', duration = 3000) {
    const alertBox = document.createElement('div');
    alertBox.className = `custom-alert ${type}`;
    alertBox.innerHTML = `
        <i class="fa-solid fa-circle-${type === 'success' ? 'check' : 'exclamation'}"></i>
        <span>${message}</span>
    `;

    // Style dasar
    Object.assign(alertBox.style, {
        position: 'fixed',
        top: '20px',
        left: '50%',
        transform: 'translateX(-50%)',
        backgroundColor: type === 'success' ? '#4CAF50' : '#f44336',
        color: 'white',
        padding: '12px 20px',
        borderRadius: '8px',
        zIndex: '9999',
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
        opacity: '1',
        transition: 'opacity 0.5s ease',
    });

    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.style.opacity = '0';
    }, duration - 500);

    setTimeout(() => {
        alertBox.remove();
    }, duration);
}

document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('errorPopup');

    if (popup) {
    setTimeout(() => {
        popup.classList.add('fade-out'); // animasi
    }, 2000);

    setTimeout(() => {
        popup.remove(); // hapus elemen setelah animasi selesai
    }, 2500);
}
    // Fungsi untuk toggle sidebar
    const toggleButton = document.querySelector('.toggle-btn-open');
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar?.classList.toggle('show');
            content?.classList.toggle('shift');

            if (sidebar?.classList.contains('show')) {
                toggleButton.classList.add('hide');
            } else {
                toggleButton.classList.remove('hide');
            }
        });
    }

    // ✅ Event untuk tombol cari pasien
    const btnSearch = document.getElementById('btn-search');
    if (btnSearch) {
        btnSearch.addEventListener('click', async () => {
            const noRegister = document.getElementById('no_reg_pasien')?.value.trim();

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
    }

    // ✅ Event click untuk tombol pilih device
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('select-device')) {
            const deviceId = event.target.dataset.deviceId;
            console.log('Device clicked:', deviceId);
            selectDevice(deviceId);
        }
    });

    // ✅ Konfirmasi Popup Logic
    const yesBtn = document.getElementById('confirmYes');
    const noBtn = document.getElementById('confirmNo');

    if (yesBtn) {
        yesBtn.addEventListener('click', () => {
            if (currentFormId) {
                document.getElementById(currentFormId)?.submit();
            }
            closeConfirmPopup();
        });
    }

    if (noBtn) {
        noBtn.addEventListener('click', () => {
            closeConfirmPopup();
        });
    }
});


// 🔄 Fungsi pilih device
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
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-id="${data.device_id}"]`)?.remove();
            document.querySelector('.patient-info')?.remove();

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

// 🔄 Variabel & fungsi konfirmasi popup
var currentFormId = null;

function openConfirmPopup(formId) {
    currentFormId = formId;
    document.getElementById('confirm-overlay')?.classList.add('active');
}

function closeConfirmPopup() {
    document.getElementById('confirm-overlay')?.classList.remove('active');
    currentFormId = null;
}
