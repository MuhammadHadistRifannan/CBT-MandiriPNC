<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('alert'))
<script>
    Swal.fire({
        icon: '{{ session('alert.type') }}',
        title: '{{ session('alert.title') }}',
        text: '{{ session('alert.text') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif