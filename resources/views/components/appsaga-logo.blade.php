<svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Wave/Cloud Graphic Element -->
    <defs>
        <linearGradient id="waveGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#1e40af;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#3b82f6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
        </linearGradient>
    </defs>

    <!-- Top wave stroke -->
    <path d="M5 25 Q15 15 25 25 Q35 35 45 25 Q55 15 65 25"
          stroke="url(#waveGradient)"
          stroke-width="3"
          fill="none"
          stroke-linecap="round" />

    <!-- Middle wave stroke -->
    <path d="M8 30 Q18 20 28 30 Q38 40 48 30 Q58 20 68 30"
          stroke="url(#waveGradient)"
          stroke-width="3"
          fill="none"
          stroke-linecap="round" />

    <!-- Bottom wave stroke -->
    <path d="M11 35 Q21 25 31 35 Q41 45 51 35 Q61 25 71 35"
          stroke="url(#waveGradient)"
          stroke-width="3"
          fill="none"
          stroke-linecap="round" />

    <!-- AppSaga Text -->
    <text x="85" y="25" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="#1e40af">
        AppSaga
    </text>

    <!-- SOLUTIONS Text -->
    <text x="85" y="40" font-family="Arial, sans-serif" font-size="10" font-weight="400" fill="#1e40af" text-transform="uppercase">
        SOLUTIONS
    </text>
</svg>
