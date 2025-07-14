{{-- resources/views/includes/scripts.blade.php --}}

@livewireScripts

{{-- Custom JS: Preline, CSRF token handling, SweetAlert --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle CSRF token update for Livewire
        Livewire.on("sendRequest", function() {
            fetch("{{ route('csrf-token') }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                });
        });

        // Initialize CSRF token if already set
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // console.log(csrfToken); // You can remove this line if not needed
    });
</script>

{{-- jQuery and Select2 (if needed) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- SweetAlert - If there's a success session flash message --}}
@if(session()->has('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let successData = @json(session('success'));
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: successData.icon,
            title: successData.title,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            background: successData.backgroundColor,
            iconColor: successData.iconColor,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            customClass: {
                title: successData.titleColor
            }
        });
    });
</script>
@endif

<!-- Add Dropzone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Livewire Script Configuration (optional, based on your setup) --}}
@livewireScriptConfig
<script>
    Livewire.hook('message.failed', (message, component, response) => {
        if (response.status === 419) {
            alert('Session expired. Reloading...');
            window.location.reload();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('login-success', () => {
            window.location.reload();
        });
    });
</script>