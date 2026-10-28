<style>
    .uc-event-details { min-height: 0; }
    .uc-event-details .uc-event-details-shell { padding: 16px !important; border-radius: 20px; }
    .uc-event-details .event-glass { border-radius: 16px !important; }
    .uc-event-details .event-glass:is(.p-6,.p-7),
    .uc-event-details .event-glass > .p-6 { padding: 14px !important; }
    .uc-event-details .grid { gap: 14px !important; }
    .uc-event-details .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 14px; }
    .uc-event-details .space-y-5 > :not([hidden]) ~ :not([hidden]) { margin-top: 12px; }
    .uc-event-details :is(.mt-6,.mt-5) { margin-top: 12px !important; }
    .uc-event-details .event-glass img { height: clamp(120px,22vh,190px) !important; object-fit: contain; background: #020617; }
    .uc-event-details .h-\[280px\] { height: 88px; }
    .uc-event-details .h-28 { width: 48px; height: 48px; border-radius: 14px; font-size: 24px; }
    .uc-event-details .h-11 { width: 34px; height: 34px; border-radius: 11px; }
    .uc-event-details h2 { font-size: 17px; }
    .uc-event-details h3 { font-size: 16px; }
    .uc-event-details p.leading-7 { line-height: 1.7; margin-top: 8px; }
    .uc-event-details .event-glass :is(a,button)[class*="py-3"] { padding-block: 9px; font-size: 13px; border-radius: 11px; }
    @media(min-width:1000px) {
        .uc-event-details-shell > .grid { grid-template-columns: repeat(12,minmax(0,1fr)); }
        .uc-event-details-shell > .grid > div:first-child { grid-column: span 8; }
        .uc-event-details-shell > .grid > div:last-child { grid-column: span 4; }
    }
</style>
