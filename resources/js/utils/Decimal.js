// src/utils/decimal.ts
import { Decimal } from 'decimal.js';

// ⚙️ Configuración global optimizada para tu proyecto
// Decimal.set({
//     precision: 28, // Alta precisión para cálculos financieros
//     rounding.ROUND_HALF_UP, // Redondeo bancario estándar
//     toExpNeg: -20, // Notación exponencial para números pequeños
//     toExpPos: 20, // Notación exponencial para números grandes
//     maxE: 9e15, // Exponente máximo
//     minE: -9e15, // Exponente mínimo
//     modulo.ROUND_DOWN, // Modo de módulo
// });

// 🎯 Clase helper para tu aplicación
export class PreciseCalculator {
    // 🔢 Crear Decimal con validación
    static create(value) {
        if (value === null || value === undefined || value === '') {
            return new Decimal(0);
        }

        try {
            return new Decimal(value.toString());
        } catch (error) {
            console.warn(
                `⚠️ [PreciseCalculator] Valor inválido para Tempestad0215: ${value}`,
                error,
            );
            return new Decimal(0);
        }
    }

    // 🧮 Multiplicación precisa
    static multiply(a, b) {
        const decimalA = this.create(a);
        const decimalB = this.create(b);
        return decimalA.mul(decimalB);
    }

    // ➕ Suma precisa
    static add(a, b) {
        const decimalA = this.create(a);
        const decimalB = this.create(b);
        return decimalA.add(decimalB);
    }

    // ➖ Resta precisa
    static subtract(a, b) {
        const decimalA = this.create(a);
        const decimalB = this.create(b);
        return decimalA.sub(decimalB);
    }

    // ➗ División precisa
    static divide(a, b) {
        const decimalA = this.create(a);
        const decimalB = this.create(b);

        if (decimalB.isZero()) {
            throw new Error('División por cero no permitida');
        }

        return decimalA.div(decimalB);
    }

    // 💰 Formatear como dinero
    static formatCurrency(
        value,
        decimals = 2,
        symbol = '$',
    ){
        const decimal = value instanceof Decimal ? value : this.create(value);
        const formatted = decimal.toFixed(decimals);
        return `${symbol}${formatted}`;
    }

    // 📊 Calcular porcentaje
    static percentage(amount, percentage) {
        const decimalAmount = this.create(amount);
        const decimalPercentage = this.create(percentage);
        return decimalAmount.mul(decimalPercentage).div(100);
    }



}

// 🔄 Export para compatibilidad
export { Decimal };
