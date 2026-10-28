@if(auth()->check() && in_array(auth()->user()->role, ['student','alumni'], true))
<div id="uc-incoming-call" hidden role="status" aria-live="polite" class="fixed bottom-5 right-5 z-[100] max-w-sm rounded-2xl border border-indigo-400/40 bg-slate-900 p-5 text-white shadow-2xl">
    <p id="uc-call-text" class="font-bold"></p><div class="mt-4 flex gap-3"><a id="uc-call-open" class="rounded-xl bg-indigo-600 px-4 py-2">View / Answer Call</a><button id="uc-call-decline" type="button" class="rounded-xl bg-red-700 px-4 py-2">Decline</button></div>
</div>
<script>
(() => {
    const box = document.getElementById('uc-incoming-call'); let currentCall = null; let loading = false;
    async function checkCall() {
        if (loading || document.hidden) return; loading = true;
        try {
            const response = await fetch(@js(route('calls.incoming')), {headers:{Accept:'application/json'}, cache:'no-store'});
            if (!response.ok) { box.hidden = true; return; }
            const data = await response.json(); currentCall = data.call;
            box.hidden = !currentCall || location.href.split('?')[0] === currentCall.url;
            if (currentCall) { document.getElementById('uc-call-text').textContent = currentCall.name + ' is calling (' + currentCall.type + ')'; document.getElementById('uc-call-open').href = currentCall.url; }
        } catch (error) { box.hidden = true; } finally { loading = false; }
    }
    document.getElementById('uc-call-decline').addEventListener('click', async () => {
        if (!currentCall) return;
        try { const response = await fetch(currentCall.reject_url, {method:'POST',headers:{Accept:'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}});
        if (response.ok) { currentCall = null; box.hidden = true; } } catch(error) {}
    });
    checkCall(); setInterval(checkCall, 5000); document.addEventListener('visibilitychange', () => { if (!document.hidden) checkCall(); });
})();
</script>
@endif
