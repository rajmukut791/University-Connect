<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes callFloat {
                0%,100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-14px) scale(1.03); }
            }

            @keyframes callGlow {
                0%,100% { box-shadow: 0 25px 80px rgba(34,211,238,.20); }
                50% { box-shadow: 0 35px 110px rgba(236,72,153,.28); }
            }

            .call-orb {
                animation: callFloat 7s ease-in-out infinite;
            }

            .call-panel {
                animation: callGlow 5s ease-in-out infinite;
            }
        </style>

        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950 via-indigo-950 to-fuchsia-950 p-5 shadow-2xl md:p-7">

            <div class="call-orb absolute -top-24 -right-24 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="call-orb absolute top-64 -left-28 h-80 w-80 rounded-full bg-fuchsia-500/20 blur-3xl" style="animation-delay:2s"></div>
            <div class="call-orb absolute -bottom-28 right-1/3 h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl" style="animation-delay:4s"></div>

            <div class="relative z-10 mb-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[.35em] text-cyan-300">
                        Real WebRTC Call
                    </p>

                    <h1 class="mt-2 text-3xl font-black">
                        {{ ucfirst($call->type) }} Call with {{ $otherUser->name }}
                    </h1>

                    <p id="callStatusText" class="mt-2 text-sm font-bold text-slate-400">
                        Status: {{ ucfirst($call->status) }}
                    </p>
                </div>

                <a href="{{ route('messages.show', $otherUser) }}"
                   class="rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white hover:bg-white/15">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Chat
                </a>
            </div>

            <div class="call-panel relative z-10 overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.06] p-5 backdrop-blur-2xl">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    <div class="relative min-h-[360px] overflow-hidden rounded-[1.7rem] border border-white/10 bg-slate-950">
                        <video id="remoteVideo"
                               autoplay
                               playsinline
                               class="h-full min-h-[360px] w-full object-cover bg-slate-950"></video>

                        <div class="absolute left-4 top-4 rounded-full bg-black/50 px-4 py-2 text-xs font-black">
                            {{ $otherUser->name }}
                        </div>
                    </div>

                    <div class="relative min-h-[360px] overflow-hidden rounded-[1.7rem] border border-white/10 bg-slate-950">
                        <video id="localVideo"
                               autoplay
                               playsinline
                               muted
                               class="h-full min-h-[360px] w-full object-cover bg-slate-950"></video>

                        <div class="absolute left-4 top-4 rounded-full bg-black/50 px-4 py-2 text-xs font-black">
                            You
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">

                    @if(auth()->id() === $call->receiver_id && $call->status === 'ringing')
                        <button id="acceptCallBtn"
                                class="rounded-2xl bg-emerald-500 px-6 py-4 text-sm font-black text-white shadow-xl hover:scale-105 transition">
                            <i class="fas fa-phone mr-2"></i>
                            Accept
                        </button>

                        <button id="rejectCallBtn"
                                class="rounded-2xl bg-red-500 px-6 py-4 text-sm font-black text-white shadow-xl hover:scale-105 transition">
                            <i class="fas fa-phone-slash mr-2"></i>
                            Reject
                        </button>
                    @endif

                    <button id="toggleMicBtn"
                            class="rounded-2xl border border-white/10 bg-white/10 px-6 py-4 text-sm font-black text-white hover:bg-white/15">
                        <i class="fas fa-microphone mr-2"></i>
                        Mic
                    </button>

                    @if($call->type === 'video')
                        <button id="toggleCameraBtn"
                                class="rounded-2xl border border-white/10 bg-white/10 px-6 py-4 text-sm font-black text-white hover:bg-white/15">
                            <i class="fas fa-video mr-2"></i>
                            Camera
                        </button>
                    @endif

                    <button id="endCallBtn"
                            class="rounded-2xl bg-red-600 px-6 py-4 text-sm font-black text-white shadow-xl hover:scale-105 transition">
                        <i class="fas fa-xmark mr-2"></i>
                        End Call
                    </button>
                </div>
            </div>
        </div>
    </div>
        <script>
        const callId = {{ $call->id }};
        const authId = {{ auth()->id() }};
        const callerId = {{ $call->caller_id }};
        const receiverId = {{ $call->receiver_id }};
        const callType = "{{ $call->type }}";

        const isCaller = authId === callerId;
        const isReceiver = authId === receiverId;

        const csrfToken = "{{ csrf_token() }}";

        let localStream = null;
        let remoteStream = null;
        let peerConnection = null;
        let pollInterval = null;
        let micEnabled = true;
        let cameraEnabled = true;
        let handledAnswer = false;
        let handledOffer = false;
        let usedCandidates = new Set();

        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');
        const callStatusText = document.getElementById('callStatusText');

        const acceptCallBtn = document.getElementById('acceptCallBtn');
        const rejectCallBtn = document.getElementById('rejectCallBtn');
        const endCallBtn = document.getElementById('endCallBtn');
        const toggleMicBtn = document.getElementById('toggleMicBtn');
        const toggleCameraBtn = document.getElementById('toggleCameraBtn');

        const iceServers = { iceServers: @json(config('services.calls.ice_servers')) };

        async function postJson(url, data = {}) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            return response.json();
        }

        async function getJson(url) {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            return response.json();
        }

        function updateStatus(text) {
            if (callStatusText) {
                callStatusText.innerText = 'Status: ' + text;
            }
        }

        async function prepareMedia() {
            localStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: callType === 'video'
            });

            localVideo.srcObject = localStream;

            remoteStream = new MediaStream();
            remoteVideo.srcObject = remoteStream;
        }

        function createPeerConnection() {
            peerConnection = new RTCPeerConnection(iceServers);

            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });

            peerConnection.ontrack = event => {
                event.streams[0].getTracks().forEach(track => {
                    remoteStream.addTrack(track);
                });
            };

            peerConnection.onicecandidate = async event => {
                if (event.candidate) {
                    await postJson("{{ route('calls.candidate', $call) }}", {
                        candidate: event.candidate
                    });
                }
            };

            peerConnection.onconnectionstatechange = () => {
                if (!peerConnection) return;

                if (peerConnection.connectionState === 'connected') {
                    updateStatus('Connected');
                }

                if (
                    peerConnection.connectionState === 'failed' ||
                    peerConnection.connectionState === 'disconnected'
                ) {
                    updateStatus('Connection Problem');
                }

                if (peerConnection.connectionState === 'closed') {
                    updateStatus('Call Ended');
                }
            };
        }

        async function callerStart() {
            await prepareMedia();
            createPeerConnection();

            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);

            await postJson("{{ route('calls.offer', $call) }}", {
                offer: offer
            });

            updateStatus('Calling...');
        }

        async function receiverAccept() {
            await postJson("{{ route('calls.accept', $call) }}", {});
            await prepareMedia();
            createPeerConnection();

            updateStatus('Accepted. Connecting...');
        }

        async function handleOffer(data) {
            if (!data.offer || handledOffer || !isReceiver) return;

            if (!peerConnection) {
                await prepareMedia();
                createPeerConnection();
            }

            handledOffer = true;

            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.offer));

            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);

            await postJson("{{ route('calls.answer', $call) }}", {
                answer: answer
            });

            updateStatus('Connecting...');
        }

        async function handleAnswer(data) {
            if (!data.answer || handledAnswer || !isCaller) return;

            handledAnswer = true;
            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));

            updateStatus('Connecting...');
        }

        async function handleCandidates(data) {
            if (!peerConnection) return;

            const candidates = isCaller
                ? (data.receiver_candidates || [])
                : (data.caller_candidates || []);

            for (const candidate of candidates) {
                const key = JSON.stringify(candidate);

                if (!usedCandidates.has(key)) {
                    usedCandidates.add(key);

                    try {
                        await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                    } catch (error) {
                        console.error('Candidate error:', error);
                    }
                }
            }
        }

        async function pollCall() {
            const data = await getJson("{{ route('calls.poll', $call) }}");

            updateStatus(data.status.charAt(0).toUpperCase() + data.status.slice(1));

            if (data.status === 'rejected' || data.status === 'ended') {
                cleanupCall();
                return;
            }

            if (data.status === 'accepted') {
                if (isCaller && data.answer) {
                    await handleAnswer(data);
                }

                if (isReceiver && data.offer) {
                    await handleOffer(data);
                }

                await handleCandidates(data);
            }
        }

        function cleanupCall() {
            if (pollInterval) {
                clearInterval(pollInterval);
            }

            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }

            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }

            updateStatus('Call Ended');
        }

        if (isCaller) {
            callerStart();
        }

        pollInterval = setInterval(pollCall, 1500);

        if (acceptCallBtn) {
            acceptCallBtn.addEventListener('click', async () => {
                await receiverAccept();

                acceptCallBtn.classList.add('hidden');

                if (rejectCallBtn) {
                    rejectCallBtn.classList.add('hidden');
                }
            });
        }

        if (rejectCallBtn) {
            rejectCallBtn.addEventListener('click', async () => {
                await postJson("{{ route('calls.reject', $call) }}", {});
                cleanupCall();
                window.location.href = "{{ route('messages.show', $otherUser) }}";
            });
        }

        if (endCallBtn) {
            endCallBtn.addEventListener('click', async () => {
                await postJson("{{ route('calls.end', $call) }}", {});
                cleanupCall();
                window.location.href = "{{ route('messages.show', $otherUser) }}";
            });
        }

        if (toggleMicBtn) {
            toggleMicBtn.addEventListener('click', () => {
                if (!localStream) return;

                micEnabled = !micEnabled;

                localStream.getAudioTracks().forEach(track => {
                    track.enabled = micEnabled;
                });

                toggleMicBtn.innerHTML = micEnabled
                    ? '<i class="fas fa-microphone mr-2"></i> Mic'
                    : '<i class="fas fa-microphone-slash mr-2"></i> Muted';
            });
        }

        if (toggleCameraBtn) {
            toggleCameraBtn.addEventListener('click', () => {
                if (!localStream) return;

                cameraEnabled = !cameraEnabled;

                localStream.getVideoTracks().forEach(track => {
                    track.enabled = cameraEnabled;
                });

                toggleCameraBtn.innerHTML = cameraEnabled
                    ? '<i class="fas fa-video mr-2"></i> Camera'
                    : '<i class="fas fa-video-slash mr-2"></i> Camera Off';
            });
        }

        window.addEventListener('beforeunload', () => {
            cleanupCall();
        });
    </script>
</x-app-layout>