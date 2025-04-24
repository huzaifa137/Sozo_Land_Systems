@include('includes.header')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> All Houses </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Filter and Sort</li>
                </ol>
            </nav>
        </div>

        <style>
            .uniform-img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                object-position: center;
                transition: transform 0.3s ease-in-out;
            }

            .image-card {
                position: relative;
                overflow: hidden;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                cursor: pointer;
            }

            .hover-info {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(0, 0, 0, 0.6);
                color: white;
                padding: 10px;
                font-size: 14px;
                opacity: 0;
                transition: opacity 0.3s ease-in-out;
            }

            .image-card:hover .hover-info {
                opacity: 1;
            }

            .image-card:hover .uniform-img {
                transform: scale(1.05);
            }

            @media (max-width: 767px) {
                .hover-info {
                    font-size: 12px;
                }
            }

            .pagination-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .pagination-container .pagination {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .pagination-container .pagination li {
                margin: 0 8px;
            }

            .pagination-container .pagination li a {
                color: #007bff;
                padding: 10px 18px;
                border: 1px solid #ddd;
                border-radius: 50px;
                font-size: 14px;
                text-decoration: none;
                display: inline-block;
                transition: background-color 0.3s, color 0.3s;
            }

            .pagination-container .pagination li a:hover {
                background-color: #007bff;
                color: white;
            }

            .pagination-container .pagination .active a {
                background-color: #007bff;
                color: white;
                pointer-events: none;
            }

            .pagination-container .pagination .disabled a {
                color: #ccc;
                pointer-events: none;
                border-color: #ddd;
            }

            @media (max-width: 767px) {
                .pagination-container .pagination li a {
                    padding: 8px 14px;
                }
            }

            .pagination-container .pagination .page-item a i {
                font-size: 16px;
            }

            .pagination-container .pagination .page-item:first-child a {
                border-top-left-radius: 50%;
                border-bottom-left-radius: 50%;
            }

            .pagination-container .pagination .page-item:last-child a {
                border-top-right-radius: 50%;
                border-bottom-right-radius: 50%;
            }
        </style>

        <div class="row mb-4">
            <div class="col-md-12">
                <form id="filterForm" class="form-inline row g-2">

                    <div class="col-md-2 mb-2">
                        <input type="text" name="location" class="form-control w-100"
                            placeholder="Search by location">
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="price" class="form-control w-100" placeholder="Price">
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="bedroom" class="form-control w-100">
                            <option value="">Bedrooms</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="land_tenure" class="form-control w-100">
                            <option value="">Land Tenure</option>
                            <option value="Customary Land Tenure"> 1. Customary Land Tenure</option>
                            <option value="Freehold Land Tenure">2. Freehold Land Tenure</option>
                            <option value="Mailo Land Tenure">3. Mailo Land Tenure</option>
                            <option value="Leasehold Land Tenure">4. Leasehold Land Tenure</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control w-100">
                            <option value="">Status</option>
                            <option value="0">Available</option>
                            <option value="10">Sold</option>
                        </select>
                    </div>

                </form>
            </div>
        </div>

        <div id="houseGallery" class="row lightGallery">
            @foreach ($houses as $house)
                @php
                    $images = json_decode($house->house_images, true);
                    $firstImage = isset($images[0]) ? asset('storage/' . $images[0]) : null;
                @endphp

                @if ($firstImage)
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="image-card">
                            <a href="{{ route('house.details', ['id' => $house->id]) }}" class="d-block">
                                <img src="{{ $firstImage }}" alt="House Thumbnail" class="uniform-img" />
                                <div class="hover-info">
                                    <div><strong>Price:</strong> {{ $house->price }}</div>
                                    <div><strong>Location:</strong> {{ $house->location }}</div>
                                    <div><strong>Land Tenure:</strong> {{ $house->LandTenure }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-12">
            <div class="pagination-container text-center">
                {{ $houses->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

<script src="/assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#filterForm input, #filterForm select').on('input change', function() {
            let formData = $('#filterForm').serialize();
            $.ajax({
                url: "{{ route('admin.sell.house.fetch') }}",
                type: "GET",
                data: formData,
                beforeSend: function() {
                    $('#houseGallery').html(
                        '<div class="col-12 text-center"><p>Loading houses...</p></div>'
                    );
                },
                success: function(data, status, xhr) {
                    const isPartial = xhr.getResponseHeader('X-Partial');
                    if (isPartial) {
                        const newHtml = $(data).find('#houseGallery').html();
                        const newPagination = $(data).find('.pagination-container').html();
                        $('#houseGallery').html(newHtml);
                        $('.pagination-container').html(newPagination);
                    } else {
                        $('#houseGallery').html(data);
                    }
                },
                error: function(xhr, status, error) {
                    $('body').html(xhr.responseText);
                }
            });
        });
    });
</script>

<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="/assets/vendors/select2/select2.min.js"></script>
<script src="/assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/hoverable-collapse.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/todolist.js"></script>
<script src="/assets/js/file-upload.js"></script>
<script src="/assets/js/typeahead.js"></script>
<script src="/assets/js/select2.js"></script>
</body>

</html>
