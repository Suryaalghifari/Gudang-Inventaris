<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Barang</h6><button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="jenisbarang" class="form-label">Jenis Barang</label>
                            <select name="jenisbarang" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($jenisbarang as $jb)
                                    <option value="{{$jb->jenisbarang_id}}">{{$jb->jenisbarang_nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="satuan" class="form-label">Satuan Barang</label>
                            <select name="satuan" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($satuan as $s)
                                <option value="{{$s->satuan_id}}">{{$s->satuan_nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="merk" class="form-label">Merk Barang</label>
                            <select name="merk" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($merk as $m)
                                <option value="{{$m->merk_id}}">{{$m->merk_nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                            <input type="text" name="harga" class="form-control" placeholder="" oninput="validateJumlahU()">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="title" class="form-label">Foto Barang</label>
                            <center>
                                <img src="{{url('/assets/default/barang/image.png')}}" width="80%" alt="profile-user" id="outputImg" class="">
                            </center>
                            <input class="form-control mt-5" id="GetFile" name="photo" type="file" onchange="VerifyFileNameAndFileSize()" accept=".png,.jpeg,.jpg,.svg">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">Simpan <i class="fe fe-check"></i></a>
                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
            </div>
        </div>
    </div>
</div>

@section('formTambahJS')
<script>
function checkForm() {
    const kode = $("input[name='kode']").val().trim();
    const nama = $("input[name='nama']").val().trim();
    const harga = $("input[name='harga']").val().trim();
    const foto = $('#GetFile')[0].files[0];

    // Lakukan validasi lainnya sesuai kebutuhan
    if (kode === "") {
        validasi('Kode Barang wajib di isi!', 'warning');
        $("input[name='kode']").addClass('is-invalid');
        return false;
    } else if (kode.includes(' ')) {
        validasi('Kode Barang Tidak Boleh Mengandung Spasi', 'warning');
        $("input[name='kode']").addClass('is-invalid');
        return false;
    } else if (nama === "") {
        validasi('Nama Barang wajib di isi!', 'warning');
        $("input[name='nama']").addClass('is-invalid');
        return false;
    } else if (harga === "") {
        validasi('Harga Barang wajib di isi!', 'warning');
        $("input[name='harga']").addClass('is-invalid');
        return false;
    } else if (!/^\d+$/.test(harga)) {
        validasi('Harga Barang hanya boleh berisi angka!', 'warning');
        $("input[name='harga']").addClass('is-invalid');
        return false;
    } else if (foto) { // Jika ada file yang diupload
        const validExtensions = ['png'];
        const fileName = foto.name;
        const fileSize = foto.size;
        const fileExt = fileName.split('.').pop().toLowerCase();

        if ($.inArray(fileExt, validExtensions) == -1) {
            validasi('Format bukan gambar! Harap pilih file dengan ekstensi .png', 'warning');
            document.getElementById('GetFile').value = null;
            return false;
        }

        if (fileSize > 5024 * 5024) {
            validasi('Ukuran file terlalu besar! Maksimum ukuran file adalah 5 MB.', 'warning');
            document.getElementById('GetFile').value = null;
            return false;
        }
    }

    // Lakukan pengecekan tambahan jika diperlukan
    // Misalnya, cek apakah kode barang sudah ada di dalam tabel
    var existingKodes = table.columns(2).data().eq(0).toArray(); // Get all existing kode_barang from the table

    if (existingKodes.includes(kode)) {
        // Jika kode barang sudah ada di dalam tabel, tampilkan pesan kesalahan menggunakan validasi()
        validasi("Kode Barang Sudah Ada", "warning");
        return false;
    } else {
        // Jika semua validasi terpenuhi dan kode barang unik, submit form
        submitForm();
    }
}

function submitForm() {
    const kode = $("input[name='kode']").val().trim();
    const nama = $("input[name='nama']").val().trim();
    const jenisbarang = $("select[name='jenisbarang']").val();
    const satuan = $("select[name='satuan']").val();
    const merk = $("select[name='merk']").val();
    const harga = $("input[name='harga']").val().trim();
    const foto = $('#GetFile')[0].files;

    var fd = new FormData();
    fd.append('foto', foto[0]);
    fd.append('kode', kode);
    fd.append('nama', nama);
    fd.append('jenisbarang', jenisbarang);
    fd.append('satuan', satuan);
    fd.append('merk', merk);
    fd.append('harga', harga);

    $.ajax({
        type: 'POST',
        url: "{{route('barang.store')}}",
        processData: false,
        contentType: false,
        dataType: 'json',
        data: fd,
        success: function(data) {
            $('#modaldemo8').modal('toggle');
            swal({
                title: "Berhasil ditambah!",
                type: "success"
            });
            table.ajax.reload(null, false);
            reset();
        }
    });
}

function resetValid() {
    $("input[name='kode']").removeClass('is-invalid');
    $("input[name='nama']").removeClass('is-invalid');
    $("select[name='jenisbarang']").removeClass('is-invalid');
    $("select[name='satuan']").removeClass('is-invalid');
    $("select[name='merk']").removeClass('is-invalid');
    $("input[name='harga']").removeClass('is-invalid');
};

function reset() {
    resetValid();
    $("input[name='kode']").val('');
    $("input[name='nama']").val('');
    $("select[name='jenisbarang']").val('');
    $("select[name='satuan']").val('');
    $("select[name='merk']").val('');
    $("input[name='harga']").val('');
    $("#outputImg").attr("src", "{{url('/assets/default/barang/image.png')}}");
    $("#GetFile").val('');
    setLoading(false);
}

function setLoading(bool) {
    if (bool == true) {
        $('#btnLoader').removeClass('d-none');
        $('#btnSimpan').addClass('d-none');
    } else {
        $('#btnSimpan').removeClass('d-none');
        $('#btnLoader').addClass('d-none');
    }
}

function fileIsValid(fileName) {
    var ext = fileName.match(/\.([^\.]+)$/)[1];
    ext = ext.toLowerCase();
    var isValid = true;
    switch (ext) {
        case 'png':
        case 'jpeg':
        case 'jpg':
        case 'svg':
            break;
        default:
            this.value = '';
            isValid = false;
    }
    return isValid;
}

function VerifyFileNameAndFileSize() {
    var file = document.getElementById('GetFile').files[0];
    if (file != null) {
        var fileName = file.name;
        if (fileIsValid(fileName) == false) {
            validasi('Format bukan gambar!', 'warning');
            document.getElementById('GetFile').value = null;
            return false;
        }
        var content;
        var size = file.size;
        if ((size != null) && ((size / (5024 * 5024)) > 3)) {
            validasi('Ukuran Maximum 5 MB', 'warning');
            document.getElementById('GetFile').value = null;
            return false;
        }
        var ext = fileName.match(/\.([^\.]+)$/)[1];
        ext = ext.toLowerCase();
        // $(".custom-file-label").addClass("selected").html(file.name);
        document.getElementById('outputImg').src = window.URL.createObjectURL(file);
        return true;
    } else
        return false;
}
</script>
@endsection
