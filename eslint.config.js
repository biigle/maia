import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';

export default [
    js.configs.recommended,
    ...pluginVue.configs['flat/essential'],
    {
        languageOptions: {
            ecmaVersion: 2020,
            sourceType: 'module',
            globals: {
                ...globals.browser,
                biigle: 'readonly',
            },
        },
        rules: {
            'no-prototype-builtins': 'off',
            'no-console': ['error', {allow: ['warn', 'error']}],
            'vue/require-v-for-key': 'off',
            'vue/multi-word-component-names': 'off',
        },
    },
];
