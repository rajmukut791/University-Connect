<style>
    /* Avoid a scrolling ancestor so the shared role navbar stays sticky to the viewport. */
    .uc-app-shell { overflow: clip; }
    .uc-topbar-wrap {
        position: sticky !important; top: 0 !important;
        background: transparent; border-bottom: 1px solid transparent;
        transition: background-color .2s ease,border-color .2s ease;
    }
    .uc-topbar-wrap.uc-navbar-scrolled {
        background: rgba(9,15,31,.86); border-bottom-color: #a5b4fc16;
        backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
    }
    .uc-topbar {
        min-height: 62px; padding-block: 8px; border-radius: 0;
        background: transparent; border: 0; box-shadow: none;
        backdrop-filter: none; animation: none;
    }
    .uc-topbar::before { display: none; }
    .uc-topbar .uc-brand { gap: 9px; }
    .uc-topbar .uc-logo-box {
        width: 42px; height: 42px; border-radius: 13px;
        background: linear-gradient(145deg,#6366f1,#8b5cf6 65%,#a855f7);
        border: 1px solid #c4b5fd40;
        box-shadow: 0 4px 16px #6366f126,inset 0 1px 0 #ffffff26;
    }
    .uc-topbar .uc-logo-box i { font-size: 19px; }
    .uc-topbar .uc-brand-title { font-size: 14px; font-weight: 900; letter-spacing: -.025em; color: #f8fafc; }
    .uc-topbar .uc-brand-subtitle { font-size: 9px; font-weight: 600; color: #a5b4cc; }
    .uc-topbar .uc-brand-copy { display: block; }
    .uc-topbar .uc-nav-row { padding: 3px; gap: 5px; }
    .uc-topbar .uc-desktop-nav .uc-nav {
        flex: 0 1 auto; min-height: 33px; padding: 0 max(7px,calc(var(--uc-nav-padding,9px) - 1px));
        font-size: var(--uc-nav-font,11px); border-radius: 10px; font-weight: 700;
        color: #e2e8f0; background: #ffffff0b; border-color: #c7d2fe26;
        box-shadow: inset 0 1px 0 #ffffff08;
    }
    .uc-topbar .uc-nav::before { display: none; }
    .uc-topbar .uc-nav i { color: #c7d2fe; }
    .uc-topbar .uc-nav:hover { transform: none; color: #fff; background: #ffffff16; border-color: #c7d2fe45; box-shadow: none; }
    .uc-topbar .uc-nav.uc-active {
        background: linear-gradient(120deg,#6366f14d,#8b5cf638) !important;
        color: #fff !important; border-color: #a5b4fc80 !important;
        box-shadow: inset 0 1px 0 #c4b5fd24 !important; animation: none;
    }
    .uc-topbar .uc-nav.uc-active i { color: #e9d5ff; }
    .uc-topbar .uc-profile-pill { background: #ffffff05; border-color: #ffffff14; box-shadow: none; }
    .uc-topbar .uc-profile-pill:hover { transform: none; background: #ffffff0c; box-shadow: none; }
    .uc-topbar .uc-profile-pill { width: max-content; min-width: 0; max-width: min(320px,48vw); }
    .uc-topbar .uc-profile-copy { display: block !important; flex: 0 1 auto; min-width: 0; }
    .uc-topbar .uc-profile-copy > p {
        white-space: normal; overflow: visible; text-overflow: clip;
        overflow-wrap: anywhere; line-height: 1.4; max-width: 230px;
    }
    .uc-topbar :is(.uc-nav,.uc-brand,.uc-profile-pill):focus-visible { outline: 2px solid #a5b4fc; outline-offset: 3px; }
    @media(max-width:1023px) {
        .uc-topbar .uc-topbar-inner { row-gap: 8px; }
        .uc-topbar .uc-desktop-nav .uc-nav { min-height: 36px; font-size: 11px; padding: 0 9px; }
    }
    @media(max-width:640px) {
        .uc-topbar { border-radius: 0; }
        .uc-topbar .uc-brand-title { font-size: 13px; }
        .uc-topbar .uc-logo-box { width: 36px; height: 36px; }
    }
    .uc-notification-link { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex-shrink: 0; border: 1px solid #a5b4fc26; border-radius: 11px; color: #c7d2fe; background: #ffffff08; }
    .uc-notification-link span { position: absolute; top: -5px; right: -5px; min-width: 17px; padding: 2px 4px; border-radius: 9px; font-size: 9px; color: white; background: #be185d; }
</style>
