@props(['items'])

@php
    $isPaginator = $items instanceof \Illuminate\Pagination\AbstractPaginator;
    $collection = $isPaginator ? $items->getCollection() : collect($items);
@endphp

@once
<style>
/* Base container */
.cd-timeline {
    position: relative;
    padding: 2rem 0;
}

/* Vertical line */
.cd-timeline::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 3px;
    transform: translateX(-50%);
    background: var(--bs-border-color);
}

/* TIMELINE BLOCK */
.cd-timeline-block {
    position: relative;
    margin: 2.5rem 0;
}

/* DOT */
.cd-timeline-img {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--bs-primary);
    border: 3px solid var(--bs-body-bg);
    z-index: 2;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
}

/* CARD */
.cd-timeline-content {
    position: relative;
    width: 46%;
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    padding: 1.25rem 1.5rem;
    border-radius: .75rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

/* CARD POSITION LEFT */
.cd-timeline-block:nth-child(odd) .cd-timeline-content {
    float: left;
    clear: both;
}

/* CARD POSITION RIGHT */
.cd-timeline-block:nth-child(even) .cd-timeline-content {
    float: right;
    clear: both;
}

/* ARROWS */
.cd-timeline-block:nth-child(odd) .cd-timeline-content::before {
    content: "";
    position: absolute;
    top: 24px;
    right: -12px;
    border-width: 8px 0 8px 12px;
    border-style: solid;
    border-color: transparent transparent transparent var(--bs-body-bg);
}

.cd-timeline-block:nth-child(even) .cd-timeline-content::before {
    content: "";
    position: absolute;
    top: 24px;
    left: -12px;
    border-width: 8px 12px 8px 0;
    border-style: solid;
    border-color: transparent var(--bs-body-bg) transparent transparent;
}

/* RESPONSIVE (MOBILE) */
@media (max-width: 768px) {
    .cd-timeline::before {
        left: 20px;
        transform: none;
    }

    .cd-timeline-img {
        left: 20px;
        transform: none;
    }

    .cd-timeline-content {
        float: none !important;
        width: calc(100% - 50px);
        margin-left: 50px;
    }

    .cd-timeline-content::before {
        left: -12px !important;
        right: auto !important;
        border-width: 8px 12px 8px 0 !important;
        border-color: transparent var(--bs-body-bg) transparent transparent !important;
    }
}
</style>
@endonce


<div class="cd-timeline">

    @forelse($collection as $item)
    <div class="cd-timeline-block">

        <div class="cd-timeline-img"></div>

        <div class="cd-timeline-content">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary">{{ $item->type_label }}</span>
                <small class="text-muted">{{ $item->created_at->format('d M Y H:i') }}</small>
            </div>

            <p class="mb-3">{{ $item->description }}</p>

            <!-- LIMPAH -->
            @if($item->target_institution || $item->target_division)
            <div class="rounded p-2 mb-3" style="background:rgba(37,99,235,0.12)">
                <b class="text-primary">Limpah:</b><br>
                @if($item->target_institution)
                    Instansi: {{ $item->target_institution->name }}<br>
                @endif
                @if($item->target_division)
                    Unit/Sub-bagian: {{ $item->target_division->name }}
                @endif
            </div>
            @endif

            <!-- FILES -->
            @if($item->evidences->isNotEmpty())
                <h6 class="fw-semibold mb-1">Bukti:</h6>
                @foreach($item->evidences as $e)
                    <button
                        class="btn btn-sm btn-outline-primary btn-preview-file mb-1"
                        data-file-url="{{ $e->file_url }}"
                        data-file-name="{{ basename($e->file_url) }}"
                        data-file-type="{{ strtolower($e->file_type) }}">
                        <i class="fa fa-eye"></i> {{ basename($e->file_url) }}
                    </button>
                @endforeach
            @endif

        </div>

    </div>
    @empty

    <div class="text-center text-muted py-4">
        Belum ada tahapan.
    </div>

    @endforelse

</div>


@if($isPaginator && $items->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $items->links('pagination::bootstrap-5') }}
</div>
@endif
