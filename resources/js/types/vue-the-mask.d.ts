declare module 'vue-the-mask' {
    import type { Directive } from 'vue';

    // Declaración de la directiva v-mask
    export const MaskDirective: Directive;

    // Declaración del componente principal como un objeto con props y eventos
    export const MaskComponent: {
        props: {
            mask: string | Array<string>;
            tokens?: Record<string, any>;
        };
        emits: ['input', 'blur', 'focus'];
    };

    // Exportación predeterminada del plugin
    const VueTheMask: {
        install: (app: any) => void;
    };

    export default VueTheMask;
}
