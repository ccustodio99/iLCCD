const primaryInput = document.getElementById('color_primary');
const accentInput = document.getElementById('color_accent');
const fontPrimarySelect = document.getElementById('font_primary');
const fontSecondarySelect = document.getElementById('font_secondary');
const headingInput = document.getElementById('home_heading');
const taglineInput = document.getElementById('home_tagline');

const preview = document.getElementById('theme-preview');
const previewHeading = preview ? preview.querySelector('.preview-heading') : null;
const previewTagline = preview ? preview.querySelector('.preview-tagline') : null;
const primarySwatch = document.getElementById('primary_color_preview');
const accentSwatch = document.getElementById('accent_color_preview');
const contrastWarning = document.getElementById('contrast_warning');

function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result
        ? {
              r: parseInt(result[1], 16),
              g: parseInt(result[2], 16),
              b: parseInt(result[3], 16),
          }
        : null;
}

function luminance({ r, g, b }) {
    const srgb = [r, g, b].map(v => {
        const c = v / 255;
        return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
    });
    return 0.2126 * srgb[0] + 0.7152 * srgb[1] + 0.0722 * srgb[2];
}

function contrast(hex1, hex2) {
    const rgb1 = hexToRgb(hex1);
    const rgb2 = hexToRgb(hex2);
    if (!rgb1 || !rgb2) return 1;
    const l1 = luminance(rgb1);
    const l2 = luminance(rgb2);
    return (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);
}

function updatePreview() {
    if (!preview) return;
    preview.style.setProperty('--color-primary', primaryInput.value);
    preview.style.setProperty('--color-accent', accentInput.value);
    preview.style.setProperty('--font-primary', fontPrimarySelect.value);
    preview.style.setProperty('--font-secondary', fontSecondarySelect.value);
    if (previewHeading) previewHeading.textContent = headingInput.value;
    if (previewTagline) previewTagline.textContent = taglineInput.value;
    if (primarySwatch) primarySwatch.style.background = primaryInput.value;
    if (accentSwatch) accentSwatch.style.background = accentInput.value;

    const ratio = contrast(primaryInput.value, accentInput.value);
    if (contrastWarning) {
        if (ratio < 4.5) {
            contrastWarning.textContent = `Low contrast: ${ratio.toFixed(2)}:1. WCAG AA recommends at least 4.5:1.`;
            contrastWarning.classList.remove('d-none');
        } else {
            contrastWarning.textContent = '';
            contrastWarning.classList.add('d-none');
        }
    }
}

[primaryInput, accentInput, fontPrimarySelect, fontSecondarySelect, headingInput, taglineInput].forEach(el => {
    if (el) el.addEventListener('input', updatePreview);
});

updatePreview();
