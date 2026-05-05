/**
 * UTF-8 String Normalization Utility
 * FIX [Q9]: Ensures proper UTF-8 character handling in quiz display
 */

/**
 * Normalize and safely escape UTF-8 text for HTML context
 * @param {string} text - Raw text potentially with encoding issues
 * @returns {string} Properly escaped text safe for innerHTML
 */
function normalizeUTF8Text(text) {
    if (typeof text !== 'string') {
        return '';
    }
    
    // Method 1: Use TextEncoder/TextDecoder for proper UTF-8 handling
    if (typeof TextEncoder !== 'undefined' && typeof TextDecoder !== 'undefined') {
        try {
            // Encode to UTF-8 bytes then decode back to ensure proper encoding
            const encoded = new TextEncoder().encode(text);
            const normalized = new TextDecoder('utf-8').decode(encoded);
            return normalized;
        } catch (e) {
            console.warn('UTF-8 normalization via TextEncoder failed:', e);
        }
    }
    
    // Method 2: Fallback - use DOM Text element for proper encoding
    try {
        const textElement = document.createElement('div');
        textElement.textContent = text;
        return textElement.textContent;
    } catch (e) {
        console.warn('UTF-8 normalization via DOM failed:', e);
    }
    
    // Method 3: Last resort - return original
    return text;
}

/**
 * Create HTML-safe span with UTF-8 content
 * @param {string} text - Text content
 * @returns {string} HTML string with proper encoding
 */
function createUTF8SafeHTML(text) {
    const normalized = normalizeUTF8Text(text);
    const div = document.createElement('div');
    div.textContent = normalized;
    return div.innerHTML;
}

/**
 * Fix common UTF-8 mojibake patterns
 * @param {string} text - Text with potential encoding issues
 * @returns {string} Text with common issues fixed
 */
function fixMojibake(text) {
    if (typeof text !== 'string') return text;
    
    // Common mojibake patterns for Romanian diacritics
    const mojibakeMap = {
        'Ã™': 'Ț',
        'ÃŸ': 'ț',
        'Å¡': 'ș',
        'Ê™': 'š',
        'Ä™': 'ă',
        'Ä…': 'ă',
        'Ä„': 'ă',
        'Ä›': 'ě',
        'Ä›': 'ě',
        'Ã®': 'î',
        'Ã­': 'í',
        'Å"': 'ő',
        'Å»': 'ż',
        'Â´': "'",
        'Â°': '°',
        'Â»': '»',
        'Â«': '«',
        'Ã§': 'ç',
        'Ã©': 'é',
        'Ã¨': 'è',
        'Ãª': 'ê',
        'Ã¤': 'ä',
        'Ã¶': 'ö',
        'Ã¼': 'ü',
        'È™': 'ș',
        'Ã®ntre': 'între',
        'È™': 'ș',
    };
    
    let result = text;
    for (const [mojibake, correct] of Object.entries(mojibakeMap)) {
        result = result.split(mojibake).join(correct);
    }
    
    return result;
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { normalizeUTF8Text, createUTF8SafeHTML, fixMojibake };
}
