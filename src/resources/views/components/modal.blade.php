@props(['name', 'show' => false])

<div class="modal fade" id="{{ $name }}" tabindex="-1" @if($show) data-bs-show="true" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal('#{{ $name }}');
        if ({{ $show ? 'true' : 'false' }}) {
            modal.show();
        }
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: { name: '{{ $name }}' }
        }));
    });
    document.addEventListener('open-modal', function (e) {
        if (e.detail.name === '{{ $name }}') {
            const modal = new bootstrap.Modal('#{{ $name }}');
            modal.show();
        }
    });
    document.addEventListener('close-modal', function () {
        const modal = bootstrap.Modal.getInstance(document.getElementById('{{ $name }}'));
        if (modal) modal.hide();
    });
</script>
@endpush
