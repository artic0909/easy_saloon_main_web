@if(isset($show_scratch_card) && $show_scratch_card)
<!-- Scratch Card Modal -->
<div id="scratchCardModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl shadow-2xl text-center relative max-w-sm w-full mx-4 overflow-hidden">
        <h3 class="text-2xl font-bold text-[#3d2b1f] mb-2" style="font-family: 'Playfair Display', serif;">Congratulations!</h3>
        <p class="text-gray-500 mb-6 text-sm">Scratch the card below to reveal your surprise reward for your first booking!</p>
        
        <div class="relative w-64 h-32 mx-auto rounded-2xl overflow-hidden shadow-inner border border-gray-100 bg-[#fdfbf7] flex items-center justify-center flex-col select-none">
            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                <span class="text-3xl font-black text-[#c6a664]">FREE</span>
                <span class="text-sm font-bold text-[#3d2b1f]">Second Booking!</span>
            </div>
            <canvas id="scratchCanvas" class="absolute inset-0 w-full h-full cursor-pointer touch-none" style="z-index: 10;"></canvas>
        </div>
        
        <div id="claimResult" class="mt-6 hidden">
            <p class="text-green-600 font-bold mb-4 text-sm">You have unlocked a FREE eligible service on your next booking!</p>
            <button onclick="closeScratchCard()" class="bg-[#3d2b1f] text-white px-8 py-3 rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-[#c6a664] transition-all w-full">Awesome!</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('scratchCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const resultDiv = document.getElementById('claimResult');
        let isDrawing = false;
        let isClaimed = false;

        // Set actual size in memory
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        // Fill canvas with a scratchable layer (gold color)
        ctx.fillStyle = '#c6a664';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Add some text or pattern on top
        ctx.font = 'bold 20px "Outfit", sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('SCRATCH ME', canvas.width / 2, canvas.height / 2);

        function getMousePos(canvas, evt) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: evt.clientX - rect.left,
                y: evt.clientY - rect.top
            };
        }

        function getTouchPos(canvas, evt) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: evt.touches[0].clientX - rect.left,
                y: evt.touches[0].clientY - rect.top
            };
        }

        function scratch(x, y) {
            ctx.globalCompositeOperation = 'destination-out';
            ctx.beginPath();
            ctx.arc(x, y, 15, 0, Math.PI * 2, false);
            ctx.fill();
            checkProgress();
        }

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            const pos = getMousePos(canvas, e);
            scratch(pos.x, pos.y);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (isDrawing) {
                const pos = getMousePos(canvas, e);
                scratch(pos.x, pos.y);
            }
        });

        canvas.addEventListener('mouseup', () => isDrawing = false);
        canvas.addEventListener('mouseleave', () => isDrawing = false);
        
        // Touch events
        canvas.addEventListener('touchstart', (e) => {
            isDrawing = true;
            const pos = getTouchPos(canvas, e);
            scratch(pos.x, pos.y);
            e.preventDefault();
        }, { passive: false });
        
        canvas.addEventListener('touchmove', (e) => {
            if (isDrawing) {
                const pos = getTouchPos(canvas, e);
                scratch(pos.x, pos.y);
            }
            e.preventDefault();
        }, { passive: false });
        
        canvas.addEventListener('touchend', () => isDrawing = false);

        function checkProgress() {
            if (isClaimed) return;
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const pixels = imageData.data;
            let transparentPixels = 0;
            
            for (let i = 3; i < pixels.length; i += 4) {
                if (pixels[i] < 128) {
                    transparentPixels++;
                }
            }
            
            const totalPixels = pixels.length / 4;
            const percentage = (transparentPixels / totalPixels) * 100;
            
            if (percentage > 40) {
                isClaimed = true;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                canvas.style.pointerEvents = 'none';
                
                fetch('{{ route('dashboard.scratch-card.claim') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        resultDiv.classList.remove('hidden');
                    }
                });
            }
        }
    });

    function closeScratchCard() {
        document.getElementById('scratchCardModal').style.display = 'none';
    }
</script>
@endif
