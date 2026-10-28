<style>
    /* Shared only by the Admin and Super Admin Verified DB pages. */
    .uc-verified-db :is(.ai-card,.v-card) {
        padding: 16px !important; border-radius: 17px;
        box-shadow: inset 0 1px 0 #ffffff09,0 8px 24px #02061720;
    }
    .uc-verified-db.space-y-8 > :not([hidden]) ~ :not([hidden]),
    .uc-verified-db .space-y-8 > :not([hidden]) ~ :not([hidden]) { margin-top: 14px !important; }
    .uc-verified-db .grid { gap: 12px !important; }
    .uc-verified-db .uc-verified-stats { grid-template-columns: repeat(4,minmax(0,1fr)); }
    .uc-verified-db .uc-verified-stats > div {
        --stat-accent: 34,211,238;
        position: relative; display: grid; grid-template-columns: minmax(0,1fr) 34px;
        column-gap: 10px; align-items: center; padding: 12px 15px !important;
        border: 1px solid rgba(var(--stat-accent),.24); overflow: hidden;
        background: linear-gradient(115deg,rgba(var(--stat-accent),.14),rgba(15,23,42,.78));
    }
    .uc-verified-db .uc-verified-stats > div:nth-child(2) { --stat-accent: 52,211,153; }
    .uc-verified-db .uc-verified-stats > div:nth-child(3) { --stat-accent: 192,132,252; }
    .uc-verified-db .uc-verified-stats > div:nth-child(4) { --stat-accent: 244,114,182; }
    .uc-verified-stats > div::before { content: ''; position: absolute; inset: 13px auto 13px 0; width: 3px; border-radius: 3px; background: rgb(var(--stat-accent)); }
    .uc-verified-db .uc-verified-stats p { grid-column: 1; grid-row: 1; font-size: 11px; color: #cbd5e1; line-height: 1.4; }
    .uc-verified-db .uc-verified-stats h3 { grid-column: 1; grid-row: 2; margin-top: 4px; font-size: 25px; line-height: 1.1; color: rgb(var(--stat-accent)); }
    .uc-verified-stat-icon { grid-column: 2; grid-row: 1 / 3; display: grid; place-items: center; width: 34px; height: 34px; border-radius: 11px; font-size: 14px; color: rgb(var(--stat-accent)); background: rgba(var(--stat-accent),.13); }
    .uc-verified-db :is(.ai-card,.v-card) h3:not(.uc-verified-stats h3) { font-size: 18px; line-height: 1.35; }
    .uc-verified-db :is(.ai-label,.v-label) { font-size: 12px; margin-bottom: 5px; }
    .uc-verified-db :is(.ai-input,.v-input) { padding: 9px 12px; border-radius: 10px; font-size: 13px; }
    .uc-verified-db textarea[name=notes] { min-height: 64px; height: 64px; resize: vertical; }
    .uc-verified-db textarea[name=bulk_text] { min-height: 110px; padding: 12px; font-size: 13px; border-radius: 12px; resize: vertical; }
    .uc-verified-db .space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 10px; }
    .uc-verified-db :is(.mt-6,.mt-8) { margin-top: 14px; }
    .uc-verified-db :is(.mb-6,.mb-8) { margin-bottom: 14px; }
    .uc-verified-db button[class*="py-4"] { padding-block: 10px; font-size: 13px; border-radius: 11px; }
    @media(max-width:640px) {
        .uc-verified-db .uc-verified-stats { grid-template-columns: repeat(2,minmax(0,1fr)); }
    }
</style>
