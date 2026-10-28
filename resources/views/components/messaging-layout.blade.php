<style>
    .uc-main-area:has(.uc-messages-view) { min-height: 0; padding-top: 12px; padding-bottom: 16px; }
    .uc-messages-view { min-width: 0; --messaging-height: calc(100dvh - 130px); }
    .uc-chat-view .chat-container { height: var(--messaging-height); min-height: 0; max-height: none; border-radius: 20px; box-shadow: 0 12px 38px #02061730; }
    .uc-chat-view #chatMessages { min-height: 0; overscroll-behavior: contain; scrollbar-gutter: stable; padding-block: 14px; }
    .uc-chat-view #chatMessages > .min-h-\[300px\] { min-height: 0; }
    .uc-chat-view .chat-container > .shrink-0 { flex-shrink: 0; }
    .uc-chat-view #messageForm { margin: 0; }
    .uc-inbox-view > .grid { height: var(--messaging-height); gap: 14px; align-items: stretch; }
    .uc-inbox-view .message-panel { display: flex; flex-direction: column; min-width: 0; min-height: 0; border-radius: 18px; box-shadow: 0 8px 26px #02061724; }
    .uc-inbox-view .message-panel > div:not(.message-scroll) { flex-shrink: 0; }
    .uc-inbox-view .message-panel > .message-scroll,
    .uc-inbox-view #userList { flex: 1 1 0; min-height: 0; max-height: none; overscroll-behavior: contain; scrollbar-gutter: stable; }
    .uc-inbox-view .message-panel > div[class*="py-4"] { padding-block: 14px; }
    .uc-inbox-view .uc-inbox-single { grid-template-columns: minmax(0,1fr); max-width: 760px; margin-inline: auto; }
    .uc-inbox-view .uc-inbox-single > section { grid-column: 1 / -1; }
    @media(max-width:1023px) {
        .uc-inbox-view > .grid:not(.uc-inbox-single) { height: auto; }
        .uc-inbox-view > .grid:not(.uc-inbox-single) .message-panel { max-height: min(480px,var(--messaging-height)); }
    }
</style>
<script>
    (() => {
        const surface = document.querySelector('.uc-messages-view');
        if (!surface) return;
        let scheduled;
        const fit = () => {
            cancelAnimationFrame(scheduled);
            scheduled = requestAnimationFrame(() => {
                const viewport = window.visualViewport;
                const bottom = viewport ? viewport.offsetTop + viewport.height : window.innerHeight;
                const top = surface.getBoundingClientRect().top;
                surface.style.setProperty('--messaging-height', Math.max(0, bottom - top - 16) + 'px');
            });
        };
        window.addEventListener('resize', fit, { passive: true });
        window.addEventListener('pageshow', fit);
        window.visualViewport?.addEventListener('resize', fit, { passive: true });
        window.visualViewport?.addEventListener('scroll', fit, { passive: true });
        const navbar = document.querySelector('.uc-topbar-wrap');
        if (navbar) new ResizeObserver(fit).observe(navbar);
        document.fonts?.ready.then(fit);
        fit();
    })();
</script>
