<style>
    /* One centered desktop content width; feed posts keep their existing reading width. */
    .uc-topbar .uc-topbar-inner { width: 100%; max-width: 1200px; margin-inline: auto; }
    .uc-main-area.uc-constrained-page > div { width: 100%; max-width: 1200px; margin-inline: auto; }
    .uc-post-grid { align-items: start; justify-content: start; }
    .uc-post-grid > :is(.uc-card,.donation-card) { width: 100%; min-width: 0; max-width: 392px; }
    .uc-post-grid > .text-center { max-width: none; }
    @media(min-width:1024px) {
        .uc-post-grid { grid-template-columns: repeat(3,minmax(0,1fr)) !important; }
    }
    @media(max-width:639px) {
        .uc-post-grid > :is(.uc-card,.donation-card) { max-width: none; }
    }
</style>
