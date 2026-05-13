import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

const replaceDeprecatedColorAdjust = {
    postcssPlugin: 'replace-deprecated-color-adjust',
    Declaration(decl) {
        if (decl.prop === 'color-adjust') {
            decl.prop = 'print-color-adjust';
        }
    },
};

export default {
    plugins: [
        tailwindcss(),
        autoprefixer(),
        replaceDeprecatedColorAdjust,
    ],
};
