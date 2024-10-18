let cropper;
const coverInput = document.getElementById('cover');
const coverPreview = document.getElementById('cover-preview');
const cropActions = document.getElementById('crop-actions');
const croppedImageInput = document.getElementById('cropped_image');
const croppedPreview = document.getElementById('cropped-preview');
const resetAction = document.getElementById('reset-action');

// Reset image cropping
function resetCrop() {
    if (cropper) {
        cropper.destroy();
    }
    coverPreview.src = '';
    coverPreview.style.display = 'none';
    croppedPreview.style.display = 'none';
    cropActions.style.display = 'none';
    resetAction.style.display = 'none'; // Sembunyikan tombol reset
    coverInput.value = ''; // Menghapus file di input form

    cropper = new Cropper(coverPreview, {
        aspectRatio: 9 / 16, // Set NaN untuk bebas (tidak mengunci rasio)
        viewMode: 1, // Membatasi panning gambar di dalam kanvas
        dragMode: 'move', // Mengaktifkan mode geser
        ready() {
            // Set ukuran crop box setelah gambar siap
            cropper.setCropBoxData({
                width: 171, // Lebar 200 px
                height: 264, // Tinggi 400 px
            });
        }
    });
}

coverInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function (event) {
        coverPreview.src = event.target.result;
        coverPreview.style.display = 'block';

        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(coverPreview, {
            aspectRatio: NaN, // Set NaN untuk bebas (tidak mengunci rasio)
            viewMode: 1, // Membatasi panning gambar di dalam kanvas
            dragMode: 'move', // Mengaktifkan mode geser
            ready() {
                // Set ukuran crop box setelah gambar siap
                cropper.setCropBoxData({
                    width: 171, // Lebar 200 px
                    height: 264, // Tinggi 400 px
                });
            }
        });

        // Tampilkan tombol "Selesai" dan "Batal" setelah gambar dimuat di cropper
        cropActions.style.display = 'block';
        resetAction.style.display = 'none'; // Sembunyikan tombol reset awalnya
    };

    reader.readAsDataURL(file);
});

// Fungsi ketika tombol "Selesai" ditekan
document.getElementById('crop-done').addEventListener('click', function () {
    const canvas = cropper.getCroppedCanvas();
    croppedPreview.src = canvas.toDataURL(); // Tampilkan gambar hasil crop
    croppedPreview.style.display = 'block'; // Tampilkan preview cropped image
    croppedImageInput.value = canvas.toDataURL(); // Simpan hasil crop ke input hidden
    cropActions.style.display = 'none'; // Sembunyikan tombol selesai
    coverPreview.style.display = 'none'; // Sembunyikan gambar preview asli

    // Setelah selesai cropping, tampilkan tombol reset
    resetAction.style.display = 'block';
});

// Fungsi ketika tombol "Batal" ditekan
document.getElementById('crop-cancel').addEventListener('click', function () {
    resetCrop(); // Reset crop, sembunyikan elemen terkait, dan hapus file
});

// Fungsi ketika tombol "Reset" ditekan
document.getElementById('crop-reset').addEventListener('click', function () {
    if (cropper) {
        cropper.reset(); // Reset crop area
    }
    croppedPreview.style.display = 'none'; // Sembunyikan preview hasil crop
    cropActions.style.display = 'block'; // Tampilkan kembali tombol Selesai & Batal
    coverPreview.style.display = 'block'; // Tampilkan kembali gambar preview asli
    resetAction.style.display = 'none'; // Sembunyikan tombol reset kembali
});
