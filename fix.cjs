const fs = require('fs');
const file = 'resources/views/welcome.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Fix text colors
content = content.replace(/color:\s*white;/g, 'color: var(--white);');
content = content.replace(/background:\s*white;/g, 'background: var(--white);');
content = content.replace(/border-color:\s*white;/g, 'border-color: var(--white);');
content = content.replace(/color:\s*#ffffff;/gi, 'color: var(--white);');
content = content.replace(/border-color:\s*#ffffff;/gi, 'border-color: var(--white);');
content = content.replace(/fill="white"/g, 'fill="var(--white)"');

// Fix hardcoded rgba and hex accents in CSS
content = content.replace(/rgba\(200,\s*169,\s*110,/g, 'rgba(var(--accent-rgb),');
content = content.replace(/rgba\(200,169,110,/g, 'rgba(var(--accent-rgb),');
content = content.replace(/:\s*#C8A96E([;\s\}])/gi, ': var(--accent)$1');
content = content.replace(/1px solid #C8A96E/gi, '1px solid var(--accent)');

// Light mode variables update
const lightModeRegex = /body\.light-mode\s*\{([\s\S]*?)\}/;
const lightModeMatch = content.match(lightModeRegex);
if (lightModeMatch) {
    let block = lightModeMatch[1];
    // change --accent
    block = block.replace(/--accent:\s*#[a-fA-F0-9]+;/, '--accent: #1A73E8;');
    // change --accent-dim
    block = block.replace(/--accent-dim:.*?;/, '--accent-dim: rgba(26, 115, 232, 0.1);');
    // add --accent-rgb if not exists
    if (!block.includes('--accent-rgb:')) {
        block = block.replace(/--accent: #1A73E8;/, '--accent: #1A73E8;\n            --accent-rgb: 26, 115, 232;');
    }
    content = content.replace(lightModeRegex, `body.light-mode {${block}}`);
}

// Write back
fs.writeFileSync(file, content);
console.log('Fixed welcome.blade.php');
