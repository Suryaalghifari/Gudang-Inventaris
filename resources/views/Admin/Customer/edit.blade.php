<!-- MODAL EDIT -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Supplier Barang</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idcustomerU">
                <div class="form-group">
                    <label for="customerU" class="form-label">Supplier Barang <span class="text-danger">*</span></label>
                    <input type="text" name="customerU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="notelpU" class="form-label">No Telepon</label>
                    <input type="text" name="notelpU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="alamatU" class="form-label">Alamat</label>
                    <textarea name="alamatU" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">Simpan Perubahan <i class="fe fe-check"></i></a>
                <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
            </div>
        </div>
    </div>
</div>

@section('formEditJS')
<script>
    function checkFormU() {
        const customer = $("input[name='customerU']").val().trim();
        const notelp = $("input[name='notelpU']").val().trim();
        
        setLoadingU(true);
        resetValidU();

        // Validasi nama customer tidak mengandung angka dan tidak boleh kosong
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (customer === "") {
            validasi('Nama Customer Wajib di Isi!', 'warning');
            $("input[name='customerU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } else if (!nameRegex.test(customer)) {
            validasi('Nama Customer Tidak Boleh Mengandung Angka!', 'warning');
            $("input[name='customerU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }

        // Validasi nomor telepon hanya mengandung angka dan tidak boleh kosong
        const phoneRegex = /^[0-9]+$/;
        if (notelp === "") {
            validasi('Nomor Telepon Wajib di Isi!', 'warning');
            $("input[name='notelpU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } else if (!phoneRegex.test(notelp)) {
            validasi('Nomor Telepon Tidak Boleh Mengandung Karakter Selain Angka!', 'warning');
            $("input[name='notelpU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }

        // Jika lolos validasi, submit form
        submitFormU();
    }

    function submitFormU() {
        const id = $("input[name='idcustomerU']").val();
        const customer = $("input[name='customerU']").val().trim();
        const notelp = $("input[name='notelpU']").val().trim();
        const alamat = $("textarea[name='alamatU']").val().trim();

        $.ajax({
            type: 'POST',
            url: "{{ url('admin/customer/proses_ubah') }}/" + id,
            data: {
                customer: customer,
                notelp: notelp,
                alamat: alamat
            },
            success: function(data) {
                swal({
                    title: "Berhasil diubah!",
                    type: "success"
                });
                $('#Umodaldemo8').modal('toggle');
                table.ajax.reload(null, false);
                resetU();
            },
            error: function(xhr, status, error) {
                swal({
                    title: "Error!",
                    text: "Terjadi kesalahan saat mengirim data.",
                    type: "error"
                });
                setLoadingU(false);
            }
        });
    }

    function resetValidU() {
        $("input[name='customerU']").removeClass('is-invalid');
        $("input[name='notelpU']").removeClass('is-invalid');
        $("textarea[name='alamatU']").removeClass('is-invalid');
    }

    function resetU() {
        resetValidU();
        $("input[name='idcustomerU']").val('');
        $("input[name='customerU']").val('');
        $("input[name='notelpU']").val('');
        $("textarea[name='alamatU']").val('');
        setLoadingU(false);
    }

    function setLoadingU(bool) {
        if (bool) {
            $('#btnLoaderU').removeClass('d-none');
            $('#btnSimpanU').addClass('d-none');
        } else {
            $('#btnSimpanU').removeClass('d-none');
            $('#btnLoaderU').addClass('d-none');
        }
    }
</script>
@endsection