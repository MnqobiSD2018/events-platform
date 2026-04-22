<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="brand-kicker">Scanner</p>
            <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">QR Check-in Scanner</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="brand-panel p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-600">Allow camera access, then scan the participant QR code to check them in.</p>
                    <a href="{{ route('events.index') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Back to Events</a>
                </div>
                <div id="reader" class="mt-4 overflow-hidden rounded-lg border border-slate-200"></div>
                <div id="result" class="mt-4 rounded-lg bg-slate-50 p-3 text-sm text-slate-700">Waiting for scan...</div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const result = document.getElementById("result");
        let processing = false;

        const qrCodeSuccessCallback = async (decodedText) => {
            if (processing) {
                return;
            }

            processing = true;
            result.textContent = "Processing check-in...";

            try {
                const response = await fetch(decodedText, {
                    method: "GET",
                    headers: { "Accept": "application/json" },
                });
                const data = await response.json();
                result.textContent = data.message || "Scan complete.";
            } catch (error) {
                result.textContent = "Failed to process this QR code.";
            } finally {
                setTimeout(() => {
                    processing = false;
                }, 1200);
            }
        };

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 260 },
            qrCodeSuccessCallback
        );
    </script>
</x-app-layout>