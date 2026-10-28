<style>
    /* Feature headers become action rows. All links, forms and role checks remain intact. */
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end;
        gap: 10px !important; min-height: 0 !important; height: auto !important;
        padding: 0 !important; margin: 0 0 14px !important;
        background: none !important; border: 0 !important; border-radius: 0 !important;
        box-shadow: none !important; backdrop-filter: none !important;
    }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header):not(:has(a,button,form,.uc-header-context)) { display: none !important; }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) div {
        display: contents !important; min-height: 0 !important; padding: 0 !important;
        background: none !important; border: 0 !important; box-shadow: none !important;
    }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) div.absolute,
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) div[class*="tracking-"]:not(:has(a,button,form,.uc-header-context)),
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) :is(h1,h2,h3,p):not(.uc-header-context):not(:has(a,button,form)),
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) :is(i,svg,span):not(a *,button *,.uc-header-context) { display: none !important; }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) .uc-header-context {
        display: block !important; flex-basis: 100%; order: -1; margin: 0 !important;
        color: #cbd5e1; font-size: 14px !important; line-height: 1.5 !important;
    }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) :is(h1,h2).uc-header-context {
        font-size: 20px !important; color: #f8fafc; font-weight: 800;
    }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) :is(a,button) {
        display: inline-flex !important; align-items: center; justify-content: center;
        gap: 7px; min-height: 40px; padding: 9px 14px !important; margin: 0 !important;
        border-radius: 11px !important; font-size: 12px !important; line-height: 1.4;
        box-shadow: inset 0 1px 0 #ffffff15,0 4px 14px #02061720 !important;
    }
    .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) form { margin: 0 !important; }
    @media(max-width:640px) {
        .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) { gap: 8px !important; }
        .uc-bannerless.uc-main-area :is(.uc-page-hero,.uc-compact-header) :is(a,button) { min-height: 42px; }
    }
</style>
