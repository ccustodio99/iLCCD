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
}

[primaryInput, accentInput, fontPrimarySelect, fontSecondarySelect, headingInput, taglineInput].forEach(el => {
    if (el) el.addEventListener('input', updatePreview);
});

updatePreview();
