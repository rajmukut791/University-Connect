<style>
    /* Student dashboard only: explicit panels, independent of shared role styles. */
    .uc-student-dashboard { display: grid; grid-template-columns: repeat(12,minmax(0,1fr)); gap: 14px; align-items: stretch; }
    .uc-student-dashboard > * { grid-column: 1 / -1; min-width: 0; }
    .uc-student-dashboard.space-y-7 > :not([hidden]) ~ :not([hidden]) { margin-top: 0 !important; }
    .uc-student-dashboard > div[class*="border"],
    .uc-student-dashboard .uc-student-strength > div,
    .uc-student-dashboard .uc-student-opportunities > div {
        padding: 16px !important; border-radius: 17px !important;
        border-color: #a5b4fc26; background: linear-gradient(135deg,#15213be8,#191a32e8);
        box-shadow: inset 0 1px 0 #ffffff0a,0 8px 26px #02061720 !important;
    }
    .uc-student-dashboard .uc-student-stats { grid-template-columns: repeat(4,minmax(0,1fr)); }
    .uc-student-dashboard .uc-student-stats > a { padding: 12px 14px !important; border-radius: 15px; background: linear-gradient(120deg,#1e294be6,#0f172ae6); }
    .uc-student-dashboard .uc-student-stats > a:nth-child(2) { border-color: #f472b640; }
    .uc-student-dashboard .uc-student-stats > a:nth-child(3) { border-color: #a78bfa40; }
    .uc-student-dashboard .uc-student-stats > a:nth-child(4) { border-color: #34d39940; }
    .uc-student-dashboard .uc-student-stats .text-3xl { font-size: 24px; line-height: 1.2; }
    .uc-student-dashboard h2.text-2xl { font-size: 16px !important; margin-top: 4px !important; }
    .uc-student-dashboard p[class*="tracking-"] { font-size: 9px; letter-spacing: .16em; }
    .uc-student-dashboard :is(.mt-5,.mt-6,.mt-7,.mt-8) { margin-top: 12px !important; }
    .uc-student-strength .h-28 { width: 76px; height: 76px; }
    .uc-student-strength .h-28 > div { width: 60px; height: 60px; }
    .uc-student-strength .h-28 .text-2xl { font-size: 19px; }
    .uc-student-dashboard :is(.uc-student-strength,.uc-student-opportunities) a[class*="py-3"] { padding-block: 9px; font-size: 12px; border-radius: 10px; }
    .uc-student-opportunities { align-items: stretch; }
    .uc-student-opportunities :is(.p-5,.p-7) { padding: 12px !important; }
    .uc-student-opportunities h3 { font-size: 14px; }
    .uc-student-opportunities p { font-size: 12px; }
    .uc-student-mentor-panel > .flex { gap: 12px !important; }
    .uc-student-mentor-panel p.max-w-2xl { font-size: 12px; line-height: 1.6; }
    .uc-student-dashboard .uc-student-quick-panel > .grid { grid-template-columns: repeat(3,minmax(0,1fr)); }
    .uc-student-quick-panel > .grid > a { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 7px; padding: 12px 8px !important; border-radius: 12px; }
    .uc-student-quick-panel > .grid > a p { margin-top: 0; font-size: 12px; }
    .uc-student-quick-panel > .grid > a > div { width: 30px; height: 30px; }
    .uc-student-dashboard .uc-student-strength { display: flex; }
    .uc-student-dashboard .uc-student-strength > div { width: 100%; display: flex; flex-direction: column; }
    .uc-student-dashboard .uc-student-strength > div > a { margin-top: auto !important; }
    .uc-student-dashboard .uc-student-strength > div > div:last-of-type { margin-bottom: 12px; }
    .uc-student-opportunities > div { display: flex; flex-direction: column; min-width: 0; }
    .uc-student-opportunities > div > .space-y-3 { flex: 1; }
    .uc-student-opportunities > div > .space-y-3 > div.text-center {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-height: 112px; padding: 18px 12px !important;
    }
    .uc-student-dashboard .uc-student-mentor-panel { display: flex; align-items: center; }
    .uc-student-mentor-panel > .flex { width: 100%; }
    .uc-student-dashboard .uc-student-quick-panel { display: flex; flex-direction: column; justify-content: center; }
    @media(min-width:800px) and (max-width:1199px) {
        .uc-student-dashboard .uc-student-opportunities { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .uc-student-opportunities > div { grid-column: auto; }
    }
    @media(min-width:1200px) {
        .uc-student-dashboard .uc-student-strength { grid-column: span 4; }
        /* Flatten this grouping so all three panels share one grid row and baseline. */
        .uc-student-dashboard .uc-student-opportunities { display: contents; }
        .uc-student-opportunities > div:first-child { grid-column: span 5; }
        .uc-student-opportunities > div:last-child { grid-column: span 3; }
        .uc-student-opportunities > div > .space-y-3 > div.text-center { height: 100%; }
        .uc-student-dashboard .uc-student-mentor-panel,
        .uc-student-dashboard .uc-student-quick-panel { grid-column: span 6; }
    }
    @media(max-width:640px) {
        .uc-student-dashboard .uc-student-stats { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .uc-student-dashboard > div[class*="border"], .uc-student-dashboard .uc-student-strength > div { padding: 14px !important; }
    }
</style>
