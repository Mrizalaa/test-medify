<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        let dataTableObj = $('#table').DataTable({
            searching: false,
            order: [[1, 'desc']], // urut berdasarkan Kode
        });

        getData();

        $('.btn-get-data').click(function() {
            getData();
        });

        function getData() {
            $('#loading-filter').show();

            let filter_kode      = $('#filter-kode').val();
            let filter_nama      = $('#filter-nama').val();
            let filter_harga_min = $('#filter-harga-min').val();
            let filter_harga_max = $('#filter-harga-max').val();

            dataTableObj.clear().draw();

            $.ajax({
                url: '{{ url("master-items/search") }}',
                dataType: 'json',
                method: 'GET',
                tryCount: 0,
                retryLimit: 3,
                data: {
                    kode: filter_kode,
                    nama: filter_nama,
                    hargamin: filter_harga_min,
                    hargamax: filter_harga_max
                },
                success: function(results) {

                    console.log('RESULTS AJAX:', results);

                    let data = results.data || [];

                    $.each(data, function(index, item) {

                        let harga_jual = item.harga_jual; // dari backend

                        // Foto
                        let foto = item.foto_url
                            ? `<img src="${item.foto_url}" width="80">`
                            : 'No Image';

                        // Kategori
                        let kategori = item.kategori_nama && item.kategori_nama !== ''
                            ? item.kategori_nama
                            : '-';

                        // Tombol view
                        let htmlView = `<a href="{{ url('master-items/view/') }}/${item.kode}" class="btn btn-primary btn-sm">View</a>`;

                        let rowData = [
                            foto,
                            item.kode,
                            item.nama,
                            item.jenis,
                            kategori,          
                            item.harga_beli,
                            harga_jual,
                            item.supplier,
                            htmlView
                        ];

                        dataTableObj.row.add(rowData);
                    });

                    dataTableObj.draw(false);
                    $('#loading-filter').hide();
                },

                error: function(xhr, textStatus, errorThrown) {
                    console.error('AJAX ERROR:', textStatus, errorThrown, xhr.status, xhr.responseText);
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                    alert('Terjadi kesalahan server, tidak dapat mengambil data');
                    $('#loading-filter').hide();
                }
            });
        }
    });
</script>
