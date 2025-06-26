<div class="card shadow-none border">
    <div class="card-body">
        <h4 class="fw-semibold mb-3">Introduction</h4>
        <p>{{ $intro??'' }}</p>
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-briefcase text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">{{ $institute??'' }}</h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-mail text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">{{ $email??'' }}</h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-phone text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">{{ $phone??'' }}</h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-2">
                <i class="ti ti-map-pin text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">{{ $address??'' }}</h6>
            </li>
        </ul>
    </div>
</div>